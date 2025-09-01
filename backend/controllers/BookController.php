<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use common\models\Book;
use common\models\BookChapter;
use common\models\User;
use common\services\BookApiService;

/**
 * Book controller for backend admin panel
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
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin() || Yii::$app->user->identity->isAuthor();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'delete-chapter' => ['POST'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
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
            ->with(['author', 'chapters']);

        // Authors can only see their own books
        if (Yii::$app->user->identity->isAuthor()) {
            $query->andWhere(['author_id' => Yii::$app->user->id]);
        }

        $books = $query->orderBy(['created_at' => SORT_DESC])->all();

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
        
        // Check if user can view this book
        if (!$book->canEdit(Yii::$app->user->identity)) {
            throw new NotFoundHttpException('You do not have permission to view this book.');
        }

        return $this->render('view', [
            'book' => $book,
        ]);
    }

    /**
     * Creates a new book
     */
    public function actionCreate()
    {
        $book = new Book();
        $book->author_id = Yii::$app->user->identity->isAuthor() 
            ? Yii::$app->user->id 
            : null;

        if ($book->load(Yii::$app->request->post()) && $book->save()) {
            Yii::$app->session->setFlash('success', 'Book created successfully.');
            return $this->redirect(['view', 'id' => $book->id]);
        }

        return $this->render('create', [
            'book' => $book,
            'authors' => $this->getAuthorsList(),
        ]);
    }

    /**
     * Updates an existing book
     */
    public function actionUpdate($id)
    {
        $book = $this->findBook($id);
        
        // Check if user can edit this book
        if (!$book->canEdit(Yii::$app->user->identity)) {
            throw new NotFoundHttpException('You do not have permission to edit this book.');
        }

        if ($book->load(Yii::$app->request->post()) && $book->save()) {
            Yii::$app->session->setFlash('success', 'Book updated successfully.');
            return $this->redirect(['view', 'id' => $book->id]);
        }

        return $this->render('update', [
            'book' => $book,
            'authors' => $this->getAuthorsList(),
        ]);
    }

    /**
     * Deletes a book
     */
    public function actionDelete($id)
    {
        $book = $this->findBook($id);
        
        // Check if user can delete this book
        if (!$book->canDelete(Yii::$app->user->identity)) {
            throw new NotFoundHttpException('You do not have permission to delete this book.');
        }

        if ($book->delete()) {
            Yii::$app->session->setFlash('success', 'Book deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete book.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Creates a new chapter
     */
    public function actionCreateChapter($bookId)
    {
        $book = $this->findBook($bookId);
        
        // Check if user can edit this book
        if (!$book->canEdit(Yii::$app->user->identity)) {
            throw new NotFoundHttpException('You do not have permission to add chapters to this book.');
        }

        $chapter = new BookChapter();
        $chapter->book_id = $bookId;

        if ($chapter->load(Yii::$app->request->post()) && $chapter->save()) {
            Yii::$app->session->setFlash('success', 'Chapter created successfully.');
            return $this->redirect(['view', 'id' => $bookId]);
        }

        return $this->render('create-chapter', [
            'chapter' => $chapter,
            'book' => $book,
        ]);
    }

    /**
     * Updates a chapter
     */
    public function actionUpdateChapter($id)
    {
        $chapter = $this->findChapter($id);
        $book = $chapter->book;
        
        // Check if user can edit this book
        if (!$book->canEdit(Yii::$app->user->identity)) {
            throw new NotFoundHttpException('You do not have permission to edit this chapter.');
        }

        if ($chapter->load(Yii::$app->request->post()) && $chapter->save()) {
            Yii::$app->session->setFlash('success', 'Chapter updated successfully.');
            return $this->redirect(['view', 'id' => $book->id]);
        }

        return $this->render('update-chapter', [
            'chapter' => $chapter,
            'book' => $book,
        ]);
    }

    /**
     * Deletes a chapter
     */
    public function actionDeleteChapter($id)
    {
        $chapter = $this->findChapter($id);
        $book = $chapter->book;
        
        // Check if user can delete this book
        if (!$book->canDelete(Yii::$app->user->identity)) {
            throw new NotFoundHttpException('You do not have permission to delete this chapter.');
        }

        if ($chapter->delete()) {
            Yii::$app->session->setFlash('success', 'Chapter deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete chapter.');
        }

        return $this->redirect(['view', 'id' => $book->id]);
    }

    /**
     * Search books via Google Books API
     */
    public function actionSearchApi()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $request = Yii::$app->request;
        $query = $request->post('query');
        
        if (!$query) {
            return [
                'success' => false,
                'message' => 'Search query is required'
            ];
        }
        
        try {
            $apiService = new BookApiService();
            $books = $apiService->searchBooks($query, 10); // Get up to 10 results
            
            return [
                'success' => true,
                'books' => $books
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error searching books: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Import book from external API
     */
    public function actionImport()
    {
        $request = Yii::$app->request;
        
        // If it's a GET request, render the import form
        if ($request->isGet) {
            return $this->render('import');
        }
        
        // Handle AJAX requests
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $isbn = $request->post('isbn');
            $bookData = $request->post('bookData');
            
            try {
                $apiService = new BookApiService();
                
                if ($isbn) {
                    $bookData = $apiService->getBookByIsbn($isbn);
                } elseif ($bookData) {
                    $bookData = json_decode($bookData, true);
                }
                
                if ($bookData) {
                    $authorId = Yii::$app->user->identity->isAuthor() 
                        ? Yii::$app->user->id 
                        : 1; // Default admin user ID
                    
                    $book = $apiService->importBook($bookData, $authorId);
                    
                    if ($book) {
                        return [
                            'success' => true,
                            'message' => 'Book imported successfully!',
                            'bookId' => $book->id
                        ];
                    } else {
                        return [
                            'success' => false,
                            'message' => 'Failed to import book'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'message' => 'No book data provided'
                    ];
                }
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Error importing book: ' . $e->getMessage()
                ];
            }
        }
        
        // Handle regular form submissions (fallback)
        $query = $request->post('query');
        $isbn = $request->post('isbn');
        
        if (!$query && !$isbn) {
            Yii::$app->session->setFlash('error', 'Please provide a search query or ISBN.');
            return $this->redirect(['index']);
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
        
        return $this->redirect(['index']);
    }

    /**
     * Get authors list for dropdown
     */
    protected function getAuthorsList()
    {
        if (Yii::$app->user->identity->isAdmin()) {
            return User::find()
                ->where(['role' => [User::ROLE_AUTHOR, User::ROLE_ADMIN]])
                ->select(['id', 'username'])
                ->indexBy('id')
                ->column();
        }
        
        return [Yii::$app->user->id => Yii::$app->user->identity->username];
    }

    /**
     * Find book by ID
     */
    protected function findBook($id)
    {
        $book = Book::find()
            ->with(['author', 'chapters'])
            ->where(['id' => $id])
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
