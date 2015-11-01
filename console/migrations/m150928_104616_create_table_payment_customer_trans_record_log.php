<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m150928_104616_create_table_payment_customer_trans_record_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'交易记录日志表\'';
        }
        $this->createTable('{{%payment_customer_trans_record_log}}', [
            'id' => Schema::TYPE_PK,
            'customer_id' => Schema::TYPE_INTEGER . "(11) COMMENT '用户ID'",
            'order_id' => Schema::TYPE_INTEGER . "(11) COMMENT '订单ID'",
            'order_channel_id' => Schema::TYPE_SMALLINT . "(6) COMMENT '订单渠道'",
            'payment_customer_trans_record_order_channel' => Schema::TYPE_STRING . "(30) COMMENT '订单渠道名称'",
            'pay_channel_id' => Schema::TYPE_SMALLINT . "(6) COMMENT '支付渠道'",
            'payment_customer_trans_record_pay_channel' => Schema::TYPE_STRING . "(30) COMMENT '支付渠道名称'",
            'payment_customer_trans_record_mode' => Schema::TYPE_SMALLINT . "(4) COMMENT '交易方式:1消费,2=充值,3=退款,4=赔偿'",
            'payment_customer_trans_record_mode_name' => Schema::TYPE_STRING . "(20) COMMENT '交易方式名称'",
            'payment_customer_trans_record_coupon_money' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '优惠券金额'",
            'payment_customer_trans_record_cash' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '现金支付'",
            'payment_customer_trans_record_pre_pay' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '预付费金额（第三方）'",
            'payment_customer_trans_record_online_pay' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '在线支付'",
            'payment_customer_trans_record_online_balance_pay' => Schema::TYPE_DECIMAL . "(5,2)  COMMENT '在线余额支付'",
            'payment_customer_trans_record_service_card_on' => Schema::TYPE_STRING . "(30)  COMMENT '服务卡号'",
            'payment_customer_trans_record_service_card_pay' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '服务卡支付'",
            'payment_customer_trans_record_service_card_current_balance' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '服务卡当前余额'",
            'payment_customer_trans_record_service_card_befor_balance' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '服务卡之前余额'",
            'payment_customer_trans_record_compensate_money' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '补偿金额'",
            'payment_customer_trans_record_refund_money' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '退款金额'",
            'payment_customer_trans_record_order_total_money' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '订单总额'",
            'payment_customer_trans_record_total_money' => Schema::TYPE_DECIMAL . "(9,2)  COMMENT '交易总额'",
            'payment_customer_trans_record_current_balance' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '当前余额'",
            'payment_customer_trans_record_befor_balance' => Schema::TYPE_DECIMAL . "(8,2)  COMMENT '之前余额'",
            'payment_customer_trans_record_transaction_id' => Schema::TYPE_STRING . "(40)  COMMENT '交易流水号'",
            'payment_customer_trans_record_remark' => Schema::TYPE_STRING . "(255)  COMMENT '备注'",
            'payment_customer_trans_record_verify' => Schema::TYPE_STRING . "(32)  COMMENT '验证'",
            'created_at' => Schema::TYPE_INTEGER . "(10)  COMMENT '创建时间'",
            'updated_at' => Schema::TYPE_INTEGER . "(10)  COMMENT '更新时间'",
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%payment_customer_trans_record_log}}');

        return true;
    }
}
