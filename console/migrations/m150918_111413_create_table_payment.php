<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_111413_create_table_payment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户充值交易记录\'';
        }
        $this->createTable('{{%payment}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT ' ,
            'customer_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL COMMENT \'用户ID\'' ,
            'customer_phone' => Schema::TYPE_STRING . '(11) COMMENT \'用户电话\'' ,

            'order_id' => Schema::TYPE_STRING . '(30) DEFAULT 0 NOT NULL COMMENT \'订单ID\'' ,
            'order_code' => Schema::TYPE_STRING . '(64) COMMENT \'订单编号\'' ,
            'order_batch_code' => Schema::TYPE_STRING . '(64) COMMENT \'周期订单编号\'' ,

            'payment_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned DEFAULT 0 NOT NULL COMMENT \'发起充值/交易金额\'' ,
            'payment_actual_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned DEFAULT 0 NOT NULL COMMENT \'实际充值/交易金额\'' ,
            'payment_source' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'来源渠道\'',
            'payment_channel_id' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'支付渠道\'',
            'payment_channel_name' => Schema::TYPE_STRING . '(20) NOT NULL COMMENT \'支付渠道名称\'' ,
            'payment_mode' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'交易方式:1=消费,2=充值,3=退款,4=补偿\'' ,
            'payment_status' => Schema::TYPE_BOOLEAN . '(1) unsigned DEFAULT 0 NOT NULL COMMENT \'状态：0=失败,1=成功\'' ,
            'payment_transaction_id' => Schema::TYPE_STRING . '(40) DEFAULT 0 NOT NULL COMMENT \'第三方交易流水号\'' ,
            'payment_eo_order_id' => Schema::TYPE_STRING . '(30) DEFAULT 0 NOT NULL COMMENT \'商户ID(第三方交易)\'' ,
            'payment_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' NOT NULL COMMENT \'备注\'' ,
            'payment_type' => Schema::TYPE_BOOLEAN . '(1) unsigned DEFAULT 0 NOT NULL COMMENT \'支付方式:1普通订单,2周期订单,3充值\'' ,
            'admin_id' => Schema::TYPE_INTEGER . '(10) unsigned DEFAULT 0 NOT NULL COMMENT \'管理员ID\'' ,
            'payment_admin_name' => Schema::TYPE_STRING . '(30) DEFAULT \'\' NOT NULL COMMENT \'管理员名称\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'销售卡阿姨ID\'' ,
            'handle_admin_id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'办卡人ID\'' ,
            'payment_handle_admin_name' => Schema::TYPE_STRING . '(30) NOT NULL DEFAULT \'\' COMMENT \'办卡人名称\'' ,
            'payment_verify' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT \'\' COMMENT \'支付验证\'' ,
            'is_reconciliation' => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT 0 COMMENT \'是否对账\'' ,
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'' ,
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'' ,

        ], $tableOptions);

        $this->createIndex('customer_id','{{%payment}}','customer_id');
        $this->createIndex('customer_phone','{{%payment}}','customer_phone');
        $this->createIndex('order_id','{{%payment}}','order_id');
        $this->createIndex('order_code','{{%payment}}','order_code');
        $this->createIndex('order_batch_code','{{%payment}}','order_batch_code');
        $this->createIndex('payment_source','{{%payment}}','payment_source');
        $this->createIndex('payment_mode','{{%payment}}','payment_mode');
        $this->createIndex('payment_status','{{%payment}}','payment_status');
        $this->createIndex('payment_transaction_id','{{%payment}}','payment_transaction_id');
        $this->createIndex('payment_eo_order_id','{{%payment}}','payment_eo_order_id');
    }

    public function down()
    {
        $this->dropTable("{{%payment}}");
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
