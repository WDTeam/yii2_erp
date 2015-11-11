<?php

namespace core\models\shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\shop\Shop;

/**
 * ShopSearch represents the model behind the search form about `boss\models\Shop`.
 */
class ShopSearch extends Shop
{
    public function rules()
    {
        return [
            [['id', 'shop_manager_id', 'province_id', 'city_id', 'county_id', 'operation_shop_district_id', 'created_at', 'updated_at', 'is_blacklist', 'audit_status', 'worker_count', 'complain_coutn'], 'integer'],
            [['name', 'street', 'principal', 'tel', 'other_contact', 'bankcard_number', 'account_person', 'opening_bank', 'sub_branch', 'opening_address', 'level', 'isdel'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Shop::find();
        $query->where('isdel is NULL OR isdel=0');
        if(isset($params['ids'])){
            $query->andFilterWhere(['in', 'id', $params['ids']]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'shop_manager_id' => $this->shop_manager_id,
            'province_id' => empty($this->province_id)?null:$this->province_id,
            'city_id' => empty($this->city_id)?null:$this->city_id,
            'county_id' => empty($this->county_id)?null:$this->county_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_blacklist' => $this->is_blacklist,
            'audit_status' => $this->audit_status,
            'worker_count' => $this->worker_count,
            'complain_coutn' => $this->complain_coutn,
            'operation_shop_district_id'=>$this->operation_shop_district_id,
        ]);

        $query
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'other_contact', $this->other_contact])
            ->andFilterWhere(['like', 'bankcard_number', $this->bankcard_number])
            ->andFilterWhere(['like', 'account_person', $this->account_person])
            ->andFilterWhere(['like', 'opening_bank', $this->opening_bank])
            ->andFilterWhere(['like', 'sub_branch', $this->sub_branch])
            ->andFilterWhere(['like', 'opening_address', $this->opening_address])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['or',
                ['like', 'principal', $this->name],
                ['like', 'name', $this->name],
                ['like', 'tel', $this->name]
            ]);

        return $dataProvider;
    }
}
