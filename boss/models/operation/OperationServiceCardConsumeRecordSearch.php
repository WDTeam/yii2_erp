<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServiceCardConsumeRecord;

/**
 * OperationServiceCardConsumeRecordSearch represents the model behind the search form about `\boss\models\operation\OperationServiceCardConsumeRecord`.
 */
class OperationServiceCardConsumeRecordSearch extends OperationServiceCardConsumeRecord
{
    public function rules()
    {
        return [
            [['id', 'customer_id', 'customer_trans_record_transaction_id', 'order_id', 'service_card_with_customer_id', 'service_card_consume_record_consume_type', 'service_card_consume_record_business_type', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['order_code', 'service_card_with_customer_code'], 'safe'],
            [['service_card_consume_record_front_money', 'service_card_consume_record_behind_money', 'service_card_consume_record_use_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServiceCardConsumeRecord::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_trans_record_transaction_id' => $this->customer_trans_record_transaction_id,
            'order_id' => $this->order_id,
            'service_card_with_customer_id' => $this->service_card_with_customer_id,
            'service_card_consume_record_front_money' => $this->service_card_consume_record_front_money,
            'service_card_consume_record_behind_money' => $this->service_card_consume_record_behind_money,
            'service_card_consume_record_consume_type' => $this->service_card_consume_record_consume_type,
            'service_card_consume_record_business_type' => $this->service_card_consume_record_business_type,
            'service_card_consume_record_use_money' => $this->service_card_consume_record_use_money,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'service_card_with_customer_code', $this->service_card_with_customer_code]);

        return $dataProvider;
    }
}
