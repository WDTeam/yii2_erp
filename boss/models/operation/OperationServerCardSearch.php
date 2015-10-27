<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServerCard;

/**
 * OperationServerCardSearch represents the model behind the search form about `\common\models\operation\OperationServerCard`.
 */
class OperationServerCardSearch extends OperationServerCard
{
    public function rules()
    {
        return [
            [['id', 'card_type', 'card_level', 'use_scope', 'valid_days', 'created_at', 'updated_at'], 'integer'],
            [['card_name'], 'safe'],
            [['par_value', 'reb_value'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServerCard::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'card_type' => $this->card_type,
            'card_level' => $this->card_level,
            'par_value' => $this->par_value,
            'reb_value' => $this->reb_value,
            'use_scope' => $this->use_scope,
            'valid_days' => $this->valid_days,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'card_name', $this->card_name]);

        return $dataProvider;
    }
}
