<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132943_ejj_channal extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'渠道表\'';
        }
        $this->createTable('{{%channal}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'channal_name'=>  Schema::TYPE_STRING.'(16) NOT NULL COMMENT \'聚道名称\'' ,
            'pid'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL' ,
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%channal}}');
    }
}
