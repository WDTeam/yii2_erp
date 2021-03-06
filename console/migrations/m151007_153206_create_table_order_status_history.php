<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153206_create_table_order_status_history extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态快照表\'';
        }


        $this->createTable('{{%order_status_history}}', [
            'id' => Schema::TYPE_BIGPK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'快照创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'快照修改时间\'',

            'order_id' => Schema::TYPE_BIGINT .'(20) unsigned NOT NULL COMMENT \'订单ID\'',
            'order_before_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'状态变更前订单状态字典ID\'',
            'order_before_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'状态变更前订单状态\'',

            'order_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'订单状态字典ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'订单状态\'',
            'order_status_boss' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'BOOS状态名称\'',
            'order_status_customer' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'客户端状态名称\'',
            'order_status_worker' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'阿姨端状态名称\'',

            'admin_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'操作人id 1系统 2客户 3阿姨 >3后台管理员\'',
        ], $tableOptions);
        $this->createIndex('idx-order_status_history-order_id', '{{%order_status_history}}', 'order_id');
    }

    public function down()
    {
        $this->dropTable('{{%order_status_history}}');

        return true;
    }


}
