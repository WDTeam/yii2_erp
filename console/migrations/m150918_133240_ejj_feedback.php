<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_133240_ejj_feedback extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户反馈表\'';
        }
        $this->createTable('{{%feedback}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联用户\'' ,
            'feedback_content'=>  Schema::TYPE_TEXT.' NOT NULL COMMENT \'反馈内容\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%feedback}}');
    }
}
