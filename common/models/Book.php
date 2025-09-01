<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Book model
 *
 * @property integer $id
 * @property string $title
 * @property integer $author_id
 * @property string $isbn
 * @property string $description
 * @property string $content
 * @property string $cover_image
 * @property string $published_date
 * @property integer $page_count
 * @property string $genre
 * @property string $language
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 * @property BookChapter[] $chapters
 */
class Book extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%books}}';
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
            [['title', 'author_id'], 'required'],
            [['author_id', 'page_count', 'status'], 'integer'],
            [['description', 'content'], 'string'],
            [['published_date'], 'safe'],
            [['title', 'isbn', 'cover_image', 'genre'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
            [['isbn'], 'unique'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['language'], 'default', 'value' => 'en'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'author_id' => 'Author',
            'isbn' => 'ISBN',
            'description' => 'Description',
            'content' => 'Content',
            'cover_image' => 'Cover Image',
            'published_date' => 'Published Date',
            'page_count' => 'Page Count',
            'genre' => 'Genre',
            'language' => 'Language',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get author relation
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Get chapters relation
     */
    public function getChapters()
    {
        return $this->hasMany(BookChapter::class, ['book_id' => 'id'])->orderBy(['chapter_number' => SORT_ASC]);
    }

    /**
     * Get total page count from chapters
     */
    public function getTotalPageCount()
    {
        return $this->getChapters()->sum('page_count');
    }

    /**
     * Check if user can edit this book
     */
    public function canEdit($user)
    {
        if (!$user) {
            return false;
        }
        
        return $user->isAdmin() || ($user->isAuthor() && $user->id === $this->author_id);
    }

    /**
     * Check if user can delete this book
     */
    public function canDelete($user)
    {
        if (!$user) {
            return false;
        }
        
        return $user->isAdmin() || ($user->isAuthor() && $user->id === $this->author_id);
    }
}
