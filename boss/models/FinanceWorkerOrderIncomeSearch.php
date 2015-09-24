<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceWorkerOrderIncome;

/**
 * FinanceWorkerOrderIncomeSearch represents the model behind the search form about `common\models\FinanceWorkerOrderIncome`.
 */
class FinanceWorkerOrderIncomeSearch extends FinanceWorkerOrderIncome
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'worder_id', 'order_id', 'finance_worker_order_income_type', 'order_booked_count', 'isSettled', 'finance_settle_apply_id', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_order_income'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FinanceWorkerOrderIncome::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worder_id' => $this->worder_id,
            'order_id' => $this->order_id,
            'finance_worker_order_income_type' => $this->finance_worker_order_income_type,
            'finance_worker_order_income' => $this->finance_worker_order_income,
            'order_booked_count' => $this->order_booked_count,
            'isSettled' => $this->isSettled,
            'finance_settle_apply_id' => $this->finance_settle_apply_id,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
    
    /**
     * 计算已完成订单的收入（包括订单收入和补贴收入），并保存到数据库表
     * @param type $orderId
     */
    public function countOrderIncomeAndSave($orderId){
        //获取订单信息并判断状态是否为已完成
        
        
        
    }
}
