<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_133240_create_table_customer_feedback extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户反馈表\'';
        }
        $this->createTable('{{%customer_feedback}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联用户\'' ,
            'customer_phone'=>  Schema::TYPE_STRING.'(11) NOT NULL COMMENT \'客户手机\'' ,
            'feedback_content'=>  Schema::TYPE_TEXT.' NOT NULL COMMENT \'反馈内容\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            'is_del' =>Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'是否逻辑删除\'' ,
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_feedback}}');

        return true;
    }
}
