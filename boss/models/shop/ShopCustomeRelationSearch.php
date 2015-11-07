<?php

namespace boss\models\shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\shop\ShopCustomeRelation;

/**
 * ShopCustomeRelationSearch represents the model behind the search form about `dbbase\models\shop\ShopCustomeRelation`.
 */
class ShopCustomeRelationSearch extends ShopCustomeRelation
{
    public function rules()
    {
        return [
            [['id', 'system_user_id', 'baseid', 'shopid', 'shop_manager_id', 'stype', 'is_del'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ShopCustomeRelation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'system_user_id' => $this->system_user_id,
            'baseid' => $this->baseid,
            'shopid' => $this->shopid,
            'shop_manager_id' => $this->shop_manager_id,
            'stype' => $this->stype,
            'is_del' => $this->is_del,
        ]);

        return $dataProvider;
    }
}
