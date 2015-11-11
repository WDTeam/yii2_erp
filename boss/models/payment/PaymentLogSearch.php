<?php

namespace boss\models\payment;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\payment\PaymentLog;

/**
 * PaymentLogSearch represents the model behind the search form about `boss\models\payment\PaymentLog`.
 */
class PaymentLogSearch extends PaymentLog
{
    public function rules()
    {
        return [
            [['_id','id', 'payment_log_status_bool', 'pay_channel_id', 'created_at', 'updated_at'], 'integer'],
            [['payment_log_price'], 'number'],
            [['payment_log_shop_name', 'payment_log_eo_order_id', 'payment_log_transaction_id', 'payment_log_status', 'pay_channel_name', 'payment_log_json_aggregation'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PaymentLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'payment_log_price' => $this->payment_log_price,
            'payment_log_status_bool' => $this->payment_log_status_bool,
            'pay_channel_id' => $this->pay_channel_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'payment_log_shop_name', $this->payment_log_shop_name])
            ->andFilterWhere(['like', 'payment_log_eo_order_id', $this->payment_log_eo_order_id])
            ->andFilterWhere(['like', 'payment_log_transaction_id', $this->payment_log_transaction_id])
            ->andFilterWhere(['like', 'payment_log_status', $this->payment_log_status])
            ->andFilterWhere(['like', 'pay_channel_name', $this->pay_channel_name])
            ->andFilterWhere(['like', 'payment_log_json_aggregation', $this->payment_log_json_aggregation]);

        return $dataProvider;
    }
}
