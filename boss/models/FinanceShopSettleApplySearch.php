<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceShopSettleApply;
use common\models\FinanceSettleApply;

/**
 * FinanceShopSettleApplySearch represents the model behind the search form about `common\models\FinanceShopSettleApply`.
 */
class FinanceShopSettleApplySearch extends FinanceShopSettleApply
{
    const BUSINESS_REVIEW = 1;//业务部门审核
    
    const FINANCE_REVIEW = 2;//财务部门审核
    
    public $review_section;//审核部门
    
    public $financeShopSettleApplyStatusArr = [
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核不通过',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED=>'业务部门审核不通过',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT=>'提出申请，正在业务部门审核',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED=>'业务部门审核通过，等待财务审核',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核通过'];
    
    public function rules()
    {
        return [
            [[ 'finance_shop_settle_apply_starttime', 'finance_shop_settle_apply_endtime'], 'required'],
            [['id', 'shop_id', 'shop_manager_id', 'finance_shop_settle_apply_order_count', 'finance_shop_settle_apply_status', 'finance_shop_settle_apply_cycle', 'finance_shop_settle_apply_starttime', 'finance_shop_settle_apply_endtime', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['shop_name', 'shop_manager_name', 'finance_shop_settle_apply_cycle_des', 'finance_shop_settle_apply_reviewer'], 'safe'],
            [['finance_shop_settle_apply_fee_per_order', 'finance_shop_settle_apply_fee'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['<=', 'finance_shop_settle_apply_starttime', $this->finance_shop_settle_apply_starttime])
            ->andFilterWhere(['>=', 'finance_shop_settle_apply_endtime', $this->finance_shop_settle_apply_endtime])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'shop_manager_name', $this->shop_manager_name])
            ->andFilterWhere(['like', 'finance_shop_settle_apply_cycle_des', $this->finance_shop_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_shop_settle_apply_reviewer', $this->finance_shop_settle_apply_reviewer]);

        return $dataProvider;
    }
    
    public function getShopSettleApplyStatusDes($shopSettleApplyStatus){
        return $this->financeShopSettleApplyStatusArr[$shopSettleApplyStatus];
    }
}
