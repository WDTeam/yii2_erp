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
    public function rules()
    {
        return [
            [['id', 'worder_id', 'settle_apply_status', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['settle_apply_money'], 'number'],
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
            'settle_apply_money' => $this->settle_apply_money,
            'settle_apply_status' => $this->settle_apply_status,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
