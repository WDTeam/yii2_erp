<?php

namespace boss\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    public function rules()
    {
        return [
            [['id', 'order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'order_before_status_dict_id', 'order_status_dict_id', 'order_service_type_id', 'order_src_id', 'channel_id', 'customer_id', 'order_booked_begin_time', 'order_booked_end_time', 'order_booked_count', 'address_id', 'order_booked_worker_id', 'order_pay_type', 'pay_channel_id', 'card_id', 'coupon_id', 'promotion_id', 'worker_id', 'worker_type_id', 'order_worker_distri_type', 'order_lock_status', 'comment_id', 'invoice_id', 'checking_id', 'shop_id', 'admin_id', 'isdel'], 'integer'],
            [['order_before_status_name', 'order_status_name', 'order_service_type_name', 'order_src_name', 'order_channel_name', 'order_channel_order_num', 'order_customer_phone', 'order_address', 'order_customer_need', 'order_customer_memo', 'order_cs_memo', 'order_pay_channel_name', 'order_pay_flow_num', 'order_worker_type_name', 'order_worker_bonus_detail'], 'safe'],
            [['order_money', 'order_pay_money', 'order_use_acc_balance', 'order_use_card_money', 'order_use_coupon_money', 'order_use_promotion_money', 'order_worker_bonus_money', 'order_pop_pay_money'], 'number'],
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
            'order_before_status_dict_id' => $this->order_before_status_dict_id,
            'order_status_dict_id' => $this->order_status_dict_id,
            'order_service_type_id' => $this->order_service_type_id,
            'order_src_id' => $this->order_src_id,
            'channel_id' => $this->channel_id,
            'customer_id' => $this->customer_id,
            'order_booked_begin_time' => $this->order_booked_begin_time,
            'order_booked_end_time' => $this->order_booked_end_time,
            'order_booked_count' => $this->order_booked_count,
            'address_id' => $this->address_id,
            'order_money' => $this->order_money,
            'order_booked_worker_id' => $this->order_booked_worker_id,
            'order_pay_type' => $this->order_pay_type,
            'pay_channel_id' => $this->pay_channel_id,
            'order_pay_money' => $this->order_pay_money,
            'order_use_acc_balance' => $this->order_use_acc_balance,
            'card_id' => $this->card_id,
            'order_use_card_money' => $this->order_use_card_money,
            'coupon_id' => $this->coupon_id,
            'order_use_coupon_money' => $this->order_use_coupon_money,
            'promotion_id' => $this->promotion_id,
            'order_use_promotion_money' => $this->order_use_promotion_money,
            'worker_id' => $this->worker_id,
            'worker_type_id' => $this->worker_type_id,
            'order_worker_distri_type' => $this->order_worker_distri_type,
            'order_lock_status' => $this->order_lock_status,
            'comment_id' => $this->comment_id,
            'order_worker_bonus_money' => $this->order_worker_bonus_money,
            'order_pop_pay_money' => $this->order_pop_pay_money,
            'invoice_id' => $this->invoice_id,
            'checking_id' => $this->checking_id,
            'shop_id' => $this->shop_id,
            'admin_id' => $this->admin_id,
            'isdel' => $this->isdel,
        ]);

        $query->andFilterWhere(['like', 'order_before_status_name', $this->order_before_status_name])
            ->andFilterWhere(['like', 'order_status_name', $this->order_status_name])
            ->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
            ->andFilterWhere(['like', 'order_src_name', $this->order_src_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_channel_order_num', $this->order_channel_order_num])
            ->andFilterWhere(['like', 'order_customer_phone', $this->order_customer_phone])
            ->andFilterWhere(['like', 'order_address', $this->order_address])
            ->andFilterWhere(['like', 'order_customer_need', $this->order_customer_need])
            ->andFilterWhere(['like', 'order_customer_memo', $this->order_customer_memo])
            ->andFilterWhere(['like', 'order_cs_memo', $this->order_cs_memo])
            ->andFilterWhere(['like', 'order_pay_channel_name', $this->order_pay_channel_name])
            ->andFilterWhere(['like', 'order_pay_flow_num', $this->order_pay_flow_num])
            ->andFilterWhere(['like', 'order_worker_type_name', $this->order_worker_type_name])
           // ->andFilterWhere(['<=', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'order_worker_bonus_detail', $this->order_worker_bonus_detail]);

        return $dataProvider;
    }
}
