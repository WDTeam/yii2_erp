<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_102439_create_table_operation_tag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'标签表\'';
        }
        $this->createTable('{{%operation_tag}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_tag_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'标签名称\'',
            'operation_applicable_scope_id' => Schema::TYPE_INTEGER . '(11) DEFAULT 1 COMMENT \'适用范围编号\'',
            'operation_applicable_scope_name' => Schema::TYPE_STRING . '(30) DEFAULT \'服务类型\' COMMENT \'适用范围名称\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_tag}}');

        return true;
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
