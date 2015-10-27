<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServerCardOrder;

/**
 * OperationServerCardOrderSearch represents the model behind the search form about `\boss\models\operation\OperationServerCardOrder`.
 */
class OperationServerCardOrderSearch extends OperationServerCardOrder
{
    public function rules()
    {
        return [
            [['id', 'usere_id', 'order_customer_phone', 'server_card_id', 'card_type', 'card_level', 'order_src_id', 'order_channel_id', 'order_lock_status', 'order_status_id', 'created_at', 'updated_at', 'order_pay_type', 'pay_channel_id', 'paid_at'], 'integer'],
            [['order_code', 'card_name', 'order_src_name', 'order_channel_name', 'order_status_name', 'pay_channel_name', 'order_pay_flow_num'], 'safe'],
            [['par_value', 'reb_value', 'order_money', 'order_pay_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServerCardOrder::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'usere_id' => $this->usere_id,
            'order_customer_phone' => $this->order_customer_phone,
            'server_card_id' => $this->server_card_id,
            'card_type' => $this->card_type,
            'card_level' => $this->card_level,
            'par_value' => $this->par_value,
            'reb_value' => $this->reb_value,
            'order_money' => $this->order_money,
            'order_src_id' => $this->order_src_id,
            'order_channel_id' => $this->order_channel_id,
            'order_lock_status' => $this->order_lock_status,
            'order_status_id' => $this->order_status_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_pay_type' => $this->order_pay_type,
            'pay_channel_id' => $this->pay_channel_id,
            'order_pay_money' => $this->order_pay_money,
            'paid_at' => $this->paid_at,
        ]);

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'card_name', $this->card_name])
            ->andFilterWhere(['like', 'order_src_name', $this->order_src_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_status_name', $this->order_status_name])
            ->andFilterWhere(['like', 'pay_channel_name', $this->pay_channel_name])
            ->andFilterWhere(['like', 'order_pay_flow_num', $this->order_pay_flow_num]);

        return $dataProvider;
    }
}
