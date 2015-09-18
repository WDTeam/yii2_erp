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
            'order_status_pey_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'支付开始时间\'',
            'order_status_pay_done_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'支付完成时间\'',
            'order_status_cancel_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'取消订单时间\'',
            'order_status_cancel_man'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'1、用户取消2、系统取消3、阿姨取消4、客服取消5、门店取消\'',
            'order_status_wait_send_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'待指派时间\'',
            'order_status_send_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'开始指派\'',
            'order_status_wait_labor_allot_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'待人工分单\'',
            'order_status_wait_labor_send_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'待人工指派\'',
            'order_status_labor_send_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'人工指派开始\'',
            'order_status_apply_payback_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'待退款\'',
            'order_status_timeout_no_send_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'超时未指派\'',
            'order_status_send_done_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'指派完成贷服务\'',
            'order_status_service_begin_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'服务开始\'',
            'order_status_service_end_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'服务结束\'',
            'order_status_accept_payback_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'退款中 同意退款\'',
            'order_status_ok_payback_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'退款完成 确认已退款\'',
            'order_status_worker_payout_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨已结算\'',
            'order_status_php_pay_time'  => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'第三方结算\'',
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
