<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\Book;
use common\models\BookChapter;
use common\models\UserReadingProgress;
use common\services\BookApiService;

/**
 * Book controller for frontend bookshop
 */
class BookController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'search', 'read'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['import'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAuthor() || Yii::$app->user->identity->isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'import' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all books
     */
    public function actionIndex()
    {
        $query = Book::find()
            ->with(['author', 'chapters'])
            ->where(['status' => Book::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC]);

        $books = $query->all();

        return $this->render('index', [
            'books' => $books,
        ]);
    }

    /**
     * Displays a single book
     */
    public function actionView($id)
    {
        $book = $this->findBook($id);
        $chapters = $book->chapters;
        
        // Check if user has reached preview limit
        $previewLimitReached = false;
        if (!Yii::$app->user->isGuest) {
            $previewLimitReached = UserReadingProgress::hasReachedPreviewLimit(
                Yii::$app->user->id, 
                $book->id
            );
        }

        return $this->render('view', [
            'book' => $book,
            'chapters' => $chapters,
            'previewLimitReached' => $previewLimitReached,
        ]);
    }

    /**
     * Search books
     */
    public function actionSearch($q = '')
    {
        $books = [];
        
        if ($q) {
            $books = Book::find()
                ->with(['author'])
                ->where(['status' => Book::STATUS_ACTIVE])
                ->andWhere(['or', 
                    ['like', 'title', $q],
                    ['like', 'description', $q],
                    ['like', 'genre', $q]
                ])
                ->orderBy(['title' => SORT_ASC])
                ->all();
        }

        return $this->render('search', [
            'books' => $books,
            'query' => $q,
        ]);
    }

    /**
     * Read a book chapter
     */
    public function actionRead($bookId, $chapterId = null)
    {
        $book = $this->findBook($bookId);
        
        if (!$chapterId) {
            $firstChapter = $book->chapters[0] ?? null;
            if ($firstChapter) {
                return $this->redirect(['read', 'bookId' => $bookId, 'chapterId' => $firstChapter->id]);
            }
        }

        $chapter = $this->findChapter($chapterId);
        
        // Check if user can read this content
        $canReadFull = true;
        $previewContent = null;
        
        if (!Yii::$app->user->isGuest) {
            $totalPagesRead = UserReadingProgress::getTotalPagesRead(Yii::$app->user->id, $bookId);
            $canReadFull = $totalPagesRead < 10;
            
            if (!$canReadFull) {
                $previewContent = $chapter->getPreviewContent(10 - $totalPagesRead);
            }
        } else {
            // Guest users can only see preview
            $canReadFull = false;
            $previewContent = $chapter->getPreviewContent(10);
        }

        // Record reading progress if user is logged in
        if (!Yii::$app->user->isGuest && $canReadFull) {
            UserReadingProgress::recordProgress(
                Yii::$app->user->id,
                $bookId,
                $chapterId,
                $chapter->page_count
            );
        }

        return $this->render('read', [
            'book' => $book,
            'chapter' => $chapter,
            'canReadFull' => $canReadFull,
            'previewContent' => $previewContent,
        ]);
    }

    /**
     * Import book from external API
     */
    public function actionImport()
    {
        $request = Yii::$app->request;
        $query = $request->post('query');
        $isbn = $request->post('isbn');
        
        if (!$query && !$isbn) {
            Yii::$app->session->setFlash('error', 'Please provide a search query or ISBN.');
            return $this->redirect(['search']);
        }

        $apiService = new BookApiService();
        
        try {
            if ($isbn) {
                $bookData = $apiService->getBookByIsbn($isbn);
            } else {
                $books = $apiService->searchBooks($query, 1);
                $bookData = !empty($books) ? $books[0] : null;
            }
            
            if ($bookData) {
                $authorId = Yii::$app->user->identity->isAuthor() 
                    ? Yii::$app->user->id 
                    : 1; // Default admin user ID
                
                $book = $apiService->importBook($bookData, $authorId);
                
                if ($book) {
                    Yii::$app->session->setFlash('success', 'Book imported successfully!');
                    return $this->redirect(['view', 'id' => $book->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to import book.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No books found for the given criteria.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error importing book: ' . $e->getMessage());
        }
        
        return $this->redirect(['search']);
    }

    /**
     * Find book by ID
     */
    protected function findBook($id)
    {
        $book = Book::find()
            ->with(['author', 'chapters'])
            ->where(['id' => $id, 'status' => Book::STATUS_ACTIVE])
            ->one();
            
        if (!$book) {
            throw new NotFoundHttpException('The requested book does not exist.');
        }
        
        return $book;
    }

    /**
     * Find chapter by ID
     */
    protected function findChapter($id)
    {
        $chapter = BookChapter::find()
            ->with(['book'])
            ->where(['id' => $id])
            ->one();
            
        if (!$chapter) {
            throw new NotFoundHttpException('The requested chapter does not exist.');
        }
        
        return $chapter;
    }
}
