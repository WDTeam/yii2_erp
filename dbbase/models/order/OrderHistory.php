<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order_history}}".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $order_id
 * @property string $order_code
 * @property string $order_batch_code
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property string $order_created_at
 * @property integer $order_isdel
 * @property integer $order_before_status_dict_id
 * @property string $order_before_status_name
 * @property integer $order_status_dict_id
 * @property string $order_status_name
 * @property string $order_status_boss
 * @property string $order_status_customer
 * @property string $order_status_worker
 * @property integer $order_flag_send
 * @property integer $order_flag_urgent
 * @property integer $order_flag_exception
 * @property integer $order_flag_sys_assign
 * @property integer $order_flag_lock
 * @property integer $order_flag_lock_time
 * @property integer $order_flag_worker_sms
 * @property integer $order_flag_worker_jpush
 * @property integer $order_flag_worker_ivr
 * @property integer $order_flag_change_booked_worker
 * @property integer $order_flag_is_checked
 * @property string $order_ip
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_service_item_id
 * @property string $order_service_item_name
 * @property integer $order_channel_type_id
 * @property string $order_channel_type_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_count
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $address_id
 * @property string $order_lat
 * @property string $order_lng
 * @property string $district_id
 * @property string $city_id
 * @property string $order_address
 * @property string $order_booked_worker_id
 * @property string $order_pop_order_code
 * @property string $order_pop_group_buy_code
 * @property string $order_pop_operation_money
 * @property string $order_pop_order_money
 * @property string $order_pop_pay_money
 * @property string $customer_id
 * @property string $order_customer_phone
 * @property string $order_customer_is_vip
 * @property string $order_customer_need
 * @property string $order_customer_memo
 * @property string $comment_id
 * @property string $invoice_id
 * @property integer $order_customer_hidden
 * @property string $pay_channel_type_id
 * @property string $order_pay_channel_type_name
 * @property string $pay_channel_id
 * @property string $order_pay_channel_name
 * @property string $order_pay_flow_num
 * @property string $order_pay_money
 * @property string $order_use_acc_balance
 * @property string $card_id
 * @property string $order_use_card_money
 * @property string $coupon_id
 * @property string $order_coupon_code
 * @property string $order_use_coupon_money
 * @property string $promotion_id
 * @property string $order_use_promotion_money
 * @property string $worker_id
 * @property string $order_worker_phone
 * @property string $order_worker_name
 * @property string $order_worker_memo
 * @property string $worker_type_id
 * @property string $order_worker_type_name
 * @property integer $order_worker_assign_type
 * @property integer $order_worker_assign_time
 * @property string $shop_id
 * @property string $order_worker_shop_name
 * @property string $checking_id
 * @property string $order_cs_memo
 * @property string $order_sys_memo
 * @property string $order_cancel_cause_id
 * @property string $order_cancel_cause_detail
 * @property string $order_cancel_cause_memo
 * @property string $admin_id
 */
class OrderHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'order_id', 'order_parent_id', 'order_is_parent', 'order_created_at', 'order_isdel', 'order_before_status_dict_id',
                'order_status_dict_id', 'order_flag_send', 'order_flag_urgent', 'order_flag_exception', 'order_flag_sys_assign', 'order_flag_lock', 'order_flag_lock_time',
                'order_flag_worker_sms', 'order_flag_worker_jpush', 'order_flag_worker_ivr', 'order_flag_change_booked_worker','order_flag_is_checked','order_cancel_cause_id',
                'order_service_type_id','order_service_item_id', 'order_channel_type_id', 'channel_id', 'order_booked_begin_time', 'order_booked_end_time',
                'address_id', 'district_id', 'city_id', 'order_booked_worker_id', 'customer_id', 'comment_id', 'order_customer_is_vip', 'invoice_id', 'order_customer_hidden',
                 'pay_channel_id','pay_channel_type_id', 'card_id', 'coupon_id', 'promotion_id', 'worker_id', 'worker_type_id', 'order_worker_assign_type',
                'order_worker_assign_time', 'shop_id', 'checking_id', 'admin_id'], 'integer'],
            [['order_id'], 'required'],
            [['order_unit_money', 'order_money', 'order_pop_operation_money', 'order_pop_order_money', 'order_pop_pay_money', 'order_pay_money', 'order_use_acc_balance', 'order_use_card_money',
                'order_use_coupon_money', 'order_use_promotion_money', 'order_booked_count','order_lat','order_lng'], 'number'],
            [['order_code','order_batch_code', 'order_channel_name', 'order_worker_type_name','order_worker_phone','order_worker_name'], 'string', 'max' => 64],
            [['order_before_status_name', 'order_status_name','order_status_boss','order_status_customer','order_status_worker', 'order_service_type_name', 'order_service_item_name',
                'order_channel_type_name', 'order_ip','order_pay_channel_name','order_pay_channel_type_name'], 'string', 'max' => 128],
            [['order_address', 'order_pop_order_code', 'order_pop_group_buy_code', 'order_customer_need', 'order_customer_memo', 'order_pay_flow_num', 'order_cs_memo','order_sys_memo',
                'order_worker_memo', 'order_worker_shop_name','order_cancel_cause_detail','order_cancel_cause_memo','order_code'], 'string', 'max' => 255],
            [['order_customer_phone'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => '快照创建时间',
            'updated_at' => '快照修改时间',
            'order_id' => '编号',
            'order_code' => '订单号',
            'order_batch_code' => '周期订单号',
            'order_parent_id' => '父级id',
            'order_is_parent' => '有无子订单 1有 0无',
            'order_created_at' => '下单时间',
            'order_isdel' => '订单是否已删除',
            'order_before_status_dict_id' => '状态变更前订单状态字典ID',
            'order_before_status_name' => '状态变更前订单状态',
            'order_status_dict_id' => '订单状态字典ID',
            'order_status_name' => '订单状态',
            'order_status_boss' => 'BOSS状态名称',
            'order_status_customer' => '客户端状态名称',
            'order_status_worker' => '阿姨端状态名称',
            'order_flag_send' => '指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => '加急',
            'order_flag_exception' => '异常 1无经纬度',
            'order_flag_sys_assign' => '是否需要系统指派 1是 0否',
            'order_flag_lock' => '是否锁定 1锁定 0未锁定',
            'order_flag_lock_time' => '加锁时间',
            'order_flag_worker_sms' => '是否给阿姨发过短信',
            'order_flag_worker_jpush' => '是否给阿姨发过极光',
            'order_flag_worker_ivr' => '是否给阿姨发过IVR',
            'order_flag_change_booked_worker' => '是否可更换指定阿姨',
            'order_flag_is_checked' => '是否已对账',
            'order_ip' => '下单IP',
            'order_service_type_id' => '订单服务类别ID',
            'order_service_type_name' => '订单服务类别',
            'order_service_item_id' => '订单服务项ID',
            'order_service_item_name' => '订单服务项',
            'order_channel_type_id' => '订单渠道分类id',
            'order_channel_type_name' => '订单渠道分类名称',
            'channel_id' => '订单渠道ID',
            'order_channel_name' => '订单渠道名称',
            'order_unit_money' => '订单单位价格',
            'order_money' => '订单金额',
            'order_booked_count' => '预约服务数量（时长）',
            'order_booked_begin_time' => '预约开始时间',
            'order_booked_end_time' => '预约结束时间',
            'address_id' => '地址ID',
            'order_lat' => '纬度',
            'order_lng' => '经度',
            'district_id' => '商圈ID',
            'city_id' => '城市ID',
            'order_address' => '详细地址 包括 联系人 手机号',
            'order_booked_worker_id' => '指定阿姨',
            'order_pop_order_code' => '第三方订单编号',
            'order_pop_group_buy_code' => '第三方团购码',
            'order_pop_operation_money' => '第三方运营费',
            'order_pop_order_money' => '第三方订单金额',
            'order_pop_pay_money' => '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。',
            'customer_id' => '客户ID',
            'order_customer_phone' => '客户手机号',
            'order_customer_is_vip' => '是否是会员',
            'order_customer_need' => '客户需求',
            'order_customer_memo' => '客户备注',
            'comment_id' => '评价id',
            'invoice_id' => '发票id',
            'order_customer_hidden' => '客户端是否已删除',
            'pay_channel_type_id' => '支付渠道分类id',
            'order_pay_channel_type_name' => '支付渠道分类名称',
            'pay_channel_id' => '支付渠道id',
            'order_pay_channel_name' => '支付渠道名称',
            'order_pay_flow_num' => '支付流水号',
            'order_pay_money' => '支付金额',
            'order_use_acc_balance' => '使用余额',
            'card_id' => '服务卡ID',
            'order_use_card_money' => '使用服务卡金额',
            'coupon_id' => '优惠券ID',
            'order_coupon_code' => '优惠码',
            'order_use_coupon_money' => '使用优惠卷金额',
            'promotion_id' => '促销id',
            'order_use_promotion_money' => '使用促销金额',
            'worker_id' => '工人id',
            'order_worker_phone' => '工人手机号',
            'order_worker_name' => '工人姓名',
            'order_worker_memo' => '工人备注',
            'worker_type_id' => '工人职位类型ID',
            'order_worker_type_name' => '工人职位类型',
            'order_worker_assign_type' => '工人接单方式 0未接单 1工人抢单 2客服指派 3门店指派',
            'order_worker_assign_time' => '接单时间',
            'shop_id' => '工人所属门店id',
            'order_worker_shop_name' => '工人所属门店',
            'checking_id' => '对账id',
            'order_cs_memo' => '客服备注',
            'order_sys_memo' => '系统备注',
            'order_cancel_cause_id' => '取消原因id',
            'order_cancel_cause_detail' => '取消原因',
            'order_cancel_cause_memo' => '取消备注',
            'admin_id' => '操作人id 1系统 2客户 3阿姨 >3后台管理员',
        ];
    }
}
