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
            'parent_id' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'上级id(为0是顶级分类)\'',
            'sort' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'排序\'',

            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
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
