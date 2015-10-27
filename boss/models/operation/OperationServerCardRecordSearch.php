<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServerCardRecord;

/**
 * OperationServerCardRecordSearch represents the model behind the search form about `\boss\models\operation\OperationServerCardRecord`.
 */
class OperationServerCardRecordSearch extends OperationServerCardRecord
{
    public function rules()
    {
        return [
            [['id', 'trade_id', 'cus_card_id', 'created_at', 'updated_at'], 'integer'],
            [['front_value', 'behind_value', 'use_value'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServerCardRecord::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'trade_id' => $this->trade_id,
            'cus_card_id' => $this->cus_card_id,
            'front_value' => $this->front_value,
            'behind_value' => $this->behind_value,
            'use_value' => $this->use_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
