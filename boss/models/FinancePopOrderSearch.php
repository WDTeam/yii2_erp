<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinancePopOrder;
use common\models\FinanceHeader;
use common\models\Order;
use common\models\GeneralPay;
use core\models\Customer;




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
            [['finance_pop_order_sum_money', 'finance_pop_order_coupon_count', 'finance_pop_order_discount_pay', 'finance_pop_order_reality_pay','finance_pop_order_pay_status_type'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    
    /**
    * 处理时间函数
    * @date: 2015-9-27
    * @author: peak pan
    * @return:
    **/
    public static  function alltime($time)
    {
    	if($time==0 || $time==""){
    	$name='未知';	
    	}else{
    	$name=date('Y-m-d H:i:s',$time);
    	}
    	return $name;
    }
    
    public static  function alltimecount($time)
    {
    	if($time==""){
    		$name='未知';
    	}else{
    		$name=$time;
    	}
    	return $name;
    }
    
    
    
    
    
    
    
    public static  function selsect_isstatus($id)
    {
     switch ($id)
				{	
				case 1:
				  return '<font color="red">充值</font>';
				  break;  
				case 2:
				  return '<font color="blue">余额支付</font>';
				  break; 
				case 3:
				  return '<font color="green">在线支付</font>';
				  break;
				case 4:
				  return '<font color="green">退款</font>';
				  break;
				  case 5:
				  	return '<font color="green">赔偿</font>';
				  	break;
				}
    } 
    
    
    public static  function is_finance($date)
    {
    	if($date==0 || $date==""){
    		return '<font color="red">未处理</font>';
    	}else{
    		return '<font color="blue">已处理</font>';
    	}
    }
    
    public static  function is_orderstatus($id)
    {
   			 switch ($id)
				{	
				case 1:
				  return '<font color="red">对账成功</font>';
				  break;  
				case 2:
				  return '<font color="blue">你有我没</font>';
				  break; 
				case 3:
				  return '<font color="green">我有你没</font>';
				  break;
				case 4:
				  return '<font color="green">金额不对</font>';
				  break;
				  case 5:
				  	return '<font color="green">状态不对</font>';
				  	break;
				  	case 6:
				  		return '<font color="green">我不知道</font>';
				  		break;
	 
				}

    }
    
    
    
    
    
    public static  function sum_money($date)
    {
    	if($date==0 || $date==""){
    		return '0';
    	}else{
    		return $date;
    	}
    }
    
    
    
    
    
    public   function defaultcss($a,$b)
    {
    	if($a==$b){	
    	return 'info';
    	}else {
    	return 'default';	
    	}
    	
    }
    
   /**
   * 验证上传的是否是正确的exl
   * @date: 2015-9-27
   * @author: peak pan
   * @return:$headerData 表头数据   $channelid 对应上传的id
   **/
    public function id_header($headerData,$channelid)
    {
    
    	$alinfo=FinanceHeader::find()
    	->select('finance_header_name')
    	->andWhere(['=','finance_order_channel_id',$channelid])
    	->asArray()->all();
    	foreach ($alinfo as $aliindata){
    		$tyyu[]=$aliindata['finance_header_name'];
    	
    	}
    	
    	if(count($tyyu)==count($headerData)){
		foreach ($headerData as $rtyes){
			if(!in_array($rtyes,$tyyu)){
				//比对的字符串不对
				return true;
			}
		}
    		
    	}else {
    		//长度不对
    		return true; 
    	}	
    }
    
    
    /**
    * 获取 对账状态
    * @date: 2015-9-26
    * @author: peak pan
    * @return:finance_pop_order_pay_status 1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
    * 第一位是表头对应的信息 
    * 第二位是一个表格的对应的信息数据
    **/
    public function PopOrderstatus($alinfo,$value,$channelid)
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
    	//$promote=$dateinfo[$hder_info['order_channel_promote']];
    	
		//打开订单库开始比对
    	//订单对账	
    	$alinfo=Order::find()
    		->select('order_status_name,channel_id,order_channel_name,customer_id,order_customer_phone,order_booked_begin_time,order_booked_end_time,order_money,order_booked_worker_id,order_pay_type,pay_channel_id,order_pay_channel_name,order_use_coupon_money,order_channel_order_num,order_customer_phone,worker_id,order_use_promotion_money,order_code,order_service_type_id,order_pay_money,created_at,coupon_id,order_before_status_dict_id')
    		->andWhere(['=','order_channel_order_num',$getorder])
    		->asArray()->one();
    	
		if ($alinfo) {
			//比对金额
			//1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
			if($alinfo['order_money']==$getorder_money){ 
				$alinfo['finance_pop_order_pay_status_type']=1;
			  }
		}else {
			//在订单表查询无数据 1 确实没有 2视为充值订单
			if($getorder_money >=1000){
				//查询胜强的充值表
				//查询存在
				$alinfo_es=GeneralPay::find()
				->select('general_pay_status,customer_id,created_at,general_pay_money,general_pay_source,general_pay_transaction_id,general_pay_mode')
				->andWhere(['=','order_channel_order_num',$getorder])
				->asArray()->one();
				if($alinfo_es){
					//存在
					//开始比对金额
					if($alinfo_es['order_money']==$getorder_money){
					//金额比对成功
						if($alinfo_es['general_pay_status']==1){
							$status='成功';
						}else{
							$status='失败';
						}
						//通过客户uid获取客户资料
						$userinfo=Customer::getCustomerById($alinfo_es['customer_id']);
						$alinfo['order_status_name']=$status;
						$alinfo['channel_id']=$channelid;
						$alinfo['order_channel_name']='充值订单';
						$alinfo['customer_id']=0;
						$alinfo['order_customer_phone']=$userinfo->customer_phone; //用户手机号 通过用户id调取用户信息
						$alinfo['order_booked_begin_time']=$alinfo_es['created_at'];
						$alinfo['order_booked_end_time']=$alinfo_es['created_at'];
						$alinfo['order_money']=$alinfo_es['general_pay_money'];
						$alinfo['order_booked_worker_id']=0;
						$alinfo['order_pay_type']=$alinfo_es['general_pay_source'];
						$alinfo['pay_channel_id']=0;
						$alinfo['order_pay_channel_name']=0;
						$alinfo['order_use_coupon_money']=0;
						$alinfo['order_channel_order_num']=$alinfo_es['general_pay_transaction_id']; //第三方订单号
						$alinfo['worker_id']=0;
						$alinfo['order_use_promotion_money']=0;
						$alinfo['order_code']=0;
						$alinfo['order_service_type_id']=2;//订单类型 1 消费订单 2 充值订单
						$alinfo['order_pay_money']=$alinfo_es['general_pay_money'];//实际收款
						$alinfo['created_at']=$alinfo_es['created_at'];
						$alinfo['coupon_id']=0;
						$alinfo['order_before_status_dict_id']=$alinfo_es['general_pay_mode']; //支付状态交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿
						$alinfo['finance_pop_order_pay_status_type']=1;
					}else{
						//金额比对不上
						$alinfo['finance_pop_order_pay_status_type']=4;
					}
				}
			}else{
				//三有我没有
				$alinfo['order_channel_order_num']=$getorder;
				$alinfo['order_money']=$getorder_money;
				//$alinfo['order_channel_promote']=$promote;
				$alinfo['order_status_name']=0;
				$alinfo['channel_id']=$channelid;
				$alinfo['order_channel_name']=0;
				$alinfo['order_customer_phone']=0;
				$alinfo['order_booked_begin_time']=0;
				$alinfo['order_booked_end_time']=0;
				$alinfo['order_booked_worker_id']=0;
				$alinfo['order_pay_type']=0;
				$alinfo['pay_channel_id']=0;
				$alinfo['order_pay_channel_name']=0;
				$alinfo['order_use_coupon_money']=0;
				$alinfo['order_customer_phone']=0;
				$alinfo['worker_id']=0;
				$alinfo['order_use_promotion_money']=0;
				$alinfo['order_code']=0;
				$alinfo['order_service_type_id']=0;
				$alinfo['worker_id']=0;
				$alinfo['order_pay_money']=0;
				$alinfo['created_at']=0;
				$alinfo['coupon_id']=0;
				$alinfo['order_before_status_dict_id']=0;
				$alinfo['finance_pop_order_pay_status_type']=2;
			}
		}
    	return $alinfo;
    }  
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function OrderPayStatus($params)
    {
    	//return $this->find()->where(['finance_pop_order_sum_money'=>$params])->count();
    $sumt=FinancePopOrder::find()->select(['sum(finance_pop_order_sum_money) as sumoney'])
    	->where(['finance_pop_order_pay_status'=>'0'])->andWhere(['finance_pop_order_pay_status_type' => $params])->asArray()->all();
    	$post['FinanceRecordLog']['finance_record_log_succeed_sum_money'] =$sumt[0]['sumoney'];
    return $sumt[0]['sumoney']?$sumt[0]['sumoney']:0;
    	
    }
    
    
    public function search()
    {
        $query = FinancePopOrder::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
/* 
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        } */

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
        	'finance_pop_order_pay_status_type' => $this->finance_pop_order_pay_status_type,
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
