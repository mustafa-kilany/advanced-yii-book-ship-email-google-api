<?php

/** @var yii\web\View $this */
/** @var string $query */
/** @var string $isbn */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Import Book from Google Books API';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-import">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-download me-2"></i>
                        Import Book from Google Books API
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Search for books by title, author, or keywords, or import directly by ISBN. 
                        Books will be imported with cover images, descriptions, and basic metadata.
                    </p>

                    <!-- Search by Query -->
                    <div class="mb-4">
                        <h5><i class="fas fa-search me-2"></i>Search by Title, Author, or Keywords</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" id="search-query" class="form-control" 
                                       placeholder="e.g., Harry Potter, J.K. Rowling, fantasy novels...">
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="search-btn" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Import by ISBN -->
                    <div class="mb-4">
                        <h5><i class="fas fa-barcode me-2"></i>Import by ISBN</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" id="isbn-input" class="form-control" 
                                       placeholder="e.g., 9780747532699">
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="import-isbn-btn" class="btn btn-success w-100">
                                    <i class="fas fa-download me-2"></i>Import by ISBN
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div id="search-results" class="mt-4" style="display: none;">
                        <h5><i class="fas fa-list me-2"></i>Search Results</h5>
                        <div id="results-container"></div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="loading" class="text-center mt-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Searching Google Books API...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        How It Works
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Search for books using keywords or titles</li>
                        <li class="mb-2">Browse search results from Google Books</li>
                        <li class="mb-2">Click "Import" on any book you want</li>
                        <li class="mb-2">Book will be added to your collection</li>
                        <li class="mb-2">You can then add chapters and content</li>
                    </ol>
                    
                    <hr>
                    
                    <h6 class="text-muted">Tips:</h6>
                    <ul class="small text-muted">
                        <li>Use specific titles for better results</li>
                        <li>Include author names when possible</li>
                        <li>ISBN provides the most accurate results</li>
                        <li>Check book details before importing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.book-result {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease-in-out;
}

.book-result:hover {
    border-color: #007bff;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 123, 255, 0.25);
}

.book-cover {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 0.25rem;
    border: 1px solid #dee2e6;
}

.book-cover-placeholder {
    width: 80px;
    height: 120px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 0.875rem;
}

.btn {
    border-radius: 0.375rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

<script>
$(document).ready(function() {
    // Search functionality
    $('#search-btn').click(function() {
        var query = $('#search-query').val().trim();
        if (!query) {
            alert('Please enter a search query');
            return;
        }
        searchBooks(query);
    });

    // Enter key in search input
    $('#search-query').keypress(function(e) {
        if (e.which == 13) {
            $('#search-btn').click();
        }
    });

    // Import by ISBN
    $('#import-isbn-btn').click(function() {
        var isbn = $('#isbn-input').val().trim();
        if (!isbn) {
            alert('Please enter an ISBN');
            return;
        }
        importByIsbn(isbn);
    });

    // Enter key in ISBN input
    $('#isbn-input').keypress(function(e) {
        if (e.which == 13) {
            $('#import-isbn-btn').click();
        }
    });

    function searchBooks(query) {
        $('#loading').show();
        $('#search-results').hide();
        
        $.ajax({
            url: '<?= Url::to(['book/search-api']) ?>',
            type: 'POST',
            data: {
                query: query,
                _csrf: '<?= Yii::$app->request->csrfToken ?>'
            },
            success: function(response) {
                $('#loading').hide();
                if (response.success && response.books) {
                    displaySearchResults(response.books);
                } else {
                    alert('No books found for: ' + query);
                }
            },
            error: function() {
                $('#loading').hide();
                alert('Error searching for books. Please try again.');
            }
        });
    }

    function importByIsbn(isbn) {
        $('#loading').show();
        
        $.ajax({
            url: '<?= Url::to(['book/import']) ?>',
            type: 'POST',
            data: {
                isbn: isbn,
                _csrf: '<?= Yii::$app->request->csrfToken ?>'
            },
            success: function(response) {
                $('#loading').hide();
                if (response.success) {
                    alert('Book imported successfully!');
                    window.location.href = '<?= Url::to(['book/index']) ?>';
                } else {
                    alert('Error importing book: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                $('#loading').hide();
                alert('Error importing book. Please try again.');
            }
        });
    }

    function displaySearchResults(books) {
        var container = $('#results-container');
        container.empty();
        
        books.forEach(function(book) {
            var coverHtml = book.imageLinks && book.imageLinks.thumbnail 
                ? '<img src="' + book.imageLinks.thumbnail + '" class="book-cover" alt="Book cover">'
                : '<div class="book-cover-placeholder">No Cover</div>';
            
            var authors = book.authors ? book.authors.join(', ') : 'Unknown Author';
            var description = book.description ? book.description.substring(0, 150) + '...' : 'No description available';
            
            var bookHtml = `
                <div class="book-result">
                    <div class="row">
                        <div class="col-md-2">
                            ${coverHtml}
                        </div>
                        <div class="col-md-8">
                            <h5>${book.title}</h5>
                            <p class="text-muted mb-2">by ${authors}</p>
                            <p class="small">${description}</p>
                            ${book.publishedDate ? '<p class="text-muted small">Published: ' + book.publishedDate + '</p>' : ''}
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button class="btn btn-success btn-sm import-book-btn" 
                                    data-book='${JSON.stringify(book)}'>
                                <i class="fas fa-download me-1"></i>Import
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(bookHtml);
        });
        
        $('#search-results').show();
        
        // Bind import buttons
        $('.import-book-btn').click(function() {
            var bookData = $(this).data('book');
            importBook(bookData);
        });
    }

    function importBook(bookData) {
        $('#loading').show();
        
        $.ajax({
            url: '<?= Url::to(['book/import']) ?>',
            type: 'POST',
            data: {
                bookData: JSON.stringify(bookData),
                _csrf: '<?= Yii::$app->request->csrfToken ?>'
            },
            success: function(response) {
                $('#loading').hide();
                if (response.success) {
                    alert('Book imported successfully!');
                    window.location.href = '<?= Url::to(['book/index']) ?>';
                } else {
                    alert('Error importing book: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                $('#loading').hide();
                alert('Error importing book. Please try again.');
            }
        });
    }
});
</script>
