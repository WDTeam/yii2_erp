<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_133029_ejj_platform extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'平台表\'';
        }
        $this->createTable('{{%ejj_platform}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'platform_name'=>  Schema::TYPE_STRING.'(16) NOT NULL COMMENT \'平台名称\'' ,
            'pid'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL' ,
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%ejj_platform}}');
    }
}
