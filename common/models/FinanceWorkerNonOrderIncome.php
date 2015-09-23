<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_worker_non_order_income}}".
 *
 * @property integer $id
 * @property integer $worder_id
 * @property integer $finance_worker_non_order_income_type
 * @property string $finance_worker_non_order_income
 * @property string $finance_worker_non_order_income_des
 * @property string $finance_worker_non_order_income_duration
 * @property integer $finance_worker_non_order_income_isSettled
 * @property integer $isdel
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinanceWorkerNonOrderIncome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_worker_non_order_income}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worder_id', 'finance_worker_non_order_income_type'], 'required'],
            [['worder_id', 'finance_worker_non_order_income_type', 'finance_worker_non_order_income_isSettled', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_non_order_income'], 'number'],
            [['finance_worker_non_order_income_des'], 'string'],
            [['finance_worker_non_order_income_duration'], 'string', 'max' => 20]
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
            'finance_worker_non_order_income_type' => Yii::t('app', '阿姨收入类型，1办卡提成，2推荐服务提成，3全勤奖，4无投诉奖，5日常违规扣款，6投诉处罚扣款，7赔偿扣款,8阿姨任务奖励,9小保养'),
            'finance_worker_non_order_income' => Yii::t('app', '阿姨收入'),
            'finance_worker_non_order_income_des' => Yii::t('app', '阿姨收入描述'),
            'finance_worker_non_order_income_duration' => Yii::t('app', '阿姨收入的时段，例如：2015年10月份，则为201510'),
            'finance_worker_non_order_income_isSettled' => Yii::t('app', '是否已结算，0为未结算，1为已结算'),
            'isdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '结算时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
