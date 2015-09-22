<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ejj_order".
 *
 * @property string $id
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property string $created_at
 * @property string $updated_at
 * @property integer $order_before_status_dict_id
 * @property string $order_before_status_name
 * @property integer $order_status_dict_id
 * @property string $order_status_name
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_channel_order_num
 * @property string $customer_id
 * @property string $order_customer_phone
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $order_booked_count
 * @property string $address_id
 * @property string $order_address
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
 * @property string $worker_id
 * @property string $worker_type_id
 * @property string $order_worker_type_name
 * @property integer $order_worker_distri_type
 * @property integer $order_lock_status
 * @property string $comment_id
 * @property string $order_worker_bonus_detail
 * @property string $order_worker_bonus_money
 * @property string $order_pop_pay_money
 * @property string $invoice_id
 * @property string $checking_id
 * @property string $shop_id
 * @property string $admin_id
 * @property integer $isdel
 */
class Order extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'order_before_status_dict_id', 'order_status_dict_id', 'order_service_type_id', 'order_src_id', 'channel_id', 'customer_id', 'order_booked_begin_time', 'order_booked_end_time', 'order_booked_count', 'address_id', 'order_booked_worker_id', 'order_pay_type', 'pay_channel_id', 'card_id', 'coupon_id', 'promotion_id', 'worker_id', 'worker_type_id', 'order_worker_distri_type', 'order_lock_status', 'comment_id', 'invoice_id', 'checking_id', 'shop_id', 'admin_id', 'isdel'], 'integer'],
            [['order_money', 'order_pay_money', 'order_use_acc_balance', 'order_use_card_money', 'order_use_coupon_money', 'order_use_promotion_money', 'order_worker_bonus_money', 'order_pop_pay_money'], 'number'],
            [['order_worker_bonus_detail'], 'required'],
            [['order_worker_bonus_detail'], 'string'],
            [['order_before_status_name', 'order_status_name', 'order_service_type_name', 'order_src_name', 'order_pay_channel_name'], 'string', 'max' => 128],
            [['order_channel_name', 'order_worker_type_name'], 'string', 'max' => 64],
            [['order_channel_order_num', 'order_address', 'order_customer_need', 'order_customer_memo', 'order_cs_memo', 'order_pay_flow_num'], 'string', 'max' => 255],
            [['order_customer_phone'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'order_parent_id' => Yii::t('app', '父级id'),
            'order_is_parent' => Yii::t('app', '有无子订单 1有 0无'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'order_before_status_dict_id' => Yii::t('app', '状态变更前订单状态字典ID'),
            'order_before_status_name' => Yii::t('app', '状态变更前订单状态'),
            'order_status_dict_id' => Yii::t('app', '订单状态字典ID'),
            'order_status_name' => Yii::t('app', '订单状态'),
            'order_service_type_id' => Yii::t('app', '订单服务类别ID'),
            'order_service_type_name' => Yii::t('app', '订单服务类别'),
            'order_src_id' => Yii::t('app', '订单来源，订单入口id'),
            'order_src_name' => Yii::t('app', '订单来源，订单入口名称'),
            'channel_id' => Yii::t('app', '下单渠道ID'),
            'order_channel_name' => Yii::t('app', '下单渠道名称'),
            'order_channel_order_num' => Yii::t('app', '渠道订单编号'),
            'customer_id' => Yii::t('app', '用户编号'),
            'order_customer_phone' => Yii::t('app', '用户手机号'),
            'order_booked_begin_time' => Yii::t('app', '预约开始时间'),
            'order_booked_end_time' => Yii::t('app', '预约结束时间'),
            'order_booked_count' => Yii::t('app', '预约服务数量'),
            'address_id' => Yii::t('app', '地址ID'),
            'order_address' => Yii::t('app', '详细地址 包括 联系人 手机号'),
            'order_money' => Yii::t('app', '订单金额'),
            'order_booked_worker_id' => Yii::t('app', '指定阿姨'),
            'order_customer_need' => Yii::t('app', '用户需求'),
            'order_customer_memo' => Yii::t('app', '用户备注'),
            'order_cs_memo' => Yii::t('app', '客服备注'),
            'order_pay_type' => Yii::t('app', '支付方式 0线上支付 1现金支付'),
            'pay_channel_id' => Yii::t('app', '支付渠道id'),
            'order_pay_channel_name' => Yii::t('app', '支付渠道名称'),
            'order_pay_flow_num' => Yii::t('app', '支付流水号'),
            'order_pay_money' => Yii::t('app', '支付金额'),
            'order_use_acc_balance' => Yii::t('app', '使用余额'),
            'card_id' => Yii::t('app', '服务卡ID'),
            'order_use_card_money' => Yii::t('app', '使用服务卡金额'),
            'coupon_id' => Yii::t('app', '优惠券ID'),
            'order_use_coupon_money' => Yii::t('app', '使用优惠卷金额'),
            'promotion_id' => Yii::t('app', '促销id'),
            'order_use_promotion_money' => Yii::t('app', '使用促销金额'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'worker_type_id' => Yii::t('app', '阿姨职位类型ID'),
            'order_worker_type_name' => Yii::t('app', '阿姨职位类型'),
            'order_worker_distri_type' => Yii::t('app', '阿姨接单方式 0未接单 1阿姨抢单 2客服指派 3门店指派'),
            'order_lock_status' => Yii::t('app', '是否锁定 1锁定 0未锁定'),
            'comment_id' => Yii::t('app', '评价id'),
            'order_worker_bonus_detail' => Yii::t('app', '补贴明细'),
            'order_worker_bonus_money' => Yii::t('app', '补贴金额'),
            'order_pop_pay_money' => Yii::t('app', '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。'),
            'invoice_id' => Yii::t('app', '发票id'),
            'checking_id' => Yii::t('app', '对账id'),
            'shop_id' => Yii::t('app', '门店id'),
            'admin_id' => Yii::t('app', '操作人id'),
            'isdel' => Yii::t('app', '是否已删除'),
        ];
    }
}
