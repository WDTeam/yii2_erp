<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_102851_ejj_channal extends Migration
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
            'pid'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL COMMENT \'父级id\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'修改时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%channal}}');
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
