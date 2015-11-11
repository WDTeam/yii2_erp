<?php

namespace core\models\finance;

use Yii;
use yii\data\ActiveDataProvider;

use core\models\finance\FinanceWorkerSettleApplySearch;

use dbbase\models\finance\FinanceShopSettleApply;
use dbbase\models\finance\FinanceWorkerSettleApply;
/**
 * FinanceShopSettleApplySearch represents the model behind the search form about `dbbase\models\finance\FinanceShopSettleApply`.
 */
class FinanceShopSettleApplySearch extends FinanceShopSettleApply
{
    const BUSINESS_REVIEW = 1;//业务部门审核
    
    const FINANCE_REVIEW = 2;//财务部门审核
    
    public $review_section;//审核部门
    
    const MANAGE_FEE_PER_ORDER = 10;//门店管理费，每单10元
    
    public $settle_apply_create_start_time;//结算申请开始时间
    
    public $settle_apply_create_end_time;//结算申请结束时间
    
    public $financeShopSettleApplyStatusArr = [
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核不通过',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED=>'业务部门审核不通过',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT=>'提出申请，正在业务部门审核',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED=>'业务部门审核通过，等待财务审核',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核通过',
        FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED=>'财务已确认打款',];
    
    public function rules()
    {
        return [
            [[ 'settle_apply_create_start_time', 'settle_apply_create_end_time'], 'required','on'=>['query','default']],
            [['id', 'shop_id', 'shop_manager_id', 'finance_shop_settle_apply_order_count', 'finance_shop_settle_apply_status', 'finance_shop_settle_apply_cycle', 'finance_shop_settle_apply_starttime', 'finance_shop_settle_apply_endtime', 'is_softdel', 'updated_at', 'created_at'], 'integer','on'=>['query','default']],
            [['shop_name', 'shop_manager_name', 'finance_shop_settle_apply_cycle_des', 'finance_shop_settle_apply_reviewer'], 'safe','on'=>['query','default']],
            [['finance_shop_settle_apply_fee_per_order', 'finance_shop_settle_apply_fee'], 'number','on'=>['query','default']],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return [
            'query'=>[ 'settle_apply_create_start_time', 'settle_apply_create_end_time'],
            'default'=>['id', 'shop_id', 'shop_manager_id', 'finance_shop_settle_apply_order_count', 'finance_shop_settle_apply_status', 'finance_shop_settle_apply_cycle', 'finance_shop_settle_apply_starttime', 'finance_shop_settle_apply_endtime', 'is_softdel', 'updated_at', 'created_at'],
        ];
    }

    public function search($params)
    {
        $query = FinanceShopSettleApplySearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'shop_manager_id' => $this->shop_manager_id,
            'finance_shop_settle_apply_order_count' => $this->finance_shop_settle_apply_order_count,
            'finance_shop_settle_apply_fee_per_order' => $this->finance_shop_settle_apply_fee_per_order,
            'finance_shop_settle_apply_fee' => $this->finance_shop_settle_apply_fee,
            'finance_shop_settle_apply_status' => $this->finance_shop_settle_apply_status,
            'finance_shop_settle_apply_cycle' => $this->finance_shop_settle_apply_cycle,
            'is_softdel' => $this->is_softdel,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['between', 'created_at', $this->settle_apply_create_start_time,$this->settle_apply_create_end_time])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_manager_name', $this->shop_manager_name])
            ->andFilterWhere(['like', 'finance_shop_settle_apply_cycle_des', $this->finance_shop_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_shop_settle_apply_reviewer', $this->finance_shop_settle_apply_reviewer]);
        $dataProvider->query->orderBy(['created_at'=>SORT_DESC]);
        return $dataProvider;
    }
    
    public function getShopSettleApplyStatusDes($shopSettleApplyStatus){
        return $this->financeShopSettleApplyStatusArr[$shopSettleApplyStatus];
    }
    
    public function getShopSettleInfo($shopId){
        $orderCount = FinanceWorkerSettleApply::find()
                ->select(['sum(finance_worker_settle_apply_order_count) as orderCount'])
                ->andWhere(['shop_id'=>$shopId,'worker_type_id'=>FinanceWorkerSettleApplySearch::NON_SELF_OPERATION])
                ->andFilterWhere(['>=','finance_worker_settle_apply_status',FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED])
                ->asArray()->all();
        $this->finance_shop_settle_apply_order_count = $orderCount[0]['orderCount'];
        $this->finance_shop_settle_apply_fee_per_order = self::MANAGE_FEE_PER_ORDER;
        $this->finance_shop_settle_apply_fee = self::MANAGE_FEE_PER_ORDER * $this->finance_shop_settle_apply_order_count;
    }
    
    public function getCanPayedShopSettlementList(){
        $shopSettleApplyArray = self::find()->where(['finance_shop_settle_apply_status'=>FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED])->all();
        return $shopSettleApplyArray;
    }
    
    public function attributeLabels()
    {
        $parentAttributeLabels = parent::attributeLabels();
        $addAttributeLabels = [
            'settle_apply_create_start_time' => Yii::t('app', '申请开始时间'),
            'settle_apply_create_end_time' => Yii::t('app', '申请结束时间'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
}
