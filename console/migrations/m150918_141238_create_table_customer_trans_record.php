<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_141238_create_table_customer_trans_record extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户交易记录表\'';
        }
        $this->createTable('{{%customer_trans_record}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT ' ,
            'customer_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL COMMENT \'用户ID\'' ,
            'order_id' => Schema::TYPE_INTEGER . '(11) unsigned DEFAULT 0 NOT NULL COMMENT \'订单ID\'' ,
            'order_channel_id' => Schema::TYPE_SMALLINT . '(6) unsigned DEFAULT 0 NOT NULL COMMENT \'订单渠道\'' ,
            'customer_trans_record_order_channel'  => Schema::TYPE_INTEGER . '(30) NOT NULL COMMENT \'订单渠道名称\'',
            'pay_channel_id'  => Schema::TYPE_SMALLINT . '(6) unsigned NOT NULL COMMENT \'支付渠道\'',
            'customer_trans_record_pay_channel'  => Schema::TYPE_INTEGER . '(30) NOT NULL COMMENT \'支付渠道名称\'',
            'customer_trans_record_mode'  => Schema::TYPE_BOOLEAN . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'交易方式:1消费,2=充值,3=退款,4=赔偿,5=补偿\'',
            'customer_trans_record_mode_name'  => Schema::TYPE_SMALLINT . '(20) NOT NULL COMMENT \'交易方式名称\'',
            'customer_trans_record_promo_code_money'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'优惠码金额\'',
            'customer_trans_record_coupon_money'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'优惠券金额\'',
            'customer_trans_record_cash'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'现金支付\'',

            'customer_trans_record_pre_pay'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'预付费金额（第三方）\'',
            'customer_trans_record_online_pay'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'在线支付\'',
            'customer_trans_record_online_balance_pay'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'在线余额支付\'',
            'customer_trans_record_online_service_card_on'  => Schema::TYPE_STRING . '(30) NOT NULL DEFAULT 0 COMMENT \'服务卡号\'',
            'customer_trans_record_online_service_card_pay'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'服务卡支付\'',
            'customer_trans_record_refund_money'  => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL COMMENT \'退款金额\'',

            'customer_trans_record_money'  => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'余额支付\'',


            'customer_trans_record_order_total_money'  => Schema::TYPE_DECIMAL . '(5,2) unsigned NOT NULL DEFAULT 0 COMMENT \'订单总额\'',
            'customer_trans_record_total_money'  => Schema::TYPE_DECIMAL . '(9,2) unsigned NOT NULL DEFAULT 0 COMMENT \'交易总额\'',
            'customer_trans_record_current_balance'  => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'当前余额\'',
            'customer_trans_record_befor_balance'  => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'之前余额\'',
            'customer_trans_record_transaction_id'  => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT 0 COMMENT \'交易流水号\'',
            'customer_trans_record_remark'  => Schema::TYPE_STRING . '(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT \'\' COMMENT \'备注\'',
            'customer_trans_record_verify'  => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'验证\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'',
            'is_del'  => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'删除\'',

        ], $tableOptions);

        $this->createIndex('customer_id','{{%customer_trans_record}}','customer_id');
        $this->createIndex('order_id','{{%customer_trans_record}}','order_id');
        $this->createIndex('order_channel_id','{{%customer_trans_record}}','order_channel_id');
        $this->createIndex('customer_trans_record_mode','{{%customer_trans_record}}','customer_trans_record_mode');
        $this->createIndex('pay_channel_id','{{%customer_trans_record}}','pay_channel_id');
        $this->createIndex('customer_trans_record_transaction_id','{{%customer_trans_record}}','customer_trans_record_transaction_id');
    }

    public function down()
    {
        $this->dropTable("{{%customer_trans_record}}");
        return false;
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
