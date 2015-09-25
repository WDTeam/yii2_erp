<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceSettleApply;

/**
 * FinanceSettleApplySearch represents the model behind the search form about `common\models\FinanceSettleApply`.
 */
class FinanceSettleApplySearch extends FinanceSettleApply
{
   public $ids;
   
   public $nodeId;
   
   
    public function rules()
    {
        return [
            [['id', 'worder_id', 'worker_type_id', 'finance_settle_apply_man_hour', 'finance_settle_apply_status', 'finance_settle_apply_cycle', 'finance_settle_apply_starttime', 'finance_settle_apply_endtime', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['worder_tel', 'worker_type_name', 'finance_settle_apply_cycle_des', 'finance_settle_apply_reviewer'], 'safe'],
            [['finance_settle_apply_order_money', 'finance_settle_apply_order_cash_money', 'finance_settle_apply_order_money_except_cash', 'finance_settle_apply_subsidy', 'finance_settle_apply_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceSettleApply::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worder_id' => $this->worder_id,
            'worker_type_id' => $this->worker_type_id,
            'finance_settle_apply_man_hour' => $this->finance_settle_apply_man_hour,
            'finance_settle_apply_order_money' => $this->finance_settle_apply_order_money,
            'finance_settle_apply_order_cash_money' => $this->finance_settle_apply_order_cash_money,
            'finance_settle_apply_order_money_except_cash' => $this->finance_settle_apply_order_money_except_cash,
            'finance_settle_apply_subsidy' => $this->finance_settle_apply_subsidy,
            'finance_settle_apply_money' => $this->finance_settle_apply_money,
            'finance_settle_apply_status' => $this->finance_settle_apply_status,
            'finance_settle_apply_cycle' => $this->finance_settle_apply_cycle,
            'finance_settle_apply_starttime' => $this->finance_settle_apply_starttime,
            'finance_settle_apply_endtime' => $this->finance_settle_apply_endtime,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'worder_tel', $this->worder_tel])
            ->andFilterWhere(['like', 'worker_type_name', $this->worker_type_name])
            ->andFilterWhere(['like', 'finance_settle_apply_cycle_des', $this->finance_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_settle_apply_reviewer', $this->finance_settle_apply_reviewer]);

        return $dataProvider;
    }
    
    
            
}
