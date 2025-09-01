<?php

use yii\db\Migration;

class m240101_000003_create_book_chapters_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%book_chapters}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'chapter_number' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'page_count' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx-book_chapters-book_id', '{{%book_chapters}}', 'book_id');
        $this->createIndex('idx-book_chapters-chapter_number', '{{%book_chapters}}', 'chapter_number');
        $this->createIndex('idx-book_chapters-book_chapter', '{{%book_chapters}}', ['book_id', 'chapter_number'], true);

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-book_chapters-book_id',
            '{{%book_chapters}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-book_chapters-book_id', '{{%book_chapters}}');
        $this->dropTable('{{%book_chapters}}');
    }
}
