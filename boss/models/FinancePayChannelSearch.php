<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinancePayChannel;

/**
 * FinancePayChannelSearch represents the model behind the search form about `common\models\FinancePayChannel`.
 */
class FinancePayChannelSearch extends FinancePayChannel
{
    public function rules()
    {
        return [
            [['id', 'finance_pay_channel_rank', 'finance_pay_channel_is_lock', 'create_time', 'is_del'], 'integer'],
            [['finance_pay_channel_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinancePayChannel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_pay_channel_rank' => $this->finance_pay_channel_rank,
            'finance_pay_channel_is_lock' => $this->finance_pay_channel_is_lock,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_pay_channel_name', $this->finance_pay_channel_name]);

        return $dataProvider;
    }
}
