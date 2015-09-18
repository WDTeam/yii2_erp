<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_124802_create_table_worker_account extends Migration
{
    public function up()
    {
           $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'启动页表\'';
        }
        $this->createTable('{{%worker_account}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'worder_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'worker_account_subsidy_income' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'补贴收入\'',
            'worker_account_order_income' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'订单收入\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 1 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'更新时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_account}}');
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
