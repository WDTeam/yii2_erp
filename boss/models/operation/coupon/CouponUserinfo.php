<?php

namespace boss\models\operation\coupon;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\operation\coupon\CouponUserinfo as CouponUserinfoModel;

/**
 * CouponUserinfo represents the model behind the search form about `dbbase\models\operation\coupon\CouponUserinfo`.
 */
class CouponUserinfo extends CouponUserinfoModel
{
    public function rules()
    {
        return [
            [['id', 'customer_id', 'coupon_userinfo_id', 'coupon_userinfo_gettime', 'coupon_userinfo_usetime', 'couponrule_use_end_time', 'system_user_id', 'is_used', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_tel', 'coupon_userinfo_code', 'coupon_userinfo_name', 'order_code', 'system_user_name'], 'safe'],
            [['coupon_userinfo_price'], 'number'],
        ];
    }

    
    /**
     * 根据客户id返回客户姓名
     * @date: 2015-11-12
     * @author: peak pan
     * @return:
     **/
    
    public static function get_customer_name($id)
    {
    	 
    	$customerinfo=\core\models\customer\Customer::getCustomerById($id);
    	 
    	if(isset($customerinfo->customer_name)){
    		
    		return $customerinfo->customer_name;
    	}else{
    		
    		return '无名称';
    	} 
    	 
    }
    
    
    
    
    
    
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CouponUserinfoModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'coupon_userinfo_id' => $this->coupon_userinfo_id,
            'coupon_userinfo_price' => $this->coupon_userinfo_price,
            'coupon_userinfo_gettime' => $this->coupon_userinfo_gettime,
            'coupon_userinfo_usetime' => $this->coupon_userinfo_usetime,
            'couponrule_use_end_time' => $this->couponrule_use_end_time,
            'system_user_id' => $this->system_user_id,
            'is_used' => $this->is_used,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'system_user_id'=>$this->system_user_id,	
            'is_del' => $this->is_del,
        ]);

        
        if($this->system_user_id==0){
        	$query->andFilterWhere(['system_user_id'=>$this->system_user_id,]);
        }else{
        	$query->andFilterWhere(['!=', 'system_user_id',0]);
        }
        
        $query->andFilterWhere(['like', 'customer_tel', $this->customer_tel])
            ->andFilterWhere(['like', 'coupon_userinfo_code', $this->coupon_userinfo_code])
            ->andFilterWhere(['like', 'coupon_userinfo_name', $this->coupon_userinfo_name])
            ->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'system_user_name', $this->system_user_name]);

        return $dataProvider;
    }
}
