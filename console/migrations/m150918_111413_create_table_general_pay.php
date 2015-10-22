<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_111413_create_table_general_pay extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户充值交易记录\'';
        }
        $this->createTable('{{%general_pay}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT ' ,
            'customer_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL COMMENT \'用户ID\'' ,
            'order_id' => Schema::TYPE_INTEGER . '(11) unsigned DEFAULT 0 NOT NULL COMMENT \'订单ID\'' ,
            'general_pay_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned DEFAULT 0 NOT NULL COMMENT \'发起充值/交易金额\'' ,
            'general_pay_actual_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned DEFAULT 0 NOT NULL COMMENT \'实际充值/交易金额\'' ,
            'general_pay_source' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'数据来源:关联订单渠道表\'',
            'general_pay_source_name' => Schema::TYPE_STRING . '(20) NOT NULL COMMENT \'数据来源名称\'' ,
            'general_pay_mode' => Schema::TYPE_BOOLEAN . '(3) unsigned DEFAULT 0 NOT NULL COMMENT \'交易方式:1=消费,2=充值,3=退款,4=补偿\'' ,
            'general_pay_status' => Schema::TYPE_BOOLEAN . '(1) unsigned DEFAULT 0 NOT NULL COMMENT \'状态：0=失败,1=成功\'' ,
            'general_pay_transaction_id' => Schema::TYPE_STRING . '(40) DEFAULT 0 NOT NULL COMMENT \'第三方交易流水号\'' ,
            'general_pay_eo_order_id' => Schema::TYPE_STRING . '(30) DEFAULT 0 NOT NULL COMMENT \'商户ID(第三方交易)\'' ,
            'general_pay_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' NOT NULL COMMENT \'备注\'' ,
            'general_pay_is_coupon' => Schema::TYPE_BOOLEAN . '(1) unsigned DEFAULT 0 NOT NULL COMMENT \'是否返券\'' ,
            'admin_id' => Schema::TYPE_INTEGER . '(10) unsigned DEFAULT 0 NOT NULL COMMENT \'管理员ID\'' ,
            'general_pay_admin_name' => Schema::TYPE_STRING . '(30) DEFAULT \'\' NOT NULL COMMENT \'管理员名称\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'销售卡阿姨ID\'' ,
            'handle_admin_id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'办卡人ID\'' ,
            'general_pay_handle_admin_name' => Schema::TYPE_STRING . '(30) NOT NULL DEFAULT \'\' COMMENT \'办卡人名称\'' ,
            'general_pay_verify' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT \'\' COMMENT \'支付验证\'' ,
            'is_reconciliation' => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT 0 COMMENT \'是否对账\'' ,
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'' ,
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'' ,

        ], $tableOptions);

        $this->createIndex('customer_id','{{%general_pay}}','customer_id');
        $this->createIndex('order_id','{{%general_pay}}','order_id');
        $this->createIndex('general_pay_source','{{%general_pay}}','general_pay_source');
        $this->createIndex('general_pay_mode','{{%general_pay}}','general_pay_mode');
        $this->createIndex('general_pay_status','{{%general_pay}}','general_pay_status');
        $this->createIndex('general_pay_transaction_id','{{%general_pay}}','general_pay_transaction_id');
        $this->createIndex('general_pay_eo_order_id','{{%general_pay}}','general_pay_eo_order_id');
    }

    public function down()
    {
        $this->dropTable("{{%general_pay}}");
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
