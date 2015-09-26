<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinancePopOrder;
use common\models\Order;
/**
 * FinancePopOrderSearch represents the model behind the search form about `common\models\FinancePopOrder`.
 */
class FinancePopOrderSearch extends FinancePopOrder
{
    public function rules()
    {
        return [
            [['id', 'finance_order_channel_id', 'finance_pay_channel_id', 'finance_pop_order_worker_uid', 'finance_pop_order_booked_time', 'finance_pop_order_booked_counttime', 'finance_pop_order_coupon_id', 'finance_pop_order_order_type', 'finance_pop_order_finance_isok', 'finance_pop_order_order_time', 'finance_pop_order_pay_time', 'finance_pop_order_pay_status', 'finance_pop_order_check_id', 'finance_pop_order_finance_time', 'create_time', 'is_del'], 'integer'],
            [['finance_pop_order_number', 'finance_order_channel_title', 'finance_pay_channel_title', 'finance_pop_order_customer_tel', 'finance_pop_order_order2', 'finance_pop_order_channel_order', 'finance_pop_order_pay_title'], 'safe'],
            [['finance_pop_order_sum_money', 'finance_pop_order_coupon_count', 'finance_pop_order_discount_pay', 'finance_pop_order_reality_pay'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
    * 获取 对账状态
    * @date: 2015-9-26
    * @author: peak pan
    * @return:finance_pop_order_pay_status 1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
    * 第一位是表头对应的信息 
    * 第二位是一个表格的对应的信息
    **/
    public function PopOrderstatus($alinfo,$value)
    {
//     	'order_channel_order_num' => string '1' (length=1)
//     	'order_money' => string '3' (length=1)
//     	'order_channel_promote' => string '6' (length=1)

	    foreach ($alinfo as $rt){
	    		$tuty=$rt['finance_header_where'];
	    		$tyyu[$tuty]=$rt['finance_header_key'];
	    }
	    
    	$hder_info=$tyyu;
    	foreach ($value as $rtyy){
    		$dateinfo[]=$rtyy;
    	}
    	
    	//对应系统订单号
    	$getorder=$dateinfo[$hder_info['order_channel_order_num']];
    	
    	//对应系统金额
    	$getorder_money=$dateinfo[$hder_info['order_money']];
    	
    	//对应数据表折扣金额
    	$promote=$dateinfo[$hder_info['order_channel_promote']];
    	
		//打开订单库开始比对
		//$orderinfo= new Order;
		$alinfo=Order::find()
		->select('order_status_name,channel_id,order_channel_name,customer_id,order_customer_phone,order_booked_begin_time,order_booked_end_time,order_money,order_booked_worker_id,order_pay_type,pay_channel_id,order_pay_channel_name,order_use_coupon_money,order_channel_order_num,order_customer_phone,worker_id,order_use_promotion_money,order_code,order_service_type_id,order_pay_money,created_at')
		->andWhere(['=','order_channel_order_num',$getorder])
		->asArray()->one();
		
		if ($alinfo) {
			//比对金额
			//1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
			if($alinfo['order_money']==$getorder_money){ $alinfo['finance_pop_order_pay_status_type']=1;  }else{
				$alinfo['finance_pop_order_pay_status_type']=4;
			}
				
		}else {
		//三有我没有
	    $alinfo['order_channel_order_num']=$getorder;
		$alinfo['order_money']=$getorder_money;	
		$alinfo['order_use_coupon_money']=$promote;
		$alinfo['finance_pop_order_pay_status_type']=2;
		}
    	return $alinfo;
    }  
    
    
    
    
    
    
    
    
    
    
    
    
    public function search($params)
    {
        $query = FinancePopOrder::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_order_channel_id' => $this->finance_order_channel_id,
            'finance_pay_channel_id' => $this->finance_pay_channel_id,
            'finance_pop_order_worker_uid' => $this->finance_pop_order_worker_uid,
            'finance_pop_order_booked_time' => $this->finance_pop_order_booked_time,
            'finance_pop_order_booked_counttime' => $this->finance_pop_order_booked_counttime,
            'finance_pop_order_sum_money' => $this->finance_pop_order_sum_money,
            'finance_pop_order_coupon_count' => $this->finance_pop_order_coupon_count,
            'finance_pop_order_coupon_id' => $this->finance_pop_order_coupon_id,
            'finance_pop_order_order_type' => $this->finance_pop_order_order_type,
            'finance_pop_order_status' => $this->finance_pop_order_status,
            'finance_pop_order_finance_isok' => $this->finance_pop_order_finance_isok,
            'finance_pop_order_discount_pay' => $this->finance_pop_order_discount_pay,
            'finance_pop_order_reality_pay' => $this->finance_pop_order_reality_pay,
            'finance_pop_order_order_time' => $this->finance_pop_order_order_time,
            'finance_pop_order_pay_time' => $this->finance_pop_order_pay_time,
            'finance_pop_order_pay_status' => $this->finance_pop_order_pay_status,
            'finance_pop_order_check_id' => $this->finance_pop_order_check_id,
            'finance_pop_order_finance_time' => $this->finance_pop_order_finance_time,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_pop_order_number', $this->finance_pop_order_number])
            ->andFilterWhere(['like', 'finance_order_channel_title', $this->finance_order_channel_title])
            ->andFilterWhere(['like', 'finance_pay_channel_title', $this->finance_pay_channel_title])
            ->andFilterWhere(['like', 'finance_pop_order_customer_tel', $this->finance_pop_order_customer_tel])
            ->andFilterWhere(['like', 'finance_pop_order_order2', $this->finance_pop_order_order2])
            ->andFilterWhere(['like', 'finance_pop_order_channel_order', $this->finance_pop_order_channel_order])
            ->andFilterWhere(['like', 'finance_pop_order_pay_title', $this->finance_pop_order_pay_title]);

        return $dataProvider;
    }
}
