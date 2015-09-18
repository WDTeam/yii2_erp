<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_112652_create_table_subsidy_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'启动页表\'';
        }
        $this->createTable('{{%subsidy_category}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'subsidy_category_code' => Schema::TYPE_STRING . '(20) NOT NULL COMMENT \'补助或奖励编码\'',
            'subsidy_category_name' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT \'补助或奖励名称\'',
            'subsidy_category_settle_type' => Schema::TYPE_SMALLINT . '(3) NOT NULL COMMENT \'1，按单结算；2，按月结算；3，按结算周期结算\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 1 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'更新时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%subsidy_category}}');
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
