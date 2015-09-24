<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_131138_create_table_order_history extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单快照表\'';
        }


        $this->createTable('{{%order_history}}', [
            'id'=>Schema::TYPE_BIGPK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'快照创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'快照修改时间\'',
            'order_id'=> Schema::TYPE_BIGINT .'(20) unsigned NOT NULL COMMENT \'编号\'',
            'order_code' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单号\'',
            'order_parent_id' => Schema::TYPE_BIGINT.'(20) unsigned NOT NULL DEFAULT 0 COMMENT \'父级id\'',
            'order_is_parent' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'有无子订单 1有 0无\'',
            'order_created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'order_updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
            'order_before_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'状态变更前订单状态字典ID\'',
            'order_before_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'状态变更前订单状态\'',
            'order_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'订单状态字典ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'订单状态\'',
            'order_flag_send' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了\'',
            'order_flag_urgent' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'加急\'',
            'order_flag_exception' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'异常 1无经纬度\'',

//===============================创建完订单后 不可修改的部分
            'order_service_type_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'订单服务类别ID\'',
            'order_service_type_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单服务类别\'',
            'order_src_id' => Schema::TYPE_SMALLINT . '(4) unsigned DEFAULT 0 COMMENT \'订单来源，订单入口id\'',
            'order_src_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单来源，订单入口名称\'',
            'channel_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'下单渠道ID\'',
            'order_channel_name' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'下单渠道名称\'',
            'order_channel_order_num' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT \'\' COMMENT \'渠道订单编号\'',
            'customer_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'用户编号\'',
            'order_ip' => Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'下单IP\'',
            'order_customer_phone' => Schema::TYPE_STRING .'(16) NOT NULL DEFAULT \'\' COMMENT \'用户手机号\'',
            'order_booked_begin_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'预约开始时间\'', //TODO 预约单词待定
            'order_booked_end_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'预约结束时间\'',
            'order_booked_count' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'预约服务数量\'',
            'address_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'地址ID\'',
            'order_address'=>Schema::TYPE_STRING.'(255) NOT NULL DEFAULT \'\' COMMENT \'详细地址 包括 联系人 手机号\'',
            'order_unit_money'=>Schema::TYPE_DECIMAL . '(8,0) NOT NULL DEFAULT 0 COMMENT \'订单单位价格\'', //TODO 订单单位价格
            'order_money' => Schema::TYPE_DECIMAL . '(8,0) NOT NULL DEFAULT 0 COMMENT \'订单金额\'',
            'order_booked_worker_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'指定阿姨\'',
            'order_customer_need' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'用户需求\'',

//============================创建完订单后 只能修改一次的部分
            'order_customer_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'用户备注\'',
            'order_cs_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客服备注\'',
            'order_pay_type' => Schema::TYPE_BOOLEAN.'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'支付方式 0线上支付 1现金支付\'', //如果是线上支付 支付成功之后再改状态
            'pay_channel_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'支付渠道id\'',
            'order_pay_channel_name' => Schema::TYPE_STRING.'(128) NOT NULL DEFAULT \'\' COMMENT \'支付渠道名称\'',
            'order_pay_flow_num' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'支付流水号\'',
            'order_pay_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'支付金额\'',
            'order_use_acc_balance' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'使用余额\'',
            'card_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'服务卡ID\'',
            'order_use_card_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'使用服务卡金额\'',
            'coupon_id' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'优惠券ID\'',
            'order_use_coupon_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'使用优惠卷金额\'',
            'promotion_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'促销id\'',
            'order_use_promotion_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'使用促销金额\'',

//===========================订单支付完成后 指派阿姨
            'order_lock_status' => Schema::TYPE_BOOLEAN.'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否锁定 1锁定 0未锁定\'',
            'worker_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨id\'',
            'worker_type_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨职位类型ID\'',
            'order_worker_type_name' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'阿姨职位类型\'',
            'order_worker_send_type' => Schema::TYPE_SMALLINT.'(4) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨接单方式 0未接单 1阿姨抢单 2客服指派 3门店指派\'',
            'shop_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'门店id\'',

//===========================指派工人===》阿姨服务完成后
            'comment_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'评价id\'',
            'order_customer_hidden' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'客户端是否已删除\'',

            'order_pop_pay_money' => Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT 0 COMMENT \'合作方结算金额 负数表示合作方结算规则不规律无法计算该值。\'',
            'invoice_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'发票id\'',
            'checking_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'对账id\'',
            'admin_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'操作人id  0客户操作 1系统操作\'',
            'order_isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否已删除\'',
            'isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'快照是否已删除\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_history}}');
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
