<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_133302_create_table_customer_help extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户APP问答表\'';
        }
        $this->createTable('{{%customer_help}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'help_question'=> Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'问题\'',
            'help_solution'=> Schema::TYPE_TEXT.' COMMENT \'回答\'',
            'help_status'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'0隐藏，1显示\'',
            'help_sort'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'排序,越小排在前面\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'创建时间\'',
            'update_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'更新时间\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_help}}');
    }
}
