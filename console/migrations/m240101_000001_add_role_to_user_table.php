<?php

use yii\db\Migration;

class m240101_000001_add_role_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(20)->notNull()->defaultValue('reader'));
        
        // Create index for role field
        $this->createIndex('idx-user-role', '{{%user}}', 'role');
    }

    public function down()
    {
        $this->dropIndex('idx-user-role', '{{%user}}');
        $this->dropColumn('{{%user}}', 'role');
    }
}
