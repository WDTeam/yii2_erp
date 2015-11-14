<?php

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_worker_non_order_income}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $finance_worker_non_order_income_id
 * @property string $finance_worker_non_order_income_code
 * @property integer $finance_worker_non_order_income_type
 * @property integer $finance_worker_non_order_income_name
 * @property string $finance_worker_non_order_income
 * @property string $finance_worker_non_order_income_des
 * @property integer $finance_worker_non_order_income_complete_time
 * @property integer $finance_worker_non_order_income_starttime
 * @property integer $finance_worker_non_order_income_endtime
 * @property integer $finance_worker_non_order_income_isSettled
 * @property integer $finance_worker_settle_apply_id
 * @property integer $is_softdel
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
            [['worker_id', 'finance_worker_non_order_income_type'], 'required'],
            [['worker_id', 'finance_worker_non_order_income_type', 'finance_worker_non_order_income_starttime', 'finance_worker_non_order_income_endtime', 'finance_worker_non_order_income_isSettled', 'finance_worker_settle_apply_id', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_non_order_income'], 'number'],
            [['finance_worker_non_order_income_des','finance_worker_non_order_income_name'], 'string']
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
            'finance_worker_non_order_income_id' => Yii::t('app', '阿姨收入id，可能是任务Id，也可能是赔偿Id'),
            'finance_worker_non_order_income_code' => Yii::t('app', '阿姨收入编号，可能是任务Id，也可能是赔偿编号'),
            'finance_worker_non_order_income_type' => Yii::t('app', '阿姨收入类型，1,任务；2赔偿'),
             'finance_worker_non_order_income_name' => Yii::t('app', '阿姨收入名称，可能是任务名称，也可能是赔偿名称'),
            'finance_worker_non_order_income' => Yii::t('app', '补贴金额'),
            'finance_worker_non_order_income_des' => Yii::t('app', '补贴规则描述'),
            'finance_worker_non_order_income_complete_time' => Yii::t('app', '收入完成时间'),
            'finance_worker_non_order_income_starttime' => Yii::t('app', '本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值'),
            'finance_worker_non_order_income_endtime' => Yii::t('app', '本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值'),
            'finance_worker_non_order_income_isSettled' => Yii::t('app', '是否已结算，0为未结算，1为已结算'),
            'finance_worker_settle_apply_id' => Yii::t('app', '结算申请Id'),
            'is_softdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '结算时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
