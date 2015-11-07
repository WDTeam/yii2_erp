<?php

namespace core\models\shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\shop\ShopManager;

/**
 * ShopManagerSearch represents the model behind the search form about `boss\models\ShopManager`.
 */
class ShopManagerSearch extends ShopManager
{
    public function rules()
    {
        return [
            [['id', 'province_id', 'city_id', 'county_id', 'bl_type', 'bl_create_time', 'bl_audit', 'bl_expiry_start', 'bl_expiry_end', 'created_at', 'updated_at', 'is_blacklist', 'audit_status', 'shop_count', 'worker_count', 'complain_coutn'], 'integer'],
            [['name', 'street', 'principal', 'tel', 'other_contact', 'bankcard_number', 'account_person', 'opening_bank', 'sub_branch', 'opening_address', 'bl_name', 'bl_number', 'bl_person', 'bl_address', 'bl_photo_url', 'bl_business', 'level'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ShopManager::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if(isset($params['ids'])){
            $query->andFilterWhere(['in', 'id', $params['ids']]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'province_id' => empty($this->province_id)?null:$this->province_id,
            'city_id' => empty($this->city_id)?null:$this->city_id,
            'county_id' => empty($this->county_id)?null:$this->county_id,
            'bl_type' => $this->bl_type,
            'bl_create_time' => $this->bl_create_time,
            'bl_audit' => $this->bl_audit,
            'bl_expiry_start' => $this->bl_expiry_start,
            'bl_expiry_end' => $this->bl_expiry_end,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_blacklist' => $this->is_blacklist,
            'audit_status' => $this->audit_status,
            'shop_count' => $this->shop_count,
            'worker_count' => $this->worker_count,
            'complain_coutn' => $this->complain_coutn,
        ]);

        $query
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'other_contact', $this->other_contact])
            ->andFilterWhere(['like', 'bankcard_number', $this->bankcard_number])
            ->andFilterWhere(['like', 'account_person', $this->account_person])
            ->andFilterWhere(['like', 'opening_bank', $this->opening_bank])
            ->andFilterWhere(['like', 'sub_branch', $this->sub_branch])
            ->andFilterWhere(['like', 'opening_address', $this->opening_address])
            ->andFilterWhere(['like', 'bl_name', $this->bl_name])
            ->andFilterWhere(['like', 'bl_number', $this->bl_number])
            ->andFilterWhere(['like', 'bl_person', $this->bl_person])
            ->andFilterWhere(['like', 'bl_address', $this->bl_address])
            ->andFilterWhere(['like', 'bl_photo_url', $this->bl_photo_url])
            ->andFilterWhere(['like', 'bl_business', $this->bl_business])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['or',
                ['like', 'principal', $this->name],
                ['like', 'name', $this->name],
                ['like', 'tel', $this->name]
            ]);

        return $dataProvider;
    }
}
