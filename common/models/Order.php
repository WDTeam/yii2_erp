<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $order_code
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property string $created_at
 * @property string $updated_at
 * @property integer $order_before_status_dict_id
 * @property string $order_before_status_name
 * @property integer $order_status_dict_id
 * @property string $order_status_name
 * @property integer $order_flag_send
 * @property integer $order_flag_urgent
 * @property integer $order_flag_exception
 * @property integer $order_flag_sys_assign
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_pop_order_code
 * @property string $order_pop_group_buy_code
 * @property string $order_pop_operation_money
 * @property string $order_pop_order_money
 * @property string $customer_id
 * @property integer $order_ip
 * @property string $order_customer_phone
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $order_booked_count
 * @property string $address_id
 * @property string $order_address
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_worker_id
 * @property string $order_customer_need
 * @property string $order_customer_memo
 * @property string $order_cs_memo
 * @property integer $order_pay_type
 * @property string $pay_channel_id
 * @property string $order_pay_channel_name
 * @property string $order_pay_flow_num
 * @property string $order_pay_money
 * @property string $order_use_acc_balance
 * @property string $card_id
 * @property string $order_use_card_money
 * @property string $coupon_id
 * @property string $order_use_coupon_money
 * @property string $promotion_id
 * @property string $order_use_promotion_money
 * @property integer $order_lock_status
 * @property string $worker_id
 * @property string $worker_type_id
 * @property string $order_worker_type_name
 * @property integer $order_worker_assign_type
 * @property string $shop_id
 * @property string $comment_id
 * @property integer $order_customer_hidden
 * @property string $order_pop_pay_money
 * @property string $invoice_id
 * @property string $checking_id
 * @property string $admin_id
 * @property integer $isdel
 */
class Order extends \common\models\ActiveRecord
{
    public $order_booked_date;
    public $order_booked_time_range;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_code'],'unique'],
            [['order_code','order_service_type_id','customer_id', 'order_ip', 'address_id','order_unit_money', 'order_money','order_before_status_name', 'order_status_name', 'order_service_type_name','channel_id',
                'order_src_id', 'order_src_name', 'order_address', 'order_customer_phone','order_booked_begin_time', 'order_booked_end_time','order_booked_date','order_booked_time_range'],'required'],

            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'order_before_status_dict_id', 'order_status_dict_id', 'order_flag_send', 'order_flag_urgent', 'order_flag_exception', 'order_flag_sys_assign', 'order_service_type_id', 'order_src_id', 'channel_id', 'customer_id', 'order_ip', 'order_booked_begin_time', 'order_booked_end_time', 'order_booked_count', 'address_id', 'order_booked_worker_id', 'order_pay_type', 'pay_channel_id', 'card_id', 'coupon_id', 'promotion_id', 'order_lock_status', 'worker_id', 'worker_type_id', 'order_worker_assign_type', 'shop_id', 'comment_id', 'order_customer_hidden', 'invoice_id', 'checking_id', 'admin_id', 'isdel'], 'integer'],
            [['order_pop_operation_money', 'order_pop_order_money', 'order_unit_money', 'order_money', 'order_pay_money', 'order_use_acc_balance', 'order_use_card_money', 'order_use_coupon_money', 'order_use_promotion_money', 'order_pop_pay_money'], 'number'],
            [['order_code', 'order_channel_name', 'order_worker_type_name'], 'string', 'max' => 64],
            [['order_before_status_name', 'order_status_name', 'order_service_type_name', 'order_src_name', 'order_pay_channel_name'], 'string', 'max' => 128],
            [['order_pop_order_code', 'order_pop_group_buy_code', 'order_address', 'order_customer_need', 'order_customer_memo', 'order_cs_memo', 'order_pay_flow_num'], 'string', 'max' => 255],
            [['order_customer_phone'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'order_code' => '订单号',
            'order_parent_id' => '父级id',
            'order_is_parent' => '有无子订单 1有 0无',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'order_before_status_dict_id' => '状态变更前订单状态字典ID',
            'order_before_status_name' => '状态变更前订单状态',
            'order_status_dict_id' => '订单状态字典ID',
            'order_status_name' => '订单状态',
            'order_flag_send' => '指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => '加急',
            'order_flag_exception' => '异常 1无经纬度',
            'order_flag_sys_assign' => '是否需要系统指派 1是 0否',
            'order_service_type_id' => '订单服务类别ID',
            'order_service_type_name' => '订单服务类别',
            'order_src_id' => '订单来源，订单入口id',
            'order_src_name' => '订单来源，订单入口名称',
            'channel_id' => '订单渠道ID',
            'order_channel_name' => '订单渠道名称',
            'order_pop_order_code' => '第三方订单编号',
            'order_pop_group_buy_code' => '第三方团购码',
            'order_pop_operation_money' => '第三方运营费',
            'order_pop_order_money' => '第三方订单金额',
            'customer_id' => '用户编号',
            'order_ip' => '下单IP',
            'order_customer_phone' => '用户手机号',
            'order_booked_begin_time' => '预约开始时间',
            'order_booked_end_time' => '预约结束时间',
            'order_booked_count' => '预约服务数量',
            'address_id' => '地址ID',
            'order_address' => '详细地址 包括 联系人 手机号',
            'order_unit_money' => '订单单位价格',
            'order_money' => '订单金额',
            'order_booked_worker_id' => '指定阿姨',
            'order_customer_need' => '用户需求',
            'order_customer_memo' => '用户备注',
            'order_cs_memo' => '客服备注',
            'order_pay_type' => '支付方式 0未支付 1现金支付 2线上支付 3第三方预付 ',
            'pay_channel_id' => '支付渠道id',
            'order_pay_channel_name' => '支付渠道名称',
            'order_pay_flow_num' => '支付流水号',
            'order_pay_money' => '支付金额',
            'order_use_acc_balance' => '使用余额',
            'card_id' => '服务卡ID',
            'order_use_card_money' => '使用服务卡金额',
            'coupon_id' => '优惠券ID',
            'order_use_coupon_money' => '使用优惠卷金额',
            'promotion_id' => '促销id',
            'order_use_promotion_money' => '使用促销金额',
            'order_lock_status' => '是否锁定 1锁定 0未锁定',
            'worker_id' => '阿姨id',
            'worker_type_id' => '阿姨职位类型ID',
            'order_worker_type_name' => '阿姨职位类型',
            'order_worker_assign_type' => '阿姨接单方式 0未接单 1阿姨抢单 2客服指派 3门店指派',
            'shop_id' => '门店id',
            'comment_id' => '评价id',
            'order_customer_hidden' => '客户端是否已删除',
            'order_pop_pay_money' => '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。',
            'invoice_id' => '发票id',
            'checking_id' => '对账id',
            'admin_id' => '操作人id  0客户操作 1系统操作',
            'isdel' => '是否已删除',
            'order_booked_date' => '预约服务日期',
            'order_booked_time_range' => '预约服务时间',
        ];
    }

    public function init()
    {
        $class = get_class($this);
        if(!in_array($class,['core\models\order\Order','core\models\order\OrderSearch','boss\models\AutoOrderSerach','boss\models\ManualOrderSerach'])){
            echo '非法调用！';
            exit(0);
        }
    }
}
