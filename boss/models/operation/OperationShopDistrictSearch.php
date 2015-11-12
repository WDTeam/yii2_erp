<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationShopDistrict;

/**
 * OperationShopDistrictSearch represents the model behind the search form about `boss\models\operation\OperationShopDistrict`.
 */
class OperationShopDistrictSearch extends OperationShopDistrict
{
    public function rules()
    {
        return [
            [['id', 'operation_city_id', 'operation_area_id', 'operation_shop_district_status', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['operation_shop_district_name', 'operation_city_name', 'operation_area_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationShopDistrict::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'operation_city_id' => $this->operation_city_id,
            'operation_area_id' => $this->operation_area_id,
            'operation_shop_district_status' => $this->operation_shop_district_status,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'operation_shop_district_name', $this->operation_shop_district_name])
            ->andFilterWhere(['like', 'operation_city_name', $this->operation_city_name])
            ->andFilterWhere(['like', 'operation_area_name', $this->operation_area_name]);

        return $dataProvider;
    }
}
