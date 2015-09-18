<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_142056_create_table_order_status_info extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_status_info}}', [
            'id'=> Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_status_info_name'=> Schema::TYPE_STRING.'(255) NOT NULL COMMENT \'状态名称\'',
            'order_status_flow'=> Schema::TYPE_STRING.'(32) NOT NULL DEFAULT 0 COMMENT \'大体流程\'',
            'order_status_oper'=> Schema::TYPE_STRING.'(255) NOT NULL COMMENT \'对应操作\'',
            'order_status_oper_man'=> Schema::TYPE_STRING.'(255) NOT NULL COMMENT \'对应的角色\'',
            'created_at' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0',
            'isdel' => Schema::TYPE_SMALLINT.'(1) unsigned NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_status_info}}');
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
