<?php

namespace boss\models\payment;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;



/**
 * CustomerTransRecordSearch represents the model behind the search form about `dbbase\models\CustomerTransRecord`.
 */
class PaymentCustomerTransRecordSearch extends PaymentCustomerTransRecord
{
    public function rules()
    {
        return [
            [['id', 'customer_id', 'order_channel_id', 'payment_customer_trans_record_order_channel', 'pay_channel_id', 'payment_customer_trans_record_pay_channel', 'payment_customer_trans_record_mode', 'payment_customer_trans_record_mode_name', 'created_at', 'updated_at'], 'integer'],
            [['payment_customer_trans_record_coupon_money', 'payment_customer_trans_record_cash', 'payment_customer_trans_record_pre_pay', 'payment_customer_trans_record_online_pay', 'payment_customer_trans_record_online_balance_pay', 'payment_customer_trans_record_service_card_pay', 'payment_customer_trans_record_compensate_money', 'payment_customer_trans_record_refund_money', 'payment_customer_trans_record_order_total_money', 'payment_customer_trans_record_total_money', 'payment_customer_trans_record_current_balance', 'payment_customer_trans_record_befor_balance'], 'number'],
            [['order_id', 'payment_customer_trans_record_service_card_on', 'payment_customer_trans_record_transaction_id', 'payment_customer_trans_record_remark', 'payment_customer_trans_record_verify'], 'safe'],
        ];
    }

    public function beforeValidate(){
        return true;
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PaymentCustomerTransRecord::find();
        $query->orderBy('id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_id' => $this->order_id,
            'order_channel_id' => $this->order_channel_id,
            'payment_customer_trans_record_order_channel' => $this->payment_customer_trans_record_order_channel,
            'pay_channel_id' => $this->pay_channel_id,
            'payment_customer_trans_record_pay_channel' => $this->payment_customer_trans_record_pay_channel,
            'payment_customer_trans_record_mode' => $this->payment_customer_trans_record_mode,
            'payment_customer_trans_record_mode_name' => $this->payment_customer_trans_record_mode_name,
            'payment_customer_trans_record_coupon_money' => $this->payment_customer_trans_record_coupon_money,
            'payment_customer_trans_record_cash' => $this->payment_customer_trans_record_cash,
            'payment_customer_trans_record_pre_pay' => $this->payment_customer_trans_record_pre_pay,
            'payment_customer_trans_record_online_pay' => $this->payment_customer_trans_record_online_pay,
            'payment_customer_trans_record_online_balance_pay' => $this->payment_customer_trans_record_online_balance_pay,
            'payment_customer_trans_record_service_card_pay' => $this->payment_customer_trans_record_service_card_pay,
            'payment_customer_trans_record_compensate_money' => $this->payment_customer_trans_record_compensate_money,
            'payment_customer_trans_record_refund_money' => $this->payment_customer_trans_record_refund_money,
            'payment_customer_trans_record_order_total_money' => $this->payment_customer_trans_record_order_total_money,
            'payment_customer_trans_record_total_money' => $this->payment_customer_trans_record_total_money,
            'payment_customer_trans_record_current_balance' => $this->payment_customer_trans_record_current_balance,
            'payment_customer_trans_record_befor_balance' => $this->payment_customer_trans_record_befor_balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'payment_customer_trans_record_service_card_on', $this->payment_customer_trans_record_service_card_on])
            ->andFilterWhere(['like', 'payment_customer_trans_record_transaction_id', $this->payment_customer_trans_record_transaction_id])
            ->andFilterWhere(['like', 'payment_customer_trans_record_remark', $this->payment_customer_trans_record_remark])
            ->andFilterWhere(['like', 'payment_customer_trans_record_verify', $this->payment_customer_trans_record_verify]);

        return $dataProvider;
    }
}
