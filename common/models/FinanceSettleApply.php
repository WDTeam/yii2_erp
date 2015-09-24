<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_settle_apply}}".
 *
 * @property integer $id
 * @property integer $worder_id
 * @property string $worder_tel
 * @property integer $worker_type_id
 * @property string $worker_type_name
 * @property string $finance_settle_apply_money
 * @property integer $finance_settle_apply_man_hour
 * @property string $finance_settle_apply_order_money
 * @property string $finance_settle_apply_order_cash_money
 * @property string $finance_settle_apply_non_order_money
 * @property string $finance_settle_apply_far_subsidy
 * @property string $finance_settle_apply_night_subsidy
 * @property string $finance_settle_apply_empty_handed_subsidy
 * @property string $finance_settle_apply_attendance_bonus
 * @property string $finance_settle_apply_no_complaint_bonus
 * @property string $finance_settle_apply_daily_violation_bonus
 * @property string $finance_settle_apply_complaint_reduction
 * @property string $finance_settle_apply_compensate_reduction
 * @property string $finance_settle_apply_task_bonus
 * @property string $finance_settle_apply_small_maintain
 * @property string $finance_settle_apply_channel_bonus
 * @property integer $finance_settle_apply_status
 * @property integer $finance_settle_apply_cycle
 * @property string $finance_settle_apply_reviewer
 * @property integer $finance_settle_apply_starttime
 * @property integer $finance_settle_apply_endtime
 * @property integer $isdel
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinanceSettleApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_settle_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worder_id', 'worder_tel', 'worker_type_id', 'worker_type_name', 'finance_settle_apply_money', 'finance_settle_apply_man_hour', 'finance_settle_apply_order_money', 'finance_settle_apply_non_order_money', 'finance_settle_apply_status', 'finance_settle_apply_cycle'], 'required'],
            [['worder_id', 'worker_type_id', 'finance_settle_apply_man_hour', 'finance_settle_apply_status', 'finance_settle_apply_cycle', 'finance_settle_apply_starttime', 'finance_settle_apply_endtime', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_settle_apply_money', 'finance_settle_apply_order_money', 'finance_settle_apply_order_cash_money', 'finance_settle_apply_non_order_money', 'finance_settle_apply_far_subsidy', 'finance_settle_apply_night_subsidy', 'finance_settle_apply_empty_handed_subsidy', 'finance_settle_apply_attendance_bonus', 'finance_settle_apply_no_complaint_bonus', 'finance_settle_apply_daily_violation_bonus', 'finance_settle_apply_complaint_reduction', 'finance_settle_apply_compensate_reduction', 'finance_settle_apply_task_bonus', 'finance_settle_apply_small_maintain', 'finance_settle_apply_channel_bonus'], 'number'],
            [['worder_tel'], 'string', 'max' => 11],
            [['worker_type_name'], 'string', 'max' => 30],
            [['finance_settle_apply_reviewer'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'worder_id' => Yii::t('app', '阿姨编号'),
            'worder_tel' => Yii::t('app', '阿姨电话'),
            'worker_type_id' => Yii::t('app', '阿姨类型Id'),
            'worker_type_name' => Yii::t('app', '阿姨类型'),
            'finance_settle_apply_money' => Yii::t('app', '申请结算金额'),
            'finance_settle_apply_man_hour' => Yii::t('app', '订单总工时'),
            'finance_settle_apply_order_money' => Yii::t('app', '工时费'),
            'finance_settle_apply_order_cash_money' => Yii::t('app', '收取现金'),
            'finance_settle_apply_non_order_money' => Yii::t('app', '补贴'),
            'finance_settle_apply_far_subsidy' => Yii::t('app', '路补'),
            'finance_settle_apply_night_subsidy' => Yii::t('app', '晚补'),
            'finance_settle_apply_empty_handed_subsidy' => Yii::t('app', '扑空补'),
            'finance_settle_apply_attendance_bonus' => Yii::t('app', '全勤奖'),
            'finance_settle_apply_no_complaint_bonus' => Yii::t('app', '无投诉奖'),
            'finance_settle_apply_daily_violation_bonus' => Yii::t('app', '日常违规扣款'),
            'finance_settle_apply_complaint_reduction' => Yii::t('app', '投诉扣款'),
            'finance_settle_apply_compensate_reduction' => Yii::t('app', '赔偿扣款'),
            'finance_settle_apply_task_bonus' => Yii::t('app', '阿姨任务奖励'),
            'finance_settle_apply_small_maintain' => Yii::t('app', '小保养'),
            'finance_settle_apply_channel_bonus' => Yii::t('app', '渠道奖励'),
            'finance_settle_apply_status' => Yii::t('app', '申请结算状态'),
            'finance_settle_apply_cycle' => Yii::t('app', '申请周期'),
            'finance_settle_apply_reviewer' => Yii::t('app', '审核人'),
            'finance_settle_apply_starttime' => Yii::t('app', '结算开始时间'),
            'finance_settle_apply_endtime' => Yii::t('app', '结算结束时间'),
            'isdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '审核时间'),
            'created_at' => Yii::t('app', '申请时间'),
        ];
    }
}
