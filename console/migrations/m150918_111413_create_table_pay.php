<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_111413_create_table_pay extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户充值交易记录\'';
        }
        $this->createTable('{{%pay}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT ' ,
            'customer_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL COMMENT \'用户ID\'' ,
            'order_id' => Schema::TYPE_INTEGER . '(11) unsigned DEFAULT 0 NOT NULL COMMENT \'订单ID\'' ,
            'pay_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned DEFAULT 0 NOT NULL COMMENT \'发起充值/交易金额\'' ,
            'pay_actual_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned DEFAULT 0 NOT NULL COMMENT \'实际充值/交易金额\'' ,
            'pay_source' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡\'',
            'pay_mode' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿\'' ,
            'pay_status' => Schema::TYPE_BOOLEAN . '(1) unsigned DEFAULT 0 NOT NULL COMMENT \'状态：0=失败,1=成功\'' ,
            'pay_transaction_id' => Schema::TYPE_STRING . '(40) DEFAULT 0 NOT NULL COMMENT \'第三方交易流水号\'' ,
            'pay_eo_order_id' => Schema::TYPE_STRING . '(30) DEFAULT 0 NOT NULL COMMENT \'商户ID(第三方交易)\'' ,
            'pay_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' NOT NULL COMMENT \'备注\'' ,
            'pay_is_coupon' => Schema::TYPE_BOOLEAN . '(1) unsigned DEFAULT 0 NOT NULL COMMENT \'是否返券\'' ,
            'admin_id' => Schema::TYPE_INTEGER . '(10) unsigned DEFAULT 0 NOT NULL COMMENT \'管理员ID\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'销售卡阿姨ID\'' ,
            'handle_admin_id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'办卡人ID\'' ,
            'pay_verify' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT \'\' COMMENT \'支付验证\'' ,
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'' ,
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'' ,
            'is_del' => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT 1 COMMENT \'删除\'' ,

        ], $tableOptions);

        $this->createIndex('customer_id','{{%pay}}','customer_id');
        $this->createIndex('order_id','{{%pay}}','order_id');
        $this->createIndex('pay_source','{{%pay}}','pay_source');
        $this->createIndex('pay_mode','{{%pay}}','pay_mode');
        $this->createIndex('pay_status','{{%pay}}','pay_status');
        $this->createIndex('pay_transaction_id','{{%pay}}','pay_transaction_id');
        $this->createIndex('pay_eo_order_id','{{%pay}}','pay_eo_order_id');
    }

    public function down()
    {
        $this->dropTable("{{%pay}}");
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
