<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServiceCardWithCustomer;

/**
 * OperationServiceCardWithCustomerSearch represents the model behind the search form about `\boss\models\operation\OperationServiceCardWithCustomer`.
 */
class OperationServiceCardWithCustomerSearch extends OperationServiceCardWithCustomer
{
    public function rules()
    {
        return [
            [['id', 'service_card_sell_record_id', 'service_card_info_id', 'customer_id', 'service_card_info_scope', 'service_card_with_customer_buy_at', 'service_card_with_customer_valid_at', 'service_card_with_customer_activated_at', 'service_card_with_customer_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_sell_record_code', 'service_card_with_customer_code', 'service_card_info_name', 'customer_phone'], 'safe'],
            [['customer_trans_record_pay_money', 'service_card_info_value', 'service_card_info_rebate_value', 'service_card_with_customer_balance'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServiceCardWithCustomer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'service_card_sell_record_id' => $this->service_card_sell_record_id,
            'service_card_info_id' => $this->service_card_info_id,
            'customer_trans_record_pay_money' => $this->customer_trans_record_pay_money,
            'service_card_info_value' => $this->service_card_info_value,
            'service_card_info_rebate_value' => $this->service_card_info_rebate_value,
            'service_card_with_customer_balance' => $this->service_card_with_customer_balance,
            'customer_id' => $this->customer_id,
            'service_card_info_scope' => $this->service_card_info_scope,
            'service_card_with_customer_buy_at' => $this->service_card_with_customer_buy_at,
            'service_card_with_customer_valid_at' => $this->service_card_with_customer_valid_at,
            'service_card_with_customer_activated_at' => $this->service_card_with_customer_activated_at,
            'service_card_with_customer_status' => $this->service_card_with_customer_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'service_card_sell_record_code', $this->service_card_sell_record_code])
            ->andFilterWhere(['like', 'service_card_with_customer_code', $this->service_card_with_customer_code])
            ->andFilterWhere(['like', 'service_card_info_name', $this->service_card_info_name])
            ->andFilterWhere(['like', 'customer_phone', $this->customer_phone]);

        return $dataProvider;
    }
}
