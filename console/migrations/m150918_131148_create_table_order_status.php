<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_131148_create_table_order_status extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_status}}', [
            'id'  => Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_id'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'订单编号\'',
            'created_at'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间初始时间\'',
            'updated_at'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'',
            'order_status_code'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'状态码=所有状态的布尔值组合成二进制再转成10进制\'',
            'order_status_cancel_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'取消订单\'',
            'order_status_pey_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'付款中\'',
            'order_status_pay_done_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'已付款\'',
            'order_status_wait_send_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'待指派\'',
            'order_status_send_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'系统指派\'',
            'order_status_allot_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'系统分单\'',
            'order_status_labor_allot_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'人工分单\'',
            'order_status_labor_send_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'人工派单\'',
            'order_status_labor_send_failure_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'人工派单失败\'',
            'order_status_send_timeout_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'超时未指派\'',
            'order_status_send_done_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'待服务\'',
            'order_status_service_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'服务中\'',
            'order_status_service_done_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'服务完成待评价\'',
            'order_status_comment_done_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'评价完成\'',
            'order_status_worker_payout_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨已结算\'',
            'order_status_php_pay_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'第三方对账\'',
            'order_status_shop_pay_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'门店已结算\'',
            'order_status_payback'  => Schema::TYPE_BOOLEAN.'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'产生退款 0未产生 1产生退款\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_status}}');
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
