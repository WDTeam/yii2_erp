<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_worker_order_income}}".
 *
 * @property integer $id
 * @property integer $worder_id
 * @property integer $order_id
 * @property integer $finance_worker_order_income_type
 * @property string $finance_worker_order_income
 * @property integer $order_booked_count
 * @property integer $isSettled
 * @property integer $isdel
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
            [['worder_id', 'finance_worker_order_income_type'], 'required'],
            [['worder_id', 'order_id', 'finance_worker_order_income_type', 'order_booked_count', 'isSettled', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_order_income'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'worder_id' => Yii::t('app', '阿姨id'),
            'order_id' => Yii::t('app', '订单id'),
            'finance_worker_order_income_type' => Yii::t('app', '阿姨收入类型，1订单收入（线上支付），2订单收入（现金），3路补，4晚补，5扑空补助,6渠道奖励'),
            'finance_worker_order_income' => Yii::t('app', '阿姨收入'),
            'order_booked_count' => Yii::t('app', '预约服务数量，即工时'),
            'isSettled' => Yii::t('app', '是否已结算，0为未结算，1为已结算'),
            'isdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '结算时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
