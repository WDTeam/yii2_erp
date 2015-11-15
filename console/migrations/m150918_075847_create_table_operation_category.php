<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_075847_create_table_operation_category extends Migration
{
    const TB_NAME = '{{%operation_category}}';

    public function up(){
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务品类表\'';
        }
        $this->createTable('{{%operation_category}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_category_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务品类名称\'',
            'operation_category_icon' => Schema::TYPE_STRING . '(128) DEFAULT NULL COMMENT \'服务品类图片\'',
            'operation_category_introduction'=>  Schema::TYPE_TEXT.' NOT NULL COMMENT \'服务品类介绍\'' ,
            'operation_category_price_description' => Schema::TYPE_STRING . '(128) DEFAULT NULL COMMENT \'价格备注\'',
            'operation_category_url' => Schema::TYPE_STRING . '(258) DEFAULT NULL COMMENT \'跳转的链接\'',
            'operation_category_parent_id' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'上级id(为0是顶级分类)\'',
            'operation_category_parent_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'上级服务品类名称\'',
            'sort' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'排序\'',

            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);

    }

    public function down(){
        $this->dropTable(self::TB_NAME);

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
