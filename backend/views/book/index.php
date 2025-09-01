<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $books common\models\Book[] */

$this->title = 'Manage Books';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                        <div class="card-tools">
                            <a href="<?= Url::to(['book/create']) ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Book
                            </a>
                            <a href="<?= Url::to(['book/import']) ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Import from API
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if (empty($books)): ?>
                        <div class="alert alert-info">
                            <p>No books available. Create your first book or import from the API.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Genre</th>
                                        <th>Pages</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books as $book): ?>
                                        <tr>
                                            <td>
                                                <?php if ($book->cover_image): ?>
                                                    <img src="<?= Html::encode($book->cover_image) ?>" 
                                                         alt="Cover" class="book-cover-thumb">
                                                <?php else: ?>
                                                    <div class="book-cover-placeholder">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= Html::encode($book->title) ?></strong>
                                                <?php if ($book->isbn): ?>
                                                    <br><small class="text-muted">ISBN: <?= Html::encode($book->isbn) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= Html::encode($book->author->username ?? 'Unknown') ?>
                                            </td>
                                            <td>
                                                <?php if ($book->genre): ?>
                                                    <span class="badge bg-secondary"><?= Html::encode($book->genre) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $book->page_count ?: '-' ?>
                                            </td>
                                            <td>
                                                <?php if ($book->status == $book::STATUS_ACTIVE): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= Yii::$app->formatter->asDate($book->created_at) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= Url::to(['book/view', 'id' => $book->id]) ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($book->canEdit(Yii::$app->user->identity)): ?>
                                                        <a href="<?= Url::to(['book/update', 'id' => $book->id]) ?>" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <a href="<?= Url::to(['book/create-chapter', 'bookId' => $book->id]) ?>" 
                                                           class="btn btn-sm btn-outline-info" title="Add Chapter">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($book->canDelete(Yii::$app->user->identity)): ?>
                                                        <?= Html::a('<i class="fas fa-trash"></i>', 
                                                            ['book/delete', 'id' => $book->id], 
                                                            [
                                                                'class' => 'btn btn-sm btn-outline-danger',
                                                                'title' => 'Delete',
                                                                'data' => [
                                                                    'confirm' => 'Are you sure you want to delete this book?',
                                                                    'method' => 'post',
                                                                ],
                                                            ]
                                                        ) ?>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.book-cover-thumb {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

.book-cover-placeholder {
    width: 50px;
    height: 70px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
}

.card-tools .btn {
    margin-left: 0.5rem;
}

@media (max-width: 768px) {
    .card-tools {
        margin-top: 1rem;
    }
    
    .card-tools .btn {
        margin-left: 0;
        margin-bottom: 0.5rem;
        width: 100%;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
