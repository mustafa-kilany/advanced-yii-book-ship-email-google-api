<?php

namespace common\services;

use Yii;
use yii\base\Component;
use yii\web\HttpException;

/**
 * Book API Service for integrating with external book APIs
 */
class BookApiService extends Component
{
    public $apiKey;
    public $baseUrl = 'https://www.googleapis.com/books/v1';
    
    public function init()
    {
        parent::init();
        $this->apiKey = Yii::$app->params['googleBooksApiKey'] ?? null;
    }

    /**
     * Search for books by query
     */
    public function searchBooks($query, $maxResults = 20)
    {
        $url = $this->baseUrl . '/volumes';
        $params = [
            'q' => $query,
            'maxResults' => $maxResults,
            'key' => $this->apiKey,
        ];

        try {
            $response = $this->makeRequest($url, $params);
            return $this->parseSearchResults($response);
        } catch (\Exception $e) {
            Yii::error('Book API search failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get book details by ISBN
     */
    public function getBookByIsbn($isbn)
    {
        $query = "isbn:{$isbn}";
        $books = $this->searchBooks($query, 1);
        
        return !empty($books) ? $books[0] : null;
    }

    /**
     * Get book details by Google Books ID
     */
    public function getBookById($bookId)
    {
        $url = $this->baseUrl . '/volumes/' . $bookId;
        $params = ['key' => $this->apiKey];

        try {
            $response = $this->makeRequest($url, $params);
            return $this->parseBookDetails($response);
        } catch (\Exception $e) {
            Yii::error('Book API get by ID failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Make HTTP request to API
     */
    protected function makeRequest($url, $params = [])
    {
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new HttpException($httpCode, 'API request failed');
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response');
        }
        
        return $data;
    }

    /**
     * Parse search results from API response
     */
    protected function parseSearchResults($response)
    {
        $books = [];
        
        if (!isset($response['items'])) {
            return $books;
        }
        
        foreach ($response['items'] as $item) {
            $books[] = $this->parseBookItem($item);
        }
        
        return $books;
    }

    /**
     * Parse book details from API response
     */
    protected function parseBookDetails($response)
    {
        return $this->parseBookItem($response);
    }

    /**
     * Parse individual book item
     */
    protected function parseBookItem($item)
    {
        $volumeInfo = $item['volumeInfo'] ?? [];
        $saleInfo = $item['saleInfo'] ?? [];
        
        return [
            'id' => $item['id'] ?? null,
            'title' => $volumeInfo['title'] ?? '',
            'subtitle' => $volumeInfo['subtitle'] ?? '',
            'authors' => $volumeInfo['authors'] ?? [],
            'description' => $volumeInfo['description'] ?? '',
            'isbn' => $this->extractIsbn($volumeInfo['industryIdentifiers'] ?? []),
            'pageCount' => $volumeInfo['pageCount'] ?? 0,
            'categories' => $volumeInfo['categories'] ?? [],
            'language' => $volumeInfo['language'] ?? 'en',
            'publishedDate' => $volumeInfo['publishedDate'] ?? '',
            'publisher' => $volumeInfo['publisher'] ?? '',
            'imageLinks' => $volumeInfo['imageLinks'] ?? [],
            'previewLink' => $volumeInfo['previewLink'] ?? '',
            'infoLink' => $volumeInfo['infoLink'] ?? '',
            'canonicalVolumeLink' => $volumeInfo['canonicalVolumeLink'] ?? '',
        ];
    }

    /**
     * Extract ISBN from industry identifiers
     */
    protected function extractIsbn($identifiers)
    {
        foreach ($identifiers as $identifier) {
            if (isset($identifier['type']) && $identifier['type'] === 'ISBN_13') {
                return $identifier['identifier'];
            }
        }
        
        foreach ($identifiers as $identifier) {
            if (isset($identifier['type']) && $identifier['type'] === 'ISBN_10') {
                return $identifier['identifier'];
            }
        }
        
        return null;
    }

    /**
     * Import book from API to local database
     */
    public function importBook($bookData, $authorId)
    {
        $book = new \common\models\Book();
        $book->title = $bookData['title'];
        $book->author_id = $authorId;
        $book->isbn = $bookData['isbn'];
        $book->description = $bookData['description'];
        $book->page_count = $bookData['pageCount'];
        $book->genre = !empty($bookData['categories']) ? implode(', ', $bookData['categories']) : '';
        $book->language = $bookData['language'];
        $book->published_date = $bookData['publishedDate'];
        $book->cover_image = $bookData['imageLinks']['thumbnail'] ?? '';
        
        if ($book->save()) {
            // Create a sample chapter with the description as content
            $chapter = new \common\models\BookChapter();
            $chapter->book_id = $book->id;
            $chapter->chapter_number = 1;
            $chapter->title = 'Introduction';
            $chapter->content = $bookData['description'] ?: 'Sample content for this book.';
            $chapter->page_count = 1;
            $chapter->save();
            
            return $book;
        }
        
        return false;
    }
}
