<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_103006_ejj_help extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户APP问答表\'';
        }
        $this->createTable('{{%help}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'help_question'=> Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'问题\'' ,
            'help_solution'=> Schema::TYPE_TEXT.' DEFAULT NULL COMMENT \'回答\'' ,
            'help_status'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'0隐藏，1显示\'' ,
            'help_sort'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'越小排在前面\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'update_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%help}}');
    }
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
