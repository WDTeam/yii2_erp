<?php

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_worker_order_income}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $order_id
 * @property string $order_code
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $channel_id
 * @property string $order_channel_name
 * @property integer $order_pay_type_id
 * @property string $order_pay_type_des
 * @property integer $order_booked_begin_time
 * @property integer $order_booked_count
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $finance_worker_order_income_discount_amount
 * @property string $order_pay_money
 * @property string $finance_worker_order_income_money
 * @property integer $isSettled
 * @property integer $finance_worker_order_income_starttime
 * @property integer $finance_worker_order_income_endtime
 * @property integer $finance_worker_settle_apply_id
 * @property integer $is_softdel
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinanceWorkerOrderIncome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_worker_order_income}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'order_id', 'order_service_type_id', 'channel_id', 'order_pay_type_id'], 'required'],
            [['worker_id', 'order_id', 'order_service_type_id', 'channel_id', 'order_pay_type_id', 'order_booked_begin_time', 'order_booked_count', 'isSettled', 'finance_worker_order_income_starttime', 'finance_worker_order_income_endtime', 'finance_worker_settle_apply_id', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
            [['order_unit_money', 'order_money', 'finance_worker_order_income_discount_amount', 'order_pay_money', 'finance_worker_order_income_money'], 'number'],
            [['order_service_type_name', 'order_channel_name', 'order_pay_type_des','order_code'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'order_id' => Yii::t('app', '订单id'),
            'order_code' => Yii::t('app', '订单编号'),
            'order_service_type_id' => Yii::t('app', '服务类型id'),
            'order_service_type_name' => Yii::t('app', '服务类型描述'),
            'channel_id' => Yii::t('app', '订单渠道ID'),
            'order_channel_name' => Yii::t('app', '订单渠道名称'),
            'order_pay_type_id' => Yii::t('app', '阿姨收入类型，1现金支付 2线上支付 3第三方预付 '),
            'order_pay_type_des' => Yii::t('app', '阿姨收入类型描述，1现金支付 2线上支付 3第三方预付 '),
            'order_booked_begin_time' => Yii::t('app', '订单预约开始时间'),
            'order_booked_count' => Yii::t('app', '预约服务数量，即工时'),
            'order_unit_money' => Yii::t('app', '订单单位价格'),
            'order_money' => Yii::t('app', '订单金额'),
            'finance_worker_order_income_discount_amount' => Yii::t('app', '优惠金额（元）'),
            'order_pay_money' => Yii::t('app', '用户支付金额（元）'),
            'finance_worker_order_income_money' => Yii::t('app', '阿姨结算金额（元）'),
            'isSettled' => Yii::t('app', '是否已结算，0为未结算，1为已结算'),
            'finance_worker_order_income_starttime' => Yii::t('app', '本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值'),
            'finance_worker_order_income_endtime' => Yii::t('app', '本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值'),
            'finance_worker_settle_apply_id' => Yii::t('app', '结算申请Id'),
            'is_softdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '结算时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
