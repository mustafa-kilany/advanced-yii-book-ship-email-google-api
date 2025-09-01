<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $books common\models\Book[] */

$this->title = 'Bookshop - Browse Books';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-index">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
                
                <!-- Search Bar -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form action="<?= Url::to(['book/search']) ?>" method="get" class="d-flex">
                            <input type="text" name="q" class="form-control me-2" placeholder="Search books by title, author, or genre...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAuthor() || Yii::$app->user->identity->isAdmin())): ?>
                            <a href="<?= Url::to(['book/import']) ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Import Book
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (empty($books)): ?>
                    <div class="alert alert-info">
                        <p>No books available at the moment. Check back later!</p>
                    </div>
                <?php else: ?>
                    <!-- Books Grid -->
                    <div class="row">
                        <?php foreach ($books as $book): ?>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card h-100 book-card">
                                    <?php if ($book->cover_image): ?>
                                        <img src="<?= Html::encode($book->cover_image) ?>" 
                                             class="card-img-top book-cover" 
                                             alt="<?= Html::encode($book->title) ?>">
                                    <?php else: ?>
                                        <div class="card-img-top book-cover-placeholder d-flex align-items-center justify-content-center">
                                            <i class="fas fa-book fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= Html::encode($book->title) ?></h5>
                                        <p class="card-text text-muted">
                                            By <strong><?= Html::encode($book->author->username ?? 'Unknown Author') ?></strong>
                                        </p>
                                        
                                        <?php if ($book->description): ?>
                                            <p class="card-text">
                                                <?= Html::encode(substr($book->description, 0, 100)) ?>...
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="mt-auto">
                                            <div class="book-meta mb-2">
                                                <?php if ($book->genre): ?>
                                                    <span class="badge bg-secondary me-1"><?= Html::encode($book->genre) ?></span>
                                                <?php endif; ?>
                                                <?php if ($book->page_count): ?>
                                                    <small class="text-muted"><?= $book->page_count ?> pages</small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <a href="<?= Url::to(['book/view', 'id' => $book->id]) ?>" 
                                               class="btn btn-primary btn-sm w-100">
                                                View Book
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
    </div>
</div>

<style>
.book-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #dee2e6;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.book-cover {
    height: 200px;
    object-fit: cover;
}

.book-cover-placeholder {
    height: 200px;
    background-color: #f8f9fa;
}

.book-meta {
    font-size: 0.875rem;
}

.card-body {
    padding: 1rem;
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
