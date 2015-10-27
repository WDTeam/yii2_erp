<?php

namespace core\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\operation\OperationServerCardRecord;

/**
 * OperationServerCardRecordSearch represents the model behind the search form about `\core\models\operation\OperationServerCardRecord`.
 */
class OperationServerCardRecordSearch extends OperationServerCardRecord
{
    public function rules()
    {
        return [
            [['id', 'trade_id','order_id', 'cus_card_id', 'created_at', 'updated_at','consume_type','business_type'], 'integer'],
			[['order_code', 'card_no'],'safe'],
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
			'order_id' => $this->order_id,
			'order_code' => $this->order_code,
            'cus_card_id' => $this->cus_card_id,
			'card_no' => $this->card_no,
            'front_value' => $this->front_value,
            'behind_value' => $this->behind_value,
            'consume_type' => $this->consume_type,
			'business_type' => $this->business_type,
			'use_value' => $this->use_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
