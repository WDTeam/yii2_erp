<?php

namespace boss\models\payment;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PaymentSearch represents the model behind the search form about `\core\models\Payment\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'payment_source', 'payment_mode', 'payment_status', 'payment_channel_id','payment_type', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at'], 'integer'],
            [['payment_money', 'payment_actual_money'], 'number'],
            [['order_id', 'payment_channel_name', 'payment_transaction_id', 'payment_eo_order_id', 'payment_memo', 'payment_admin_name', 'payment_handle_admin_name', 'payment_verify'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Payment::find();
        $query->orderBy('id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_id' => $this->order_id,
            'payment_money' => $this->payment_money,
            'payment_actual_money' => $this->payment_actual_money,
            'payment_source' => $this->payment_source,
            'payment_channel_id' => $this->payment_channel_id,
            'payment_mode' => $this->payment_mode,
            'payment_status' => $this->payment_status,
            'payment_type' => $this->payment_type,
            'admin_id' => $this->admin_id,
            'worker_id' => $this->worker_id,
            'handle_admin_id' => $this->handle_admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]);

        $query->andFilterWhere(['like', 'payment_channel_name', $this->payment_channel_name])
            ->andFilterWhere(['like', 'payment_transaction_id', $this->payment_transaction_id])
            ->andFilterWhere(['like', 'payment_eo_order_id', $this->payment_eo_order_id])
            ->andFilterWhere(['like', 'payment_memo', $this->payment_memo])
            ->andFilterWhere(['like', 'payment_admin_name', $this->payment_admin_name])
            ->andFilterWhere(['like', 'payment_handle_admin_name', $this->payment_handle_admin_name])
            ->andFilterWhere(['like', 'payment_verify', $this->payment_verify]);

        return $dataProvider;
    }
}
