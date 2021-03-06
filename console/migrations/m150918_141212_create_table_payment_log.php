<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_141212_create_table_payment_log extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'支付记录日志表\'';
        }
        $this->createTable('{{%payment_log}}', [
            'id'  => Schema::TYPE_PK . ' AUTO_INCREMENT ',
            'payment_log_price'  => Schema::TYPE_DECIMAL . '(9,2) COMMENT \'支付金额\'',
            'payment_log_shop_name'  => Schema::TYPE_STRING . '(50) DEFAULT \'\' COMMENT \'商品名称\'',
            'payment_log_eo_order_id'  => Schema::TYPE_STRING . '(30) COMMENT \'第三方订单ID\'',
            'payment_log_transaction_id'  => Schema::TYPE_STRING . '(40) COMMENT \'第三方交易流水号\'',
            'payment_log_status_bool'  => Schema::TYPE_BOOLEAN . '(1) COMMENT \'状态数\'',
            'payment_log_status'  => Schema::TYPE_STRING . '(30) COMMENT \'状态\'',
            'pay_channel_id'  => Schema::TYPE_BOOLEAN . '(4) DEFAULT 0 COMMENT \'支付渠道\'',
            'pay_channel_name'  => Schema::TYPE_STRING . '(20) COMMENT \'支付渠道名称\'',
            'payment_log_json_aggregation'  => Schema::TYPE_TEXT . ' COMMENT \'记录数据集合\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) COMMENT \'更新时间\'',

        ], $tableOptions);

        $this->createIndex('pay_channel_id','{{%payment_log}}','pay_channel_id');
        $this->createIndex('payment_log_eo_order_id','{{%payment_log}}','payment_log_eo_order_id');
        $this->createIndex('payment_log_transaction_id','{{%payment_log}}','payment_log_transaction_id');
        $this->createIndex('payment_log_status','{{%payment_log}}','payment_log_status');
    }


    public function down()
    {
        $this->dropTable("{{%payment_log}}");
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
