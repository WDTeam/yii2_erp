<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Customer;

/**
 * CustomerSearch represents the model behind the search form about `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $time_begin;
    public $time_end;
    public function rules()
    {
        return [
            [['id', 'customer_sex', 'customer_birth', 'operation_area_id', 'operation_city_id', 'general_region_id', 'customer_score', 'customer_level', 'customer_complaint_times', 'customer_src', 'channal_id', 'platform_id', 'customer_login_time', 'customer_is_vip', 'is_del'], 'integer'],
            [['customer_name', 'customer_photo', 'customer_phone', 'customer_email', 'customer_live_address_detail', 'customer_login_ip', 'customer_del_reason'], 'safe'],
            [['customer_balance'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Customer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_sex' => $this->customer_sex,
            'customer_birth' => $this->customer_birth,
            'operation_area_id' => $this->operation_area_id,
            'operation_city_id' => $this->operation_city_id,
            'general_region_id' => $this->general_region_id,
            'customer_balance' => $this->customer_balance,
            'customer_score' => $this->customer_score,
            'customer_level' => $this->customer_level,
            'customer_complaint_times' => $this->customer_complaint_times,
            'customer_src' => $this->customer_src,
            'channal_id' => $this->channal_id,
            'platform_id' => $this->platform_id,
            'customer_platform_version' => $this->customer_platform_version,
            'customer_app_version' => $this->customer_app_version,
            'customer_mac' => $this->customer_mac,
            'customer_login_time' => $this->customer_login_time,
            'customer_is_vip' => $this->customer_is_vip,
            // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_photo', $this->customer_photo])
            ->andFilterWhere(['like', 'customer_phone', $this->customer_phone])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'customer_live_address_detail', $this->customer_live_address_detail])
            ->andFilterWhere(['like', 'customer_login_ip', $this->customer_login_ip])
            ->andFilterWhere(['like', 'customer_del_reason', $this->customer_del_reason]);
            
         // $query->andFilterWhere(['>', 'created_at', strtotime($this->time_begin)])
         //    ->andFilterWhere(['<', 'created_at', strtotime($this->time_end)]);

            // var_dump($params);
            // var_dump($this->time_begin);
            // var_dump($this->time_end);
            // exit();
        if (strtotime($this->time_end) > 0) {
            // var_dump($params);
            // var_dump($this->time_begin);
            // var_dump($this->time_end);
            // exit();
            $query->andFilterWhere(['>', 'created_at', strtotime($this->time_begin)])
            ->andFilterWhere(['<', 'created_at', strtotime($this->time_end)]);
        }

        $query->orFilterWhere(['like', 'customer_name', $this->customer_name])
            ->orFilterWhere(['like', 'customer_phone', $this->customer_name]);


        return $dataProvider;
    }
}
