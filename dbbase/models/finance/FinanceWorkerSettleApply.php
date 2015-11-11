<?php

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_worker_settle_apply}}".
 *
 * @property integer $id
 * @property string finance_worker_settle_apply_code
 * @property integer $worker_id
 * @property string $worker_tel
 * @property integer $worker_type_id
 * @property string $worker_type_name
 * @property integer $worker_identity_id
 * @property string $worker_identity_name
 * @property integer $shop_id
 * @property string $shop_name
 * @property integer $shop_manager_id
 * @property string $shop_manager_name
 * @property integer $finance_worker_settle_apply_order_count
 * @property integer $finance_worker_settle_apply_man_hour
 * @property string $finance_worker_settle_apply_order_money
 * @property integer $finance_worker_settle_apply_task_count
 * @property string $finance_worker_settle_apply_task_money
 * @property integer $finance_worker_settle_apply_base_salary
 * @property string $finance_worker_settle_apply_base_salary_subsidy
 * @property string $finance_worker_settle_apply_money_except_deduct_cash
 * @property string $finance_worker_settle_apply_money_deduction
 * @property string $finance_worker_settle_apply_money_except_cash
 * @property string $finance_worker_settle_apply_order_cash_count
 * @property string $finance_worker_settle_apply_order_cash_money
 * @property string $finance_worker_settle_apply_money
 * @property string $finance_worker_settle_apply_order_noncash_count
 * @property string $finance_worker_settle_apply_order_money_except_cash
 * @property integer $finance_worker_settle_apply_status
 * @property integer $finance_worker_settle_apply_cycle
 * @property string $finance_worker_settle_apply_cycle_des
 * @property string $finance_worker_settle_apply_reviewer
 * @property integer $finance_worker_settle_apply_starttime
 * @property integer $finance_worker_settle_apply_endtime 
 * @property integer $isManagementFeeDone
 * @property integer $finance_shop_settle_apply_id
 * @property integer $isWorkerConfirmed
 * @property integer $is_softdel
 * @property integer $updated_at
 * @property integer $created_at
 * @property text $comment
 */
class FinanceWorkerSettleApply extends \yii\db\ActiveRecord
{
    const FINANCE_SETTLE_APPLY_CYCLE_WEEK=1;//周结
    
    const FINANCE_SETTLE_APPLY_CYCLE_WEEK_DES="周结";//周结描述
    
    const FINANCE_SETTLE_APPLY_CYCLE_MONTH=2;//月结
    
    const FINANCE_SETTLE_APPLY_CYCLE_MONTH_DES="月结";//月结描述
    
    const FINANCE_SETTLE_APPLY_STATUS_INIT = 0;//提交结算申请
    
    const FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED = 1;//业务部门审核通过
    
    const FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED = -1;//业务部门审核不通过
    
    const FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED = 2;//财务审核通过；
    
    const FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED = -2;//财务审核不通过；
    
