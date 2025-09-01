<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $book common\models\Book */
/* @var $chapter common\models\BookChapter */
/* @var $canReadFull bool */
/* @var $previewContent string */

$this->title = $book->title . ' - Chapter ' . $chapter->chapter_number;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = ['label' => $book->title, 'url' => ['book/view', 'id' => $book->id]];
$this->params['breadcrumbs'][] = 'Chapter ' . $chapter->chapter_number;
?>

<div class="book-read">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Chapter Header -->
                <div class="chapter-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="chapter-title"><?= Html::encode($chapter->title) ?></h1>
                            <p class="text-muted">
                                <strong><?= Html::encode($book->title) ?></strong> - 
                                Chapter <?= $chapter->chapter_number ?> 
                                (<?= $chapter->page_count ?> pages)
                            </p>
                        </div>
                        <div class="chapter-navigation">
                            <?php if ($chapter->chapter_number > 1): ?>
                                <a href="<?= Url::to(['book/read', 'bookId' => $book->id, 'chapterId' => $chapter->id - 1]) ?>" 
                                   class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($chapter->chapter_number < count($book->chapters)): ?>
                                <a href="<?= Url::to(['book/read', 'bookId' => $book->id, 'chapterId' => $chapter->id + 1]) ?>" 
                                   class="btn btn-outline-secondary">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reading Progress Bar -->
                <?php if (!Yii::$app->user->isGuest): ?>
                    <div class="reading-progress mb-4">
                        <div class="progress-info d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Reading Progress</span>
                            <?php
                            $totalPagesRead = \common\models\UserReadingProgress::getTotalPagesRead(Yii::$app->user->id, $book->id);
                            $progressPercentage = min(100, ($totalPagesRead / 10) * 100);
                            ?>
                            <span class="text-muted"><?= $totalPagesRead ?>/10 pages</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?= $progressPercentage ?>%" 
                                 aria-valuenow="<?= $totalPagesRead ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="10">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Chapter Content -->
                <div class="chapter-content">
                    <?php if ($canReadFull): ?>
                        <!-- Full Content -->
                        <div class="content-full">
                            <?= nl2br(Html::encode($chapter->content)) ?>
                        </div>
                        
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <div class="reading-complete mt-4 text-center">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> 
                                    You've completed reading this chapter!
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Preview Content -->
                        <div class="content-preview">
                            <div class="alert alert-info mb-4">
                                <h5><i class="fas fa-info-circle"></i> Preview Mode</h5>
                                <p>You are currently viewing a preview of this chapter. 
                                <?php if (Yii::$app->user->isGuest): ?>
                                    Sign up or log in to read the full content.
                                <?php else: ?>
                                    You have reached the 10-page free preview limit. 
                                    Upgrade your account to continue reading.
                                <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="preview-content">
                                <?= nl2br(Html::encode($previewContent)) ?>
                            </div>
                            
                            <div class="preview-limit-warning mt-4 p-4 bg-light border rounded">
                                <h5 class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Preview Limit Reached
                                </h5>
                                <p class="mb-3">
                                    This is the end of your free preview. To continue reading this book and access all content, 
                                    please sign up for an account.
                                </p>
                                
                                <?php if (Yii::$app->user->isGuest): ?>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="<?= Url::to(['site/signup']) ?>" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Sign Up Now
                                        </a>
                                        <a href="<?= Url::to(['site/login']) ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-sign-in-alt"></i> Log In
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <a href="<?= Url::to(['site/upgrade']) ?>" class="btn btn-primary">
                                            <i class="fas fa-crown"></i> Upgrade Account
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Navigation Footer -->
                <div class="chapter-footer mt-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= Url::to(['book/view', 'id' => $book->id]) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Book
                        </a>
                        
                        <div class="chapter-actions">
                            <?php if ($chapter->chapter_number > 1): ?>
                                <a href="<?= Url::to(['book/read', 'bookId' => $book->id, 'chapterId' => $chapter->id - 1]) ?>" 
                                   class="btn btn-outline-primary me-2">
                                    <i class="fas fa-chevron-left"></i> Previous Chapter
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($chapter->chapter_number < count($book->chapters)): ?>
                                <a href="<?= Url::to(['book/read', 'bookId' => $book->id, 'chapterId' => $chapter->id + 1]) ?>" 
                                   class="btn btn-primary">
                                    Next Chapter <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chapter-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
}

.chapter-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.reading-progress .progress {
    height: 8px;
    border-radius: 4px;
}

.reading-progress .progress-bar {
    background-color: #28a745;
    border-radius: 4px;
}

.chapter-content {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    line-height: 1.8;
    font-size: 1.1rem;
}

.content-preview .alert {
    border-radius: 8px;
    border-left: 4px solid #17a2b8;
}

.preview-limit-warning {
    border-color: #ffc107 !important;
}

.preview-limit-warning h5 {
    color: #856404;
}

.chapter-footer {
    border-top: 2px solid #e9ecef;
    padding-top: 1rem;
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

.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}

.chapter-navigation .btn {
    min-width: 100px;
}

@media (max-width: 768px) {
    .chapter-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .chapter-navigation {
        margin-top: 1rem;
    }
    
    .chapter-content {
        padding: 1rem;
        font-size: 1rem;
    }
    
    .chapter-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .chapter-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
