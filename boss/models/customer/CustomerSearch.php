<?php

namespace boss\models\customer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use core\models\customer\Customer;
use core\models\customer\CustomerAddress;

/**
 * CustomerSearch represents the model behind the search form about `dbbase\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $time_begin;
    public $time_end;
	public $customer_global_search;
    public function rules()
    {
        return [
            [['id', 'customer_sex', 'customer_birth', 'operation_area_id', 'operation_city_id', 'customer_level', 'customer_complaint_times', 'customer_login_time', 'customer_is_vip', 'is_del', 'created_at'], 'integer'],
            [['customer_name', 'customer_photo', 'customer_phone', 'customer_email', 'customer_login_ip',], 'safe'],
            [['time_begin', 'time_end'], 'date'],
			[['customer_global_search'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $sort_str = 'created_at desc';
//        if(!empty($_REQUEST['CustomerSort'])){
//            $sort_str = $_REQUEST['CustomerSort']['field'].' '.$_REQUEST['CustomerSort']['order'];
//        }
//		$query = (new \yii\db\Query())->select(['c.* as *', 'count(oec.*) as count'])->from(['c' => //'{{%customer}}'])->leftJoin(['oec' => '{{%order_ext_customer}}'], 'oec.customer_id = c.id')-//>orderBy($sort_str);
        $query = Customer::find()->orderBy($sort_str);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 20,
			],
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
            'customer_level' => $this->customer_level,
            'customer_complaint_times' => $this->customer_complaint_times,
            'customer_platform_version' => $this->customer_platform_version,
            'customer_app_version' => $this->customer_app_version,
            'customer_mac' => $this->customer_mac,
            'customer_login_time' => $this->customer_login_time,
            //'customer_is_vip' => $this->customer_is_vip,
            // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

		if(isset($this->customer_is_vip)){
			if($this->customer_is_vip != -1){
				$query->andFilterWhere(['customer_is_vip' => $this->customer_is_vip]);
			}
		}

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_photo', $this->customer_photo])
            ->andFilterWhere(['like', 'customer_phone', $this->customer_phone])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'customer_login_ip', $this->customer_login_ip]);

        if ($this->time_begin) {
            $query->andFilterWhere(['>', 'created_at', strtotime($this->time_begin)]);
        }

		if ($this->time_end) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->time_end) + 24 * 3600]);
        }

		if($this->customer_global_search){
			$customer_global_search = $this->customer_global_search;
			$customerIdsArr = CustomerAddress::find()->select(['customer_id'])
				->where(['like', 'operation_province_name', $customer_global_search])
				->orWhere(['like', 'operation_city_name', $customer_global_search])
				->orWhere(['like', 'operation_area_name', $customer_global_search])
				->orWhere(['like', 'customer_address_detail', $customer_global_search])
				->asArray()->all();
			$customer_ids = [];
			foreach ($customerIdsArr as $customerIds)
			{
				array_push($customer_ids, $customerIds['customer_id']);
			}
			$query->orFilterWhere(['in', 'id', $customer_ids])
				->orFilterWhere(['like', 'customer_phone', $this->customer_global_search]);
		}
        return $dataProvider;
    }
	

	public function test(){
		$sort = \Yii::$app->request->get('sort', 'created_at');
		$sortArr = array();
        if ($sort == 'created_at') {
            $sortArr = [
				'defaultOrder' => [
				    'created_at' => SORT_DESC,
				]
			];
        }else if ($sort == 'order_count') {
            $sortArr = [
				'defaultOrder' => [
				    'created_at' => SORT_ASC,
				]
			];
        }else{
            $sortArr = [
				'defaultOrder' => [
				    'created_at' => SORT_DESC,
				]
			];
        }
	}
}






























