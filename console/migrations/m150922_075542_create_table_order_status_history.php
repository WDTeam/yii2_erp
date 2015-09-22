<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_075542_create_table_order_status_history extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态快照表\'';
        }


        $this->createTable('{{%order_status_history}}', [
            'id'=>Schema::TYPE_BIGPK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'快照创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'快照修改时间\'',
            'order_id'=> Schema::TYPE_BIGINT .'(20) unsigned NOT NULL COMMENT \'编号\'',
            'order_before_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'状态变更前订单状态字典ID\'',
            'order_before_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'状态变更前订单状态\'',
            'order_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'订单状态字典ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'订单状态\'',
            'admin_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'操作人id  0客户操作 1系统操作\'',
            'isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'快照是否已删除\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_status_history}}');
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
