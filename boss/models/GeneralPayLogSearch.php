<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GeneralPayLog;

/**
 * GeneralPayLogSearch represents the model behind the search form about `common\models\GeneralPayLog`.
 */
class GeneralPayLogSearch extends GeneralPayLog
{
    public function rules()
    {
        return [
            [['id', 'pay_channel_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['general_pay_log_price'], 'number'],
            [['general_pay_log_shop_name', 'general_pay_log_eo_order_id', 'general_pay_log_transaction_id', 'general_pay_log_status', 'general_pay_log_json_aggregation'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = GeneralPayLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'general_pay_log_price' => $this->general_pay_log_price,
            'pay_channel_id' => $this->pay_channel_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'general_pay_log_shop_name', $this->general_pay_log_shop_name])
            ->andFilterWhere(['like', 'general_pay_log_eo_order_id', $this->general_pay_log_eo_order_id])
            ->andFilterWhere(['like', 'general_pay_log_transaction_id', $this->general_pay_log_transaction_id])
            ->andFilterWhere(['like', 'general_pay_log_status', $this->general_pay_log_status])
            ->andFilterWhere(['like', 'general_pay_log_json_aggregation', $this->general_pay_log_json_aggregation]);

        return $dataProvider;
    }
}
