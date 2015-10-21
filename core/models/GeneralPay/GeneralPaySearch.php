<?php

namespace core\models\GeneralPay;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\GeneralPay\GeneralPay;

/**
 * GeneralPaySearch represents the model behind the search form about `\core\models\GeneralPay\GeneralPay`.
 */
class GeneralPaySearch extends GeneralPay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'order_id', 'general_pay_source', 'general_pay_mode', 'general_pay_status', 'general_pay_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at'], 'integer'],
            [['general_pay_money', 'general_pay_actual_money'], 'number'],
            [['general_pay_source_name', 'general_pay_transaction_id', 'general_pay_eo_order_id', 'general_pay_memo', 'general_pay_admin_name', 'general_pay_handle_admin_id', 'general_pay_verify'], 'safe'],
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
        $query = GeneralPay::find();

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
            'general_pay_money' => $this->general_pay_money,
            'general_pay_actual_money' => $this->general_pay_actual_money,
            'general_pay_source' => $this->general_pay_source,
            'general_pay_mode' => $this->general_pay_mode,
            'general_pay_status' => $this->general_pay_status,
            'general_pay_is_coupon' => $this->general_pay_is_coupon,
            'admin_id' => $this->admin_id,
            'worker_id' => $this->worker_id,
            'handle_admin_id' => $this->handle_admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]);

        $query->andFilterWhere(['like', 'general_pay_source_name', $this->general_pay_source_name])
            ->andFilterWhere(['like', 'general_pay_transaction_id', $this->general_pay_transaction_id])
            ->andFilterWhere(['like', 'general_pay_eo_order_id', $this->general_pay_eo_order_id])
            ->andFilterWhere(['like', 'general_pay_memo', $this->general_pay_memo])
            ->andFilterWhere(['like', 'general_pay_admin_name', $this->general_pay_admin_name])
            ->andFilterWhere(['like', 'general_pay_handle_admin_id', $this->general_pay_handle_admin_id])
            ->andFilterWhere(['like', 'general_pay_verify', $this->general_pay_verify]);

        return $dataProvider;
    }
}
