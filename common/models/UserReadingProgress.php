<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * UserReadingProgress model
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $page_number
 * @property integer $read_at
 *
 * @property User $user
 * @property Book $book
 * @property BookChapter $chapter
 */
class UserReadingProgress extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_reading_progress}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'book_id', 'chapter_id', 'page_number'], 'required'],
            [['user_id', 'book_id', 'chapter_id', 'page_number'], 'integer'],
            [['read_at'], 'safe'],
            [['read_at'], 'default', 'value' => time()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'book_id' => 'Book',
            'chapter_id' => 'Chapter',
            'page_number' => 'Page Number',
            'read_at' => 'Read At',
        ];
    }

    /**
     * Get user relation
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get book relation
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * Get chapter relation
     */
    public function getChapter()
    {
        return $this->hasOne(BookChapter::class, ['id' => 'chapter_id']);
    }

    /**
     * Get total pages read by user for a specific book
     */
    public static function getTotalPagesRead($userId, $bookId)
    {
        return static::find()
            ->where(['user_id' => $userId, 'book_id' => $bookId])
            ->sum('page_number') ?: 0;
    }

    /**
     * Check if user has reached the free preview limit for a book
     */
    public static function hasReachedPreviewLimit($userId, $bookId, $limit = 10)
    {
        $totalPagesRead = self::getTotalPagesRead($userId, $bookId);
        return $totalPagesRead >= $limit;
    }

    /**
     * Record reading progress
     */
    public static function recordProgress($userId, $bookId, $chapterId, $pageNumber)
    {
        $progress = new static();
        $progress->user_id = $userId;
        $progress->book_id = $bookId;
        $progress->chapter_id = $chapterId;
        $progress->page_number = $pageNumber;
        $progress->read_at = time();
        
        return $progress->save();
    }
}
