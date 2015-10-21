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
    const FINANCE_COMPENSATE_REVIEW_INIT = 0;//提出申请
    
    const FINANCE_COMPENSATE_REVIEW_PASSED = 1;//确认打款
    
    const FINANCE_COMPENSATE_REVIEW_FAILED = -1;//不通过
    
    public function rules()
    {
        return [
            [['id', 'finance_complaint_id', 'worker_id', 'customer_id', 'updated_at', 'created_at', 'isdel'], 'integer'],
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

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_complaint_id' => $this->finance_complaint_id,
            'worker_id' => $this->worker_id,
            'customer_id' => $this->customer_id,
            'finance_compensate_money' => $this->finance_compensate_money,
            'finance_compensate_status' => $this->finance_compensate_status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'isdel' => $this->isdel,
        ]);

        $query->andFilterWhere(['like', 'finance_compensate_oa_code', $this->finance_compensate_oa_code])
                ->andFilterWhere(['like', 'worker_tel1', $this->worker_tel])
            ->andFilterWhere(['like', 'finance_compensate_coupon', $this->finance_compensate_coupon])
            ->andFilterWhere(['like', 'finance_compensate_reason', $this->finance_compensate_reason])
            ->andFilterWhere(['like', 'finance_compensate_proposer', $this->finance_compensate_proposer])
            ->andFilterWhere(['like', 'finance_compensate_auditor', $this->finance_compensate_auditor])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
