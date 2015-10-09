<?php

namespace core\models\order;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    public function rules()
    {
        return [
            [['id', 'order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'order_src_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id', 'checking_id'], 'integer'],
            [['order_code', 'order_service_type_name', 'order_src_name', 'order_channel_name', 'order_address', 'order_cs_memo'], 'safe'],
            [['order_unit_money', 'order_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_parent_id' => $this->order_parent_id,
            'order_is_parent' => $this->order_is_parent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isdel' => $this->isdel,
            'order_ip' => $this->order_ip,
            'order_service_type_id' => $this->order_service_type_id,
            'order_src_id' => $this->order_src_id,
            'channel_id' => $this->channel_id,
            'order_unit_money' => $this->order_unit_money,
            'order_money' => $this->order_money,
            'order_booked_count' => $this->order_booked_count,
            'order_booked_begin_time' => $this->order_booked_begin_time,
            'order_booked_end_time' => $this->order_booked_end_time,
            'address_id' => $this->address_id,
            'order_booked_worker_id' => $this->order_booked_worker_id,
            'checking_id' => $this->checking_id,
        ]);

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
            ->andFilterWhere(['like', 'order_src_name', $this->order_src_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_address', $this->order_address])
            ->andFilterWhere(['like', 'order_cs_memo', $this->order_cs_memo]);

        return $dataProvider;
    }
}
