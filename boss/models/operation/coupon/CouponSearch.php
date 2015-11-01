<?php

namespace boss\models\operation\coupon;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\operation\coupon\Coupon;

use \core\models\operation\coupon\CouponCode;

/**
 * CouponSearch represents the model behind the search form about `\dbbase\models\coupon\Coupon`.
 */
class CouponSearch extends Coupon
{
	public $coupon_code;
    public function rules()
    {
        return [
            [['id', 'coupon_type', 'coupon_service_type_id', 'coupon_service_id', 'coupon_city_limit', 'coupon_city_id', 'coupon_customer_type', 'coupon_time_type', 'coupon_begin_at', 'coupon_end_at', 'coupon_get_end_at', 'coupon_use_end_days', 'coupon_promote_type', 'coupon_code_num', 'coupon_code_max_customer_num', 'is_disabled', 'created_at', 'updated_at', 'is_del', 'system_user_id'], 'integer'],
            [['coupon_name', 'coupon_type_name', 'coupon_service_type_name', 'coupon_service_name', 'coupon_city_name', 'coupon_customer_type_name', 'coupon_time_type_name', 'coupon_promote_type_name', 'system_user_name'], 'safe'],
            [['coupon_price', 'coupon_order_min_price'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Coupon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'coupon_price' => $this->coupon_price,
            'coupon_type' => $this->coupon_type,
            'coupon_service_type_id' => $this->coupon_service_type_id,
            'coupon_service_id' => $this->coupon_service_id,
            'coupon_city_limit' => $this->coupon_city_limit,
            'coupon_city_id' => $this->coupon_city_id,
            'coupon_customer_type' => $this->coupon_customer_type,
            'coupon_time_type' => $this->coupon_time_type,
            'coupon_begin_at' => $this->coupon_begin_at,
            'coupon_end_at' => $this->coupon_end_at,
            'coupon_get_end_at' => $this->coupon_get_end_at,
            'coupon_use_end_days' => $this->coupon_use_end_days,
            'coupon_promote_type' => $this->coupon_promote_type,
            'coupon_order_min_price' => $this->coupon_order_min_price,
            'coupon_code_num' => $this->coupon_code_num,
            'coupon_code_max_customer_num' => $this->coupon_code_max_customer_num,
            'is_disabled' => $this->is_disabled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
            'system_user_id' => $this->system_user_id,
        ]);

        $query->andFilterWhere(['like', 'coupon_name', $this->coupon_name])
            ->andFilterWhere(['like', 'coupon_type_name', $this->coupon_type_name])
            ->andFilterWhere(['like', 'coupon_service_type_name', $this->coupon_service_type_name])
            ->andFilterWhere(['like', 'coupon_service_name', $this->coupon_service_name])
            ->andFilterWhere(['like', 'coupon_city_name', $this->coupon_city_name])
            ->andFilterWhere(['like', 'coupon_customer_type_name', $this->coupon_customer_type_name])
            ->andFilterWhere(['like', 'coupon_time_type_name', $this->coupon_time_type_name])
            ->andFilterWhere(['like', 'coupon_promote_type_name', $this->coupon_promote_type_name])
            ->andFilterWhere(['like', 'system_user_name', $this->system_user_name]);

		
		if(!empty($this->coupon_code)){
			$coupon_code_arr = CouponCode::findAll(['like', 'coupon_code', $this->coupon_cdoe]);
			$coupon_ids = array();
			if(!empty($coupon_code_arr)){
				foreach($coupon_code_arr as $couponCode){
					array_push($coupon_ids, $couponCode->coupon_id);
				}		
			}
			$query->andFilterWhere(['in', 'id', $coupon_ids]);
		}

        return $dataProvider;
    }
}
