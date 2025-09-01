<?php

use yii\db\Migration;

class m240101_000002_create_books_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'author_id' => $this->integer()->notNull(),
            'isbn' => $this->string(20)->unique(),
            'description' => $this->text(),
            'content' => $this->text(),
            'cover_image' => $this->string(255),
            'published_date' => $this->date(),
            'page_count' => $this->integer(),
            'genre' => $this->string(100),
            'language' => $this->string(10)->defaultValue('en'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1), // 1=active, 0=inactive
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx-books-author_id', '{{%books}}', 'author_id');
        $this->createIndex('idx-books-isbn', '{{%books}}', 'isbn');
        $this->createIndex('idx-books-genre', '{{%books}}', 'genre');
        $this->createIndex('idx-books-status', '{{%books}}', 'status');

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-books-author_id',
            '{{%books}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-books-author_id', '{{%books}}');
        $this->dropTable('{{%books}}');
    }
}
