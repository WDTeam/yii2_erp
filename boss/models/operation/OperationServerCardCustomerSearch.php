<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServerCardCustomer;

/**
 * OperationServerCardCustomerSearch represents the model behind the search form about `\boss\models\operation\OperationServerCardCustomer`.
 */
class OperationServerCardCustomerSearch extends OperationServerCardCustomer
{
    public function rules()
    {
        return [
            [['id', 'order_id', 'card_id', 'card_type', 'card_level', 'customer_id', 'use_scope', 'buy_at', 'valid_at', 'activated_at', 'freeze_flag', 'created_at', 'updated_at'], 'integer'],
            [['order_code', 'card_no', 'card_name', 'customer_name', 'customer_phone'], 'safe'],
            [['pay_value', 'par_value', 'reb_value', 'res_value'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServerCardCustomer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'card_id' => $this->card_id,
            'card_type' => $this->card_type,
            'card_level' => $this->card_level,
            'pay_value' => $this->pay_value,
            'par_value' => $this->par_value,
            'reb_value' => $this->reb_value,
            'res_value' => $this->res_value,
            'customer_id' => $this->customer_id,
            'use_scope' => $this->use_scope,
            'buy_at' => $this->buy_at,
            'valid_at' => $this->valid_at,
            'activated_at' => $this->activated_at,
            'freeze_flag' => $this->freeze_flag,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'card_no', $this->card_no])
            ->andFilterWhere(['like', 'card_name', $this->card_name])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone', $this->customer_phone]);

        return $dataProvider;
    }
}
