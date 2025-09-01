<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * BookChapter model
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $chapter_number
 * @property string $title
 * @property string $content
 * @property integer $page_count
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Book $book
 */
class BookChapter extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_chapters}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'chapter_number', 'title', 'content', 'page_count'], 'required'],
            [['book_id', 'chapter_number', 'page_count'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['book_id', 'chapter_number'], 'unique', 'targetAttribute' => ['book_id', 'chapter_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book',
            'chapter_number' => 'Chapter Number',
            'title' => 'Title',
            'content' => 'Content',
            'page_count' => 'Page Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get book relation
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * Get content preview (first 10 pages worth of content)
     */
    public function getPreviewContent($maxPages = 10)
    {
        // Simple implementation - in a real system you'd want more sophisticated page calculation
        $words = explode(' ', $this->content);
        $wordsPerPage = 250; // Average words per page
        $maxWords = $maxPages * $wordsPerPage;
        
        if (count($words) <= $maxWords) {
            return $this->content;
        }
        
        return implode(' ', array_slice($words, 0, $maxWords)) . '...';
    }

    /**
     * Check if content is preview only
     */
    public function isPreviewOnly($maxPages = 10)
    {
        $words = explode(' ', $this->content);
        $wordsPerPage = 250;
        $maxWords = $maxPages * $wordsPerPage;
        
        return count($words) > $maxWords;
    }
}
