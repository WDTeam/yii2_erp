<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_141212_create_table_general_pay_log extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'支付记录日志表\'';
        }
        $this->createTable('{{%general_pay_log}}', [
            'id'  => Schema::TYPE_PK . ' AUTO_INCREMENT ',
            'general_pay_log_price'  => Schema::TYPE_DECIMAL . '(9,2) COMMENT \'支付金额\'',
            'general_pay_log_shop_name'  => Schema::TYPE_STRING . '(50) DEFAULT \'\' COMMENT \'商品名称\'',
            'general_pay_log_eo_order_id'  => Schema::TYPE_STRING . '(30) COMMENT \'第三方订单ID\'',
            'general_pay_log_transaction_id'  => Schema::TYPE_STRING . '(40) COMMENT \'第三方交易流水号\'',
            'general_pay_log_status_bool'  => Schema::TYPE_BOOLEAN . '(1) COMMENT \'状态数\'',
            'general_pay_log_status'  => Schema::TYPE_STRING . '(30) COMMENT \'状态\'',
            'pay_channel_id'  => Schema::TYPE_BOOLEAN . '(4) DEFAULT 0 COMMENT \'支付渠道\'',
            'pay_channel_name'  => Schema::TYPE_STRING . '(20) COMMENT \'支付渠道名称\'',
            'general_pay_log_json_aggregation'  => Schema::TYPE_TEXT . ' COMMENT \'记录数据集合\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) COMMENT \'更新时间\'',
            'is_del'  => Schema::TYPE_BOOLEAN . '(1) DEFAULT 1 COMMENT \'删除\'',

        ], $tableOptions);

        $this->createIndex('pay_channel_id','{{%general_pay_log}}','pay_channel_id');
        $this->createIndex('general_pay_log_eo_order_id','{{%general_pay_log}}','general_pay_log_eo_order_id');
        $this->createIndex('general_pay_log_transaction_id','{{%general_pay_log}}','general_pay_log_transaction_id');
        $this->createIndex('general_pay_log_status','{{%general_pay_log}}','general_pay_log_status');
    }


    public function down()
    {
        $this->dropTable("{{%general_pay_log}}");
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
