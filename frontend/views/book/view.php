<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $book common\models\Book */
/* @var $chapters common\models\BookChapter[] */
/* @var $previewLimitReached bool */

$this->title = $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-view">
    <div class="container">
        <div class="row">
            <!-- Book Cover and Basic Info -->
            <div class="col-md-4">
                <div class="book-cover-container text-center mb-4">
                    <?php if ($book->cover_image): ?>
                        <img src="<?= Html::encode($book->cover_image) ?>" 
                             class="img-fluid book-cover-large" 
                             alt="<?= Html::encode($book->title) ?>">
                    <?php else: ?>
                        <div class="book-cover-placeholder-large d-flex align-items-center justify-content-center">
                            <i class="fas fa-book fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Book Details -->
            <div class="col-md-8">
                <h1 class="book-title mb-3"><?= Html::encode($book->title) ?></h1>
                
                <div class="book-meta mb-4">
                    <p class="text-muted mb-2">
                        <strong>Author:</strong> <?= Html::encode($book->author->username ?? 'Unknown Author') ?>
                    </p>
                    
                    <?php if ($book->isbn): ?>
                        <p class="text-muted mb-2">
                            <strong>ISBN:</strong> <?= Html::encode($book->isbn) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($book->genre): ?>
                        <p class="text-muted mb-2">
                            <strong>Genre:</strong> 
                            <span class="badge bg-secondary"><?= Html::encode($book->genre) ?></span>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($book->page_count): ?>
                        <p class="text-muted mb-2">
                            <strong>Pages:</strong> <?= $book->page_count ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($book->published_date): ?>
                        <p class="text-muted mb-2">
                            <strong>Published:</strong> <?= Html::encode($book->published_date) ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <?php if ($book->description): ?>
                    <div class="book-description mb-4">
                        <h4>Description</h4>
                        <p><?= nl2br(Html::encode($book->description)) ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Reading Progress Warning -->
                <?php if ($previewLimitReached): ?>
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Preview Limit Reached</h5>
                        <p>You have reached the 10-page free preview limit for this book. 
                        Please sign up or log in to continue reading.</p>
                        <a href="<?= Url::to(['site/signup']) ?>" class="btn btn-primary">Sign Up</a>
                        <a href="<?= Url::to(['site/login']) ?>" class="btn btn-outline-primary">Log In</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Chapters Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Chapters</h3>
                
                <?php if (empty($chapters)): ?>
                    <div class="alert alert-info">
                        <p>No chapters available for this book yet.</p>
                    </div>
                <?php else: ?>
                    <div class="chapters-list">
                        <?php foreach ($chapters as $index => $chapter): ?>
                            <div class="chapter-item card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="chapter-title mb-2">
                                                Chapter <?= $chapter->chapter_number ?>: <?= Html::encode($chapter->title) ?>
                                            </h5>
                                            <p class="text-muted mb-0">
                                                <?= $chapter->page_count ?> pages
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a href="<?= Url::to(['book/read', 'bookId' => $book->id, 'chapterId' => $chapter->id]) ?>" 
                                               class="btn btn-primary">
                                                <i class="fas fa-book-open"></i> Read
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?= Url::to(['book/index']) ?>" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Books
                </a>
                
                <?php if (!Yii::$app->user->isGuest && $book->canEdit(Yii::$app->user->identity)): ?>
                    <a href="<?= Url::to(['backend/book/update', 'id' => $book->id]) ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit Book
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.book-cover-large {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.book-cover-placeholder-large {
    width: 300px;
    height: 400px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

.book-title {
    color: #2c3e50;
    font-weight: 700;
}

.book-meta p {
    margin-bottom: 0.5rem;
}

.book-description {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.chapter-item {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #dee2e6;
}

.chapter-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.chapter-title {
    color: #2c3e50;
    font-weight: 600;
}

.alert {
    border-radius: 8px;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
</style>
