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
 * @property integer $finance_worker_non_order_income_starttime
 * @property integer $finance_worker_non_order_income_endtime
 * @property integer $finance_worker_non_order_income_isSettled
 * @property integer $finance_settle_apply_id
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
            [['worder_id', 'finance_worker_non_order_income_type', 'finance_worker_non_order_income_starttime', 'finance_worker_non_order_income_endtime', 'finance_worker_non_order_income_isSettled', 'finance_settle_apply_id', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_non_order_income'], 'number'],
            [['finance_worker_non_order_income_des','finance_worker_non_order_income_type_des'], 'string']
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
             'finance_worker_non_order_income_type_des' => Yii::t('app', '阿姨收入类型描述'),
            'finance_worker_non_order_income' => Yii::t('app', '阿姨收入'),
            'finance_worker_non_order_income_des' => Yii::t('app', '阿姨收入描述'),
            'finance_worker_non_order_income_starttime' => Yii::t('app', '本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值'),
            'finance_worker_non_order_income_endtime' => Yii::t('app', '本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值'),
            'finance_worker_non_order_income_isSettled' => Yii::t('app', '是否已结算，0为未结算，1为已结算'),
            'finance_settle_apply_id' => Yii::t('app', '结算申请Id'),
            'isdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '结算时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
