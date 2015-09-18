<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_075922_create_table_category_type extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'服务类型表\'';
        }
        $this->createTable('{{%category_type}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'category_type_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务类型名称\'',
            'category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务品类编号（服务类型的上级编号冗余）\'',
            'category_name' => Schema::TYPE_STRING . '(11) DEFAULT NULL COMMENT \'对应服务品类名称（服务类型的上级名称冗余）\'',
            'category_type_introduction' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务简介\'',
            'category_type_english_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务类型英文名称\'',
            
            
            
        ], $tableOptions);
    }

    public function down(){
        $this->dropTable('{{%category_type}}');
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
