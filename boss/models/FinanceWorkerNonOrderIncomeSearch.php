<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceWorkerNonOrderIncome;

/**
 * FinanceWorkerNonOrderIncomeSearch represents the model behind the search form about `common\models\FinanceWorkerNonOrderIncome`.
 */
class FinanceWorkerNonOrderIncomeSearch extends FinanceWorkerNonOrderIncome
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'worder_id', 'finance_worker_non_order_income_type', 'finance_worker_non_order_income_starttime', 'finance_worker_non_order_income_endtime', 'finance_worker_non_order_income_isSettled', 'finance_settle_apply_id', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_non_order_income'], 'number'],
            [['finance_worker_non_order_income_des'], 'safe'],
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
        $query = FinanceWorkerNonOrderIncome::find();

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
            'finance_worker_non_order_income_type' => $this->finance_worker_non_order_income_type,
            'finance_worker_non_order_income' => $this->finance_worker_non_order_income,
            'finance_worker_non_order_income_starttime' => $this->finance_worker_non_order_income_starttime,
            'finance_worker_non_order_income_endtime' => $this->finance_worker_non_order_income_endtime,
            'finance_worker_non_order_income_isSettled' => $this->finance_worker_non_order_income_isSettled,
            'finance_settle_apply_id' => $this->finance_settle_apply_id,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'finance_worker_non_order_income_des', $this->finance_worker_non_order_income_des]);

        return $dataProvider;
    }
    
    public static function getSubsidyDetail($settleApplyId){
        $detail = "";
        $nonIncomeArr = FinanceWorkerNonOrderIncome::find()->where(['finance_settle_apply_id'=>$settleApplyId])->all();
        foreach($nonIncomeArr as $nonIncome){
            $detail.=$nonIncome->finance_worker_non_order_income_type_des.':'.$nonIncome->finance_worker_non_order_income.'|';
        }
        return $detail;
    }
    
    public function getNonOrderIncomeBySettleApplyId($settleApplyId){
        $nonOrderIncomeArr =  FinanceWorkerNonOrderIncome::find()->select(['finance_worker_non_order_income_type','finance_worker_non_order_income'])
                 ->where(['finance_settle_apply_id'=>$settleApplyId])->all();
        return $nonOrderIncomeArr;
    }
}
