<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationSpec;

/**
 * OperationSpecSearch represents the model behind the search form about `boss\models\operation\OperationSpec`.
 */
class OperationSpecSearch extends OperationSpec
{
    public function rules()
    {
        return [
            [['id', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['operation_spec_name', 'operation_spec_description', 'operation_spec_strategy_unit', 'operation_spec_values'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationSpec::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'operation_spec_name', $this->operation_spec_name])
            ->andFilterWhere(['like', 'operation_spec_description', $this->operation_spec_description])
            ->andFilterWhere(['like', 'operation_spec_strategy_unit', $this->operation_spec_strategy_unit])
            ->andFilterWhere(['like', 'operation_spec_values', $this->operation_spec_values]);

        return $dataProvider;
    }
}
