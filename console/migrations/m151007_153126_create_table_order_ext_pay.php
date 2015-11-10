<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151007_153126_create_table_order_ext_pay extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单支付信息表\'';
        }


        $this->createTable('{{%order_ext_pay}}', [
            'order_id'=> Schema::TYPE_BIGPK .' NOT NULL COMMENT \'订单id\'',

            //============================支付信息
            'order_pay_type' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'支付方式 0未支付 1现金支付 2线上支付 3第三方预付 \'', //如果是线上支付 支付成功之后再改状态
            'pay_channel_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'支付渠道id\'',
            'order_pay_channel_name' => Schema::TYPE_STRING.'(128)  DEFAULT \'\' COMMENT \'支付渠道名称\'',
            'order_pay_flow_num' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'支付流水号\'',
            'order_pay_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'支付金额\'',
            'order_use_acc_balance' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'使用余额\'',
            'card_id' => Schema::TYPE_INTEGER . '(11) unsigned  DEFAULT 0 COMMENT \'服务卡ID\'',
            'order_use_card_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'使用服务卡金额\'',
            'coupon_id' => Schema::TYPE_INTEGER.'(11) unsigned  DEFAULT 0 COMMENT \'优惠券ID\'',
            'order_coupon_code' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'优惠码\'',
            'order_use_coupon_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'使用优惠卷金额\'',
            'promotion_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'促销id\'',
            'order_use_promotion_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'使用促销金额\'',

            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',

        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_ext_pay}}');

        return true;
    }
}