    const FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED = 3;//财务确认打款；
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_worker_settle_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['worker_id', 'worker_tel', 'worker_type_id', 'worker_type_name', 'finance_worker_settle_apply_man_hour', 'finance_worker_settle_apply_order_money', 'finance_worker_settle_apply_order_money_except_cash', 'finance_worker_settle_apply_money', 'finance_worker_settle_apply_status', 'finance_worker_settle_apply_cycle', 'finance_worker_settle_apply_cycle_des'], 'required'],
            [['worker_id', 'worker_type_id', 'finance_worker_settle_apply_man_hour', 'finance_worker_settle_apply_status', 'finance_worker_settle_apply_cycle', 'finance_worker_settle_apply_starttime', 'finance_worker_settle_apply_endtime', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_settle_apply_order_money', 'finance_worker_settle_apply_order_cash_money', 'finance_worker_settle_apply_order_money_except_cash',  'finance_worker_settle_apply_money','finance_worker_settle_apply_task_count','finance_worker_settle_apply_task_money','finance_worker_settle_apply_base_salary','finance_worker_settle_apply_base_salary_subsidy','finance_worker_settle_apply_money_except_deduct_cash','finance_worker_settle_apply_money_deduction','finance_worker_settle_apply_money_except_cash',], 'number'],
            [['finance_worker_settle_apply_cycle_des'], 'string'],
            [['worker_tel'], 'string', 'max' => 11],
            [['worker_type_name'], 'string', 'max' => 30],
            [['finance_worker_settle_apply_reviewer'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'finance_worker_settle_apply_code' => Yii::t('app', '结算编号'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'worker_name' => Yii::t('app', '阿姨姓名'),
            'worker_tel' => Yii::t('app', '阿姨电话'),
            'worker_type_id' => Yii::t('app', '阿姨类型'),
            'worker_type_name' => Yii::t('app', '阿姨职位类型'),
            'worker_identity_id' => Yii::t('app', '阿姨身份'),
            'worker_identity_name' => Yii::t('app', '阿姨身份描述'),
            'shop_id' => Yii::t('app', '门店id'),
            'shop_name' => Yii::t('app', '门店名称'),
            'shop_manager_id' => Yii::t('app', '归属家政id'),
            'shop_manager_name' => Yii::t('app', '归属家政名称'),
            'finance_worker_settle_apply_order_count' => Yii::t('app', '总单量'),
            'finance_worker_settle_apply_man_hour' => Yii::t('app', '订单总工时'),
            'finance_worker_settle_apply_order_money' => Yii::t('app', '工时费小计'),
            'finance_worker_settle_apply_task_count' => Yii::t('app', '完成任务数'),
            'finance_worker_settle_apply_task_money' => Yii::t('app', '完成任务奖励'),
            'finance_worker_settle_apply_base_salary' => Yii::t('app', '底薪'),
            'finance_worker_settle_apply_base_salary_subsidy' => Yii::t('app', '底薪补贴'),
            'finance_worker_settle_apply_money_except_deduct_cash' => Yii::t('app', '应结合计'),
            'finance_worker_settle_apply_money_deduction' => Yii::t('app', '扣款小计'),
            'finance_worker_settle_apply_money_except_cash' => Yii::t('app', '本次应结合计'),
            'finance_worker_settle_apply_order_cash_count' => Yii::t('app', '现金订单'),
            'finance_worker_settle_apply_order_cash_money' => Yii::t('app', '已收现金小计'),
            'finance_worker_settle_apply_money' => Yii::t('app', '本次应付合计'),
            'finance_worker_settle_apply_order_noncash_count' => Yii::t('app', '非现金订单'),
            'finance_worker_settle_apply_order_money_except_cash' => Yii::t('app', '工时费应结'),
            'finance_worker_settle_apply_status' => Yii::t('app', '结算状态'),
            'finance_worker_settle_apply_cycle' => Yii::t('app', '结算周期'),
            'finance_worker_settle_apply_cycle_des' => Yii::t('app', '结算类型'),
            'finance_worker_settle_apply_reviewer' => Yii::t('app', '审核人姓名'),
            'finance_worker_settle_apply_starttime' => Yii::t('app', '结算开始时间'),
            'finance_worker_settle_apply_endtime' => Yii::t('app', '结算结束时间'),
            'isManagementFeeDone' => Yii::t('app', '门店服务管理费是否已结算，0为未结算，1为已结算'),
            'finance_shop_settle_apply_id' => Yii::t('app', '门店结算Id'),
            'isWorkerConfirmed' => Yii::t('app', '阿姨是否已经确认结算'),
            'is_softdel' => Yii::t('app', '是否被删除，0为启用，1为删除'), 
            'updated_at' => Yii::t('app', '审核时间'),
            'created_at' => Yii::t('app', '申请时间'),
            'comment' => Yii::t('app', '备注'),
        ];
    }
}
