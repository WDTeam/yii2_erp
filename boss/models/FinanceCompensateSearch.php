<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceCompensate;

/**
 * FinanceCompensateSearch represents the model behind the search form about `common\models\FinanceCompensate`.
 */
class FinanceCompensateSearch extends FinanceCompensate
{
    public function rules()
    {
        return [
            [['id', 'finance_pay_channel_id', 'finance_order_channel_id', 'finance_compensate_pay_create_time', 'finance_compensate_pay_status', 'finance_compensate_worker_id', 'create_time', 'is_del'], 'integer'],
            [['finance_compensate_oa_num', 'finance_compensate_cause', 'finance_compensate_tel', 'finance_pay_channel_name', 'finance_order_channel_name', 'finance_compensate_pay_flow_num', 'finance_compensate_worker_tel', 'finance_compensate_proposer', 'finance_compensate_auditor'], 'safe'],
            [['finance_compensate_pay_money', 'finance_compensate_money', 'finance_compensate_discount'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceCompensate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_compensate_pay_money' => $this->finance_compensate_pay_money,
            'finance_compensate_money' => $this->finance_compensate_money,
            'finance_pay_channel_id' => $this->finance_pay_channel_id,
            'finance_order_channel_id' => $this->finance_order_channel_id,
            'finance_compensate_discount' => $this->finance_compensate_discount,
            'finance_compensate_pay_create_time' => $this->finance_compensate_pay_create_time,
            'finance_compensate_pay_status' => $this->finance_compensate_pay_status,
            'finance_compensate_worker_id' => $this->finance_compensate_worker_id,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_compensate_oa_num', $this->finance_compensate_oa_num])
            ->andFilterWhere(['like', 'finance_compensate_cause', $this->finance_compensate_cause])
            ->andFilterWhere(['like', 'finance_compensate_tel', $this->finance_compensate_tel])
            ->andFilterWhere(['like', 'finance_pay_channel_name', $this->finance_pay_channel_name])
            ->andFilterWhere(['like', 'finance_order_channel_name', $this->finance_order_channel_name])
            ->andFilterWhere(['like', 'finance_compensate_pay_flow_num', $this->finance_compensate_pay_flow_num])
            ->andFilterWhere(['like', 'finance_compensate_worker_tel', $this->finance_compensate_worker_tel])
            ->andFilterWhere(['like', 'finance_compensate_proposer', $this->finance_compensate_proposer])
            ->andFilterWhere(['like', 'finance_compensate_auditor', $this->finance_compensate_auditor]);

        return $dataProvider;
    }
}
