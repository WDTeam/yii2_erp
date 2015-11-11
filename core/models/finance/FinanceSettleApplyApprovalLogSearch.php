<?php

namespace core\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\finance\FinanceSettleApplyApprovalLog;

/**
 * FinanceSettleApplyLogSearch represents the model behind the search form about `dbbase\models\finance\FinanceSettleApplyLog`.
 */
class FinanceSettleApplyApprovalLogSearch extends FinanceSettleApplyApprovalLog
{
    public function rules()
    {
        return [
            [['id', 'finance_settle_apply_id', 'finance_settle_apply_reviewer_id',  'finance_settle_apply_node_id', 'finance_settle_apply_is_passed', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_settle_apply_reviewer_comment'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceSettleApplyLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_settle_apply_id' => $this->finance_settle_apply_id,
            'finance_settle_apply_reviewer_id' => $this->finance_settle_apply_reviewer_id,
            'finance_settle_apply_reviewer' => $this->finance_settle_apply_reviewer,
            'finance_settle_apply_node_id' => $this->finance_settle_apply_node_id,
            'finance_settle_apply_node_des' => $this->finance_settle_apply_node_des,
            'finance_settle_apply_is_passed' => $this->finance_settle_apply_is_passed,
            'is_softdel' => $this->is_softdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'finance_settle_apply_reviewer_comment', $this->finance_settle_apply_reviewer_comment]);

        return $dataProvider;
    }
}
