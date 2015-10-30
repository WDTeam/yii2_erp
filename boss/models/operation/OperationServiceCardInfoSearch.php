<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationServiceCardInfo;

/**
 * OperationServiceCardInfoSearch represents the model behind the search form about `\boss\models\operation\OperationServiceCardInfo`.
 */
class OperationServiceCardInfoSearch extends OperationServiceCardInfo
{
    public function rules()
    {
        return [
            [['id', 'service_card_info_card_type', 'service_card_info_card_level', 'service_card_info_use_scope', 'service_card_info_valid_days', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_info_name'], 'safe'],
            [['service_card_info_value', 'service_card_info_rebate_value'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationServiceCardInfo::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'service_card_info_card_type' => $this->service_card_info_card_type,
            'service_card_info_card_level' => $this->service_card_info_card_level,
            'service_card_info_value' => $this->service_card_info_value,
            'service_card_info_rebate_value' => $this->service_card_info_rebate_value,
            'service_card_info_use_scope' => $this->service_card_info_use_scope,
            'service_card_info_valid_days' => $this->service_card_info_valid_days,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'service_card_info_name', $this->service_card_info_name]);

        return $dataProvider;
    }
}
