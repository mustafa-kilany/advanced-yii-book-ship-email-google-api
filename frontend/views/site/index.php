<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Welcome to Advanced Bookshop';
?>

<div class="site-index">
    <!-- Hero Section -->
    <div class="hero-section text-center py-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-3 fw-bold text-primary mb-4">
                        <i class="fas fa-book-open me-3"></i>
                        Advanced Bookshop
                    </h1>
                    <p class="lead fs-4 text-muted mb-4">
                        Discover thousands of books, read previews for free, and dive into your favorite stories. 
                        Join our community of readers and authors today!
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="<?= Url::to(['/book/index']) ?>" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Browse Books
                        </a>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a href="<?= Url::to(['/site/signup']) ?>" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-user-plus me-2"></i>Sign Up Free
                            </a>
                        <?php else: ?>
                            <a href="<?= Url::to(['/book/index']) ?>" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-book me-2"></i>My Library
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-book-reader fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5 mb-3">Free Preview</h3>
                    <p class="text-muted">
                        Read the first 10 pages of any book for free. No signup required to start exploring!
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h3 class="h5 mb-3">Community Driven</h3>
                    <p class="text-muted">
                        Join as a reader, author, or admin. Create content, share stories, and build your audience.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-info"></i>
                    </div>
                    <h3 class="h5 mb-3">Read Anywhere</h3>
                    <p class="text-muted">
                        Responsive design works perfectly on desktop, tablet, and mobile devices.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="how-it-works py-5 mb-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="step-card text-center">
                        <div class="step-number mb-3">1</div>
                        <h4 class="h6 mb-3">Browse Books</h4>
                        <p class="text-muted small">Explore our collection of books from various genres and authors</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card text-center">
                        <div class="step-number mb-3">2</div>
                        <h4 class="h6 mb-3">Read Preview</h4>
                        <p class="text-muted small">Read the first 10 pages for free to see if you like the book</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card text-center">
                        <div class="step-number mb-3">3</div>
                        <h4 class="h6 mb-3">Sign Up</h4>
                        <p class="text-muted small">Create a free account to access the full book content</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card text-center">
                        <div class="step-number mb-3">4</div>
                        <h4 class="h6 mb-3">Enjoy Reading</h4>
                        <p class="text-muted small">Read complete books, track progress, and discover new stories</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="cta-section p-5 bg-primary text-white rounded-3">
                    <h2 class="mb-4">Ready to Start Reading?</h2>
                    <p class="lead mb-4">
                        Join thousands of readers who are already enjoying our book collection. 
                        Start your reading journey today!
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="<?= Url::to(['/book/index']) ?>" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-book me-2"></i>Start Reading
                        </a>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a href="<?= Url::to(['/site/signup']) ?>" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-user-plus me-2"></i>Join Now
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="container mb-5">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number text-primary fw-bold fs-1">1000+</div>
                    <div class="stat-label text-muted">Books Available</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number text-success fw-bold fs-1">500+</div>
                    <div class="stat-label text-muted">Active Readers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number text-info fw-bold fs-1">50+</div>
                    <div class="stat-label text-muted">Authors</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number text-warning fw-bold fs-1">24/7</div>
                    <div class="stat-label text-muted">Available</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0 0 2rem 2rem;
    margin-top: -1rem;
}

.hero-section h1 {
    color: white !important;
}

.feature-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.feature-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-card {
    padding: 1rem;
}

.step-number {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0 auto;
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.stat-card {
    padding: 1rem;
}

.stat-number {
    display: block;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .hero-section .lead {
        font-size: 1.1rem;
    }
    
    .d-flex.justify-content-center {
        flex-direction: column;
        align-items: center;
    }
    
    .d-flex.justify-content-center .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
