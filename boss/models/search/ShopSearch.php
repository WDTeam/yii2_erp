<?php

namespace boss\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\Shop;

/**
 * ShopSearch represents the model behind the search form about `boss\models\Shop`.
 */
class ShopSearch extends Shop
{
    public function rules()
    {
        return [
            [['id', 'shop_manager_id', 'province_id', 'city_id', 'county_id', 'created_at', 'updated_at', 'is_blacklist', 'audit_status', 'worker_count', 'complain_coutn'], 'integer'],
            [['name', 'street', 'principal', 'tel', 'other_contact', 'bankcard_number', 'account_person', 'opening_bank', 'sub_branch', 'opening_address', 'level'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'shop_manager_id' => $this->shop_manager_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'county_id' => $this->county_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_blacklist' => $this->is_blacklist,
//             'blacklist_time' => $this->blacklist_time,
            'audit_status' => $this->audit_status,
            'worker_count' => $this->worker_count,
            'complain_coutn' => $this->complain_coutn,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'principal', $this->principal])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'other_contact', $this->other_contact])
            ->andFilterWhere(['like', 'bankcard_number', $this->bankcard_number])
            ->andFilterWhere(['like', 'account_person', $this->account_person])
            ->andFilterWhere(['like', 'opening_bank', $this->opening_bank])
            ->andFilterWhere(['like', 'sub_branch', $this->sub_branch])
            ->andFilterWhere(['like', 'opening_address', $this->opening_address])
            ->andFilterWhere(['like', 'level', $this->level]);
        
        $query->orFilterWhere(['like', 'principal', $this->name])
        ->orFilterWhere(['like', 'name', $this->name])
        ->orFilterWhere(['like', 'tel', $this->name]);

        return $dataProvider;
    }
}
