<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153203_create_table_order_history extends Migration
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
            'order_batch_code' => Schema::TYPE_STRING.'(64) DEFAULT \'\' COMMENT \'周期订单号\'',
            'order_parent_id' => Schema::TYPE_BIGINT.'(20) unsigned  DEFAULT 0 COMMENT \'父级id\'',
            'order_is_parent' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'有无子订单 1有 0无\'',
            'order_created_at' => Schema::TYPE_INTEGER.'(11) unsigned  DEFAULT 0 COMMENT \'下单时间\'',
            'order_isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'订单是否已删除\'',
            'order_ver' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 1 COMMENT \'乐观锁\'',
//==============================订单状态
            'order_before_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned  DEFAULT 0 COMMENT \'状态变更前订单状态字典ID\'',
            'order_before_status_name' => Schema::TYPE_STRING . '(128)   DEFAULT \'\' COMMENT \'状态变更前订单状态\'',
            'order_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned  DEFAULT 0 COMMENT \'订单状态字典ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(128)   DEFAULT \'\' COMMENT \'订单状态\'',
            'order_status_boss' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'BOOS状态名称\'',
            'order_status_customer' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'客户端状态名称\'',
            'order_status_worker' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'阿姨端状态名称\'',
//==============================订单标记
            'order_flag_send' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了\'',
            'order_flag_urgent' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'加急\'',
            'order_flag_exception' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'异常 1无经纬度\'',
            'order_flag_sys_assign' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 1 COMMENT \'是否需要系统指派 1是 0否\'',
            'order_flag_lock' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'是否锁定 1锁定 0未锁定\'',
            'order_flag_lock_time' => Schema::TYPE_INTEGER.'(11) unsigned  DEFAULT 0 COMMENT \'加锁时间\'',
            'order_flag_worker_sms' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'是否给阿姨发了短信\'',
            'order_flag_worker_jpush' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'是否给阿姨发了极光推送\'',
            'order_flag_worker_ivr' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'是否给阿姨发了IVR\'',
            'order_flag_change_booked_worker' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'是否可换指定阿姨\'',
            'order_flag_is_checked' => Schema::TYPE_BOOLEAN.'(1) unsigned  DEFAULT 0 COMMENT \'是否已对账\'',

//===============================下单信息
            'order_ip' => Schema::TYPE_STRING.'(128) NOT NULL DEFAULT \'\' COMMENT \'下单IP\'',
            'order_service_type_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'订单服务类别ID\'',
            'order_service_type_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单服务类别\'',
            'order_service_item_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'订单服务项ID\'',
            'order_service_item_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单服务项\'',
            'order_src_id' => Schema::TYPE_SMALLINT . '(4) unsigned DEFAULT 0 COMMENT \'订单来源，订单入口id\'',
            'order_src_name' => Schema::TYPE_STRING . '(128)  DEFAULT \'\' COMMENT \'订单来源，订单入口名称\'',
            'channel_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'订单渠道ID\'',
            'order_channel_name' => Schema::TYPE_STRING.'(64)  DEFAULT \'\' COMMENT \'订单渠道名称\'',
//===============================服务信息
            'order_unit_money'=>Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT 0 COMMENT \'订单单位价格\'',
            'order_money' => Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT 0 COMMENT \'订单金额\'',
            'order_booked_count' => Schema::TYPE_DECIMAL.'(8,1) NOT NULL DEFAULT  \'0.0\' COMMENT \'预约服务数量（时长）\'',
            'order_booked_begin_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'预约开始时间\'',
            'order_booked_end_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'预约结束时间\'',
            'city_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'城市ID\'',
            'district_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'商圈ID\'',
            'address_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'地址ID\'',
            'order_address'=>Schema::TYPE_STRING.'(255) NOT NULL DEFAULT \'\' COMMENT \'详细地址 包括 联系人 手机号\'',
            'order_lat' => Schema::TYPE_DOUBLE.' DEFAULT NULL COMMENT \'纬度\'',
            'order_lng' => Schema::TYPE_DOUBLE.' DEFAULT NULL COMMENT \'经度\'',
            'order_booked_worker_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'指定阿姨\'',
//================================第三方信息
            'order_pop_order_code' => Schema::TYPE_STRING . '(255)  DEFAULT \'\' COMMENT \'第三方订单编号\'',
            'order_pop_group_buy_code' =>  Schema::TYPE_STRING . '(255)  DEFAULT \'\' COMMENT \'第三方团购码\'',
            'order_pop_operation_money' =>  Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'第三方运营费\'',
            'order_pop_order_money' =>  Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'第三方订单金额\'',
            'order_pop_pay_money' => Schema::TYPE_DECIMAL . '(8,2)  DEFAULT 0 COMMENT \'合作方结算金额 负数表示合作方结算规则不规律无法计算该值。\'',
//================================客户信息
            'customer_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'客户ID\'',
            'order_customer_phone' => Schema::TYPE_STRING .'(16) NOT NULL DEFAULT \'\' COMMENT \'客户手机号\'',
            'order_customer_is_vip' => Schema::TYPE_BOOLEAN .'(1) unsigned  DEFAULT 0 COMMENT \'是否是vip\'',
            'order_customer_need' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客户需求\'',
            'order_customer_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客户备注\'',
            'comment_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'评价id\'',
            'invoice_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'发票id\'',
            'order_customer_hidden' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'客户端是否已删除\'',

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
            'order_use_coupon_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'使用优惠卷金额\'',
            'promotion_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'促销id\'',
            'order_use_promotion_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'使用促销金额\'',

//===========================工人信息
            'worker_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'工人id\'',
            'order_worker_phone' => Schema::TYPE_STRING.'(64) DEFAULT \'\' COMMENT \'工人手机号\'',
            'order_worker_name' => Schema::TYPE_STRING.'(64) DEFAULT \'\' COMMENT \'工人姓名\'',
            'worker_type_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'工人职位类型ID\'',
            'order_worker_type_name' => Schema::TYPE_STRING.'(64)  DEFAULT \'\' COMMENT \'工人职位类型\'',
            'order_worker_assign_type' => Schema::TYPE_SMALLINT.'(4) unsigned  DEFAULT 0 COMMENT \'工人接单方式 0未接单 1工人抢单 2客服指派 3门店指派\'',
            'shop_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'工人所属门店id\'',
            'order_worker_shop_name' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'工人所属门店名称\'',
            'order_worker_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'阿姨备注\'',

//===========================对账信息
            'checking_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'对账id\'',
//===========================其他信息
            'order_cs_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客服备注\'',
            'order_sys_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'系统备注\'',
            'order_cancel_cause_id' => Schema::TYPE_INTEGER.'(11) unsigned  DEFAULT 0 COMMENT \'取消具体原因id\'',
            'order_cancel_cause_detail' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'取消具体原因\'',
            'order_cancel_cause_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'取消原因备注\'',
            'admin_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'操作人id 1系统 2客户 3阿姨 >3后台管理员\'',


        ], $tableOptions);

        $this->createIndex('idx-order_history-order_id', '{{%order_history}}', 'order_id');
    }

    public function down()
    {
        $this->dropTable('{{%order_history}}');

        return true;
    }


}
