<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceCompensate as FinanceCompensateModel;

/**
 * FinanceCompensate represents the model behind the search form about `common\models\FinanceCompensate`.
 */
class FinanceCompensate extends FinanceCompensateModel
{
    public function rules()
    {
        return [
            [['id', 'finance_complaint_id', 'worker_id', 'customer_id', 'updated_at', 'created_at', 'is_del'], 'integer'],
            [['finance_compensate_oa_code', 'finance_compensate_coupon', 'finance_compensate_reason', 'finance_compensate_proposer', 'finance_compensate_auditor', 'comment'], 'safe'],
            [['finance_compensate_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceCompensateModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_complaint_id' => $this->finance_complaint_id,
            'worker_id' => $this->worker_id,
            'customer_id' => $this->customer_id,
            'finance_compensate_money' => $this->finance_compensate_money,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_compensate_oa_code', $this->finance_compensate_oa_code])
            ->andFilterWhere(['like', 'finance_compensate_coupon', $this->finance_compensate_coupon])
            ->andFilterWhere(['like', 'finance_compensate_reason', $this->finance_compensate_reason])
            ->andFilterWhere(['like', 'finance_compensate_proposer', $this->finance_compensate_proposer])
            ->andFilterWhere(['like', 'finance_compensate_auditor', $this->finance_compensate_auditor])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
