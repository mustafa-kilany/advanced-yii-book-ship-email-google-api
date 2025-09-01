<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use common\services\BookApiService;

/**
 * Console controller to automatically import books from Google Books API
 */
class ImportBooksController extends Controller
{
    /**
     * Default books to import
     */
    private $defaultBooks = [
        'Harry Potter',
        'The Lord of the Rings',
        '1984',
        'Pride and Prejudice',
        'To Kill a Mockingbird',
        'The Great Gatsby',
        'Animal Farm',
        'Brave New World',
        'The Catcher in the Rye',
        'Fahrenheit 451'
    ];

    /**
     * Import books automatically
     */
    public function actionIndex()
    {
        $this->stdout("🚀 Starting automatic book import...\n", Console::FG_GREEN);
        
        try {
            $apiService = new BookApiService();
            $importedCount = 0;
            $errors = [];
            
            foreach ($this->defaultBooks as $bookTitle) {
                $this->stdout("📚 Searching for: {$bookTitle}...\n", Console::FG_YELLOW);
                
                try {
                    // Search for the book
                    $books = $apiService->searchBooks($bookTitle, 1);
                    
                    if (!empty($books)) {
                        $bookData = $books[0]; // Get the first result
                        
                        // Import the book (assign to admin user ID 1)
                        $book = $apiService->importBook($bookData, 1);
                        
                        if ($book) {
                            $this->stdout("✅ Successfully imported: {$bookData['title']}\n", Console::FG_GREEN);
                            $importedCount++;
                        } else {
                            $this->stdout("❌ Failed to import: {$bookTitle}\n", Console::FG_RED);
                            $errors[] = "Failed to import: {$bookTitle}";
                        }
                    } else {
                        $this->stdout("⚠️  No results found for: {$bookTitle}\n", Console::FG_YELLOW);
                    }
                    
                    // Small delay to avoid overwhelming the API
                    sleep(1);
                    
                } catch (\Exception $e) {
                    $this->stdout("❌ Error importing {$bookTitle}: " . $e->getMessage() . "\n", Console::FG_RED);
                    $errors[] = "Error importing {$bookTitle}: " . $e->getMessage();
                }
            }
            
            // Summary
            $this->stdout("\n" . str_repeat("=", 50) . "\n", Console::FG_CYAN);
            $this->stdout("📊 IMPORT SUMMARY:\n", Console::FG_CYAN);
            $this->stdout("✅ Successfully imported: {$importedCount} books\n", Console::FG_GREEN);
            $this->stdout("❌ Errors: " . count($errors) . "\n", Console::FG_RED);
            
            if (!empty($errors)) {
                $this->stdout("\n❌ Error Details:\n", Console::FG_RED);
                foreach ($errors as $error) {
                    $this->stdout("   - {$error}\n", Console::FG_RED);
                }
            }
            
            $this->stdout("\n🎉 Import process completed!\n", Console::FG_GREEN);
            $this->stdout("You can now view your books at: http://localhost/auth_yii/bookshop/backend/web/index.php?r=book/index\n", Console::FG_CYAN);
            
            return ExitCode::OK;
            
        } catch (\Exception $e) {
            $this->stdout("❌ Fatal error: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
    
    /**
     * Import a specific book by title
     */
    public function actionBook($title)
    {
        if (empty($title)) {
            $this->stdout("❌ Please provide a book title\n", Console::FG_RED);
            $this->stdout("Usage: yii import-books/book \"Book Title\"\n", Console::FG_YELLOW);
            return ExitCode::USAGE;
        }
        
        $this->stdout("📚 Importing specific book: {$title}\n", Console::FG_YELLOW);
        
        try {
            $apiService = new BookApiService();
            
            // Search for the book
            $books = $apiService->searchBooks($title, 1);
            
            if (!empty($books)) {
                $bookData = $books[0];
                
                // Import the book
                $book = $apiService->importBook($bookData, 1);
                
                if ($book) {
                    $this->stdout("✅ Successfully imported: {$bookData['title']}\n", Console::FG_GREEN);
                    $this->stdout("📖 Book ID: {$book->id}\n", Console::FG_CYAN);
                } else {
                    $this->stdout("❌ Failed to import: {$title}\n", Console::FG_RED);
                }
            } else {
                $this->stdout("❌ No results found for: {$title}\n", Console::FG_RED);
            }
            
            return ExitCode::OK;
            
        } catch (\Exception $e) {
            $this->stdout("❌ Error: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
