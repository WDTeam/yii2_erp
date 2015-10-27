<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_075847_create_table_operation_category extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务品类表\'';
        }
        $this->createTable('{{%operation_category}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_category_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务品类名称\'',
            'operation_category_parent_id' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'上级id(为0是顶级分类)\'',
            'operation_category_parent_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'上级服务品类名称\'',
            'sort' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'排序\'',

            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);

        $this->execute(
            "INSERT INTO {{%operation_category}} (`id`, `operation_category_name`, `operation_category_parent_id`, `operation_category_parent_name`, `created_at`, `updated_at`)
VALUES
	(8, '手机', 0, NULL, 1444229187, 1444229187),(9, '清洁服务', 0, NULL, 1444229187, 1444229187),(10, '保护费', 0, NULL, 1444229187, 1444229187);"
        );
    }

    public function down(){
        $this->dropTable('{{%operation_category}}');

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
