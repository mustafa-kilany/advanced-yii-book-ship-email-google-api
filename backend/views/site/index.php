<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Bookshop Admin Dashboard';
?>
<div class="site-index">
    <!-- Welcome Section -->
    <div class="jumbotron text-center bg-primary text-white mb-4">
        <h1 class="display-4">
            <i class="fas fa-book-open me-3"></i>
            Bookshop Admin Dashboard
        </h1>
        <p class="lead">Welcome to your bookshop administration panel. Manage books, users, and system settings.</p>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <i class="fas fa-books fa-2x mb-2"></i>
                    <h5 class="card-title">Total Books</h5>
                    <h3 class="card-text">0</h3>
                    <small>Books in system</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h5 class="card-title">Total Users</h5>
                    <h3 class="card-text">1</h3>
                    <small>Registered users</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <i class="fas fa-user-clock fa-2x mb-2"></i>
                    <h5 class="card-title">Pending</h5>
                    <h3 class="card-text">0</h3>
                    <small>Pending verifications</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h5 class="card-title">Today</h5>
                    <h3 class="card-text">0</h3>
                    <small>New registrations</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= Url::to(['/book/create']) ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Book
                        </a>
                        <a href="<?= Url::to(['/book/index']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Manage Books
                        </a>
                        <a href="<?= Url::to(['/user/index']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-download me-2"></i>
                        Import Books
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Import books from Google Books API to quickly populate your bookshop.</p>
                    <div class="d-grid gap-2">
                        <a href="<?= Url::to(['/book/import']) ?>" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Import from Google Books
                        </a>
                        <a href="<?= Url::to(['/book/index']) ?>" class="btn btn-outline-success">
                            <i class="fas fa-search me-2"></i>Search & Import
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        System Status
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Database: Connected
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Email System: Configured (Mailtrap)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Google Books API: Configured
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Books: 0 available
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Getting Started
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Import books from Google Books API</li>
                        <li class="mb-2">Create book chapters and content</li>
                        <li class="mb-2">Invite authors to contribute</li>
                        <li class="mb-2">Monitor user activity and reading progress</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.jumbotron {
    border-radius: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.btn {
    border-radius: 0.5rem;
}

.fas {
    width: 20px;
}
</style>
