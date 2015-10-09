<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CustomerTransRecord;

/**
 * CustomerTransRecordSearch represents the model behind the search form about `common\models\CustomerTransRecord`.
 */
class CustomerTransRecordSearch extends CustomerTransRecord
{
    public function rules()
    {
        return [
            [['id', 'customer_id', 'order_id', 'order_channel_id', 'customer_trans_record_order_channel', 'pay_channel_id', 'customer_trans_record_pay_channel', 'customer_trans_record_mode', 'customer_trans_record_mode_name', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_trans_record_promo_code_money', 'customer_trans_record_coupon_money', 'customer_trans_record_cash', 'customer_trans_record_pre_pay', 'customer_trans_record_online_pay', 'customer_trans_record_online_balance_pay', 'customer_trans_record_online_service_card_pay', 'customer_trans_record_compensate_money', 'customer_trans_record_refund_money', 'customer_trans_record_money', 'customer_trans_record_order_total_money', 'customer_trans_record_total_money', 'customer_trans_record_current_balance', 'customer_trans_record_befor_balance'], 'number'],
            [['customer_trans_record_online_service_card_on', 'customer_trans_record_transaction_id', 'customer_trans_record_remark', 'customer_trans_record_verify'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerTransRecord::find();

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
            'customer_trans_record_order_channel' => $this->customer_trans_record_order_channel,
            'pay_channel_id' => $this->pay_channel_id,
            'customer_trans_record_pay_channel' => $this->customer_trans_record_pay_channel,
            'customer_trans_record_mode' => $this->customer_trans_record_mode,
            'customer_trans_record_mode_name' => $this->customer_trans_record_mode_name,
            'customer_trans_record_promo_code_money' => $this->customer_trans_record_promo_code_money,
            'customer_trans_record_coupon_money' => $this->customer_trans_record_coupon_money,
            'customer_trans_record_cash' => $this->customer_trans_record_cash,
            'customer_trans_record_pre_pay' => $this->customer_trans_record_pre_pay,
            'customer_trans_record_online_pay' => $this->customer_trans_record_online_pay,
            'customer_trans_record_online_balance_pay' => $this->customer_trans_record_online_balance_pay,
            'customer_trans_record_online_service_card_pay' => $this->customer_trans_record_online_service_card_pay,
            'customer_trans_record_compensate_money' => $this->customer_trans_record_compensate_money,
            'customer_trans_record_refund_money' => $this->customer_trans_record_refund_money,
            'customer_trans_record_money' => $this->customer_trans_record_money,
            'customer_trans_record_order_total_money' => $this->customer_trans_record_order_total_money,
            'customer_trans_record_total_money' => $this->customer_trans_record_total_money,
            'customer_trans_record_current_balance' => $this->customer_trans_record_current_balance,
            'customer_trans_record_befor_balance' => $this->customer_trans_record_befor_balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'customer_trans_record_online_service_card_on', $this->customer_trans_record_online_service_card_on])
            ->andFilterWhere(['like', 'customer_trans_record_transaction_id', $this->customer_trans_record_transaction_id])
            ->andFilterWhere(['like', 'customer_trans_record_remark', $this->customer_trans_record_remark])
            ->andFilterWhere(['like', 'customer_trans_record_verify', $this->customer_trans_record_verify]);

        return $dataProvider;
    }
}
