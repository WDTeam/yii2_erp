<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServiceCardSellRecord;

/**
 * OperationServiceCardSellRecordSearch represents the model behind the search form about `\boss\models\operation\OperationServiceCardSellRecord`.
 */
class OperationServiceCardSellRecordSearch extends OperationServiceCardSellRecord
{
	
    public function rules()
    {
        return [
            [['id', 'customer_id', 'customer_phone', 'service_card_info_id', 'service_card_sell_record_channel_id', 'service_card_sell_record_status', 'customer_trans_record_pay_mode', 'pay_channel_id', 'customer_trans_record_pay_account', 'customer_trans_record_paid_at','created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_sell_record_code', 'service_card_info_name', 'service_card_sell_record_channel_name', 'customer_trans_record_pay_channel', 'customer_trans_record_transaction_id','customer_trans_record_paid_at_min', 'customer_trans_record_paid_at_max',], 'safe'],
            [['service_card_sell_record_money', 'customer_trans_record_pay_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServiceCardSellRecord::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
			
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_phone' => $this->customer_phone,
            'service_card_info_id' => $this->service_card_info_card_id,
            'service_card_sell_record_money' => $this->service_card_sell_record_money,
            'service_card_sell_record_channel_id' => $this->service_card_sell_record_channel_id,
            'service_card_sell_record_status' => $this->service_card_sell_record_status,
            'customer_trans_record_pay_mode' => $this->customer_trans_record_pay_mode,
            'pay_channel_id' => $this->pay_channel_id,
            'customer_trans_record_pay_money' => $this->customer_trans_record_pay_money,
            'customer_trans_record_pay_account' => $this->customer_trans_record_pay_account,
//            'customer_trans_record_paid_at' => $this->customer_trans_record_paid_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);
		$query->andFilterWhere(['>=','customer_trans_record_paid_at',strtotime($this->customer_trans_record_paid_at_min)])
			->andFilterWhere(['<=','customer_trans_record_paid_at',strtotime($this->customer_trans_record_paid_at_max)]);

        $query->andFilterWhere(['like', 'service_card_sell_record_code', $this->service_card_sell_record_code])
            ->andFilterWhere(['like', 'service_card_info_name', $this->service_card_info_name])
            ->andFilterWhere(['like', 'service_card_sell_record_channel_name', $this->service_card_sell_record_channel_name])
            ->andFilterWhere(['like', 'customer_trans_record_pay_channel', $this->customer_trans_record_pay_channel])
            ->andFilterWhere(['like', 'customer_trans_record_transaction_id', $this->customer_trans_record_transaction_id]);

        return $dataProvider;
    }
}
