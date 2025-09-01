<?php

use yii\db\Migration;

class m240101_000004_create_user_reading_progress_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_reading_progress}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'chapter_id' => $this->integer()->notNull(),
            'page_number' => $this->integer()->notNull(),
            'read_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx-user_reading_progress-user_id', '{{%user_reading_progress}}', 'user_id');
        $this->createIndex('idx-user_reading_progress-book_id', '{{%user_reading_progress}}', 'book_id');
        $this->createIndex('idx-user_reading_progress-user_book', '{{%user_reading_progress}}', ['user_id', 'book_id']);

        // Add foreign key constraints
        $this->addForeignKey(
            'fk-user_reading_progress-user_id',
            '{{%user_reading_progress}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_reading_progress-book_id',
            '{{%user_reading_progress}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_reading_progress-chapter_id',
            '{{%user_reading_progress}}',
            'chapter_id',
            '{{%book_chapters}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-user_reading_progress-chapter_id', '{{%user_reading_progress}}');
        $this->dropForeignKey('fk-user_reading_progress-book_id', '{{%user_reading_progress}}');
        $this->dropForeignKey('fk-user_reading_progress-user_id', '{{%user_reading_progress}}');
        $this->dropTable('{{%user_reading_progress}}');
    }
}
