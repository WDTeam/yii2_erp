<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationCity;

/**
 * OperationCitySearch represents the model behind the search form about `boss\models\operation\OperationCity`.
 */
class OperationCitySearch extends OperationCity
{
    public function rules()
    {
        return [
            [['id', 'province_id', 'city_id', 'operation_city_is_online', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['province_name', 'city_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationCity::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'operation_city_is_online' => $this->operation_city_is_online,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'province_name', $this->province_name])
            ->andFilterWhere(['like', 'city_name', $this->city_name]);

        return $dataProvider;
    }
}
