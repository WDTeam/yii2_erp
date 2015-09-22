<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_pop_order}}".
 *
 * @property integer $id
 * @property string $finance_pop_order_number
 * @property integer $finance_order_channel_id
 * @property integer $finance_order_channel_title
 * @property integer $finance_pay_channel_id
 * @property integer $finance_pay_channel_title
 * @property string $finance_pop_order_customer_tel
 * @property integer $finance_pop_order_worker_uid
 * @property integer $finance_pop_order_booked_time
 * @property integer $finance_pop_order_booked_counttime
 * @property string $finance_pop_order_sum_money
 * @property string $finance_pop_order_coupon_count
 * @property integer $finance_pop_order_coupon_id
 * @property string $finance_pop_order_order2
 * @property string $finance_pop_order_channel_order
 * @property integer $finance_pop_order_order_type
 * @property integer $finance_pop_order_status
 * @property integer $finance_pop_order_finance_isok
 * @property string $finance_pop_order_discount_pay
 * @property string $finance_pop_order_reality_pay
 * @property integer $finance_pop_order_order_time
 * @property integer $finance_pop_order_pay_time
 * @property integer $finance_pop_order_pay_status
 * @property string $finance_pop_order_pay_title
 * @property integer $finance_pop_order_check_id
 * @property integer $finance_pop_order_finance_time
 * @property integer $create_time
 * @property integer $is_del
 */
class FinancePopOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_pop_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_order_channel_id', 'finance_order_channel_title', 'finance_pay_channel_id', 'finance_pay_channel_title'], 'required'],
            [['finance_order_channel_id', 'finance_order_channel_title', 'finance_pay_channel_id', 'finance_pay_channel_title', 'finance_pop_order_worker_uid', 'finance_pop_order_booked_time', 'finance_pop_order_booked_counttime', 'finance_pop_order_coupon_id', 'finance_pop_order_order_type', 'finance_pop_order_status', 'finance_pop_order_finance_isok', 'finance_pop_order_order_time', 'finance_pop_order_pay_time', 'finance_pop_order_pay_status', 'finance_pop_order_check_id', 'finance_pop_order_finance_time', 'create_time', 'is_del'], 'integer'],
            [['finance_pop_order_sum_money', 'finance_pop_order_coupon_count', 'finance_pop_order_discount_pay', 'finance_pop_order_reality_pay'], 'number'],
            [['finance_pop_order_number', 'finance_pop_order_order2', 'finance_pop_order_channel_order'], 'string', 'max' => 40],
            [['finance_pop_order_customer_tel'], 'string', 'max' => 20],
            [['finance_pop_order_pay_title'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'finance_pop_order_number' => Yii::t('app', '第三方订单号'),
            'finance_order_channel_id' => Yii::t('app', '下单渠道(对应finance_order_channel)'),
            'finance_order_channel_title' => Yii::t('app', '下单渠道名称(对应order_channel)'),
            'finance_pay_channel_id' => Yii::t('app', '支付渠道(对应pay_channel)'),
            'finance_pay_channel_title' => Yii::t('app', '支付渠道名称(对应pay_channel)'),
            'finance_pop_order_customer_tel' => Yii::t('app', '用户电话'),
            'finance_pop_order_worker_uid' => Yii::t('app', '服务阿姨'),
            'finance_pop_order_booked_time' => Yii::t('app', '预约开始时间'),
            'finance_pop_order_booked_counttime' => Yii::t('app', '预约服务时长(按分钟记录)'),
            'finance_pop_order_sum_money' => Yii::t('app', '总金额'),
            'finance_pop_order_coupon_count' => Yii::t('app', '优惠卷金额'),
            'finance_pop_order_coupon_id' => Yii::t('app', '优惠卷id'),
            'finance_pop_order_order2' => Yii::t('app', '子订单号'),
            'finance_pop_order_channel_order' => Yii::t('app', '获取渠道唯一订单号'),
            'finance_pop_order_order_type' => Yii::t('app', '订单类型'),
            'finance_pop_order_status' => Yii::t('app', '支付状态'),
            'finance_pop_order_finance_isok' => Yii::t('app', '财务确定'),
            'finance_pop_order_discount_pay' => Yii::t('app', '优惠金额'),
            'finance_pop_order_reality_pay' => Yii::t('app', '实际收款'),
            'finance_pop_order_order_time' => Yii::t('app', '下单时间'),
            'finance_pop_order_pay_time' => Yii::t('app', '支付时间'),
            'finance_pop_order_pay_status' => Yii::t('app', '1 对账成功 2 财务确定ok  3 财务确定on 4 财务未处理'),
            'finance_pop_order_pay_title' => Yii::t('app', '状态 描述'),
            'finance_pop_order_check_id' => Yii::t('app', '操作人id'),
            'finance_pop_order_finance_time' => Yii::t('app', '财务对账提交时间'),
            'create_time' => Yii::t('app', '增加时间'),
            'is_del' => Yii::t('app', '0 正常 1 删除'),
        ];
    }
}