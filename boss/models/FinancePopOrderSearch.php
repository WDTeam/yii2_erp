<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinancePopOrder;
use common\models\FinanceHeader;
use common\models\GeneralPay;
use core\models\Customer;
use core\models\order\OrderSearch;
use core\models\order\Order;
use core\models\worker\Worker;
/**
 * FinancePopOrderSearch represents the model behind the search form about `common\models\FinancePopOrder`.
 */
class FinancePopOrderSearch extends FinancePopOrder
{
    public function rules()
    {
        return [
            [['id','finance_record_log_id', 'finance_order_channel_id', 'finance_pay_channel_id', 'finance_pop_order_worker_uid','finance_pop_order_status','finance_pop_order_booked_time', 'finance_pop_order_booked_counttime', 'finance_pop_order_coupon_id', 'finance_pop_order_order_type', 'finance_pop_order_finance_isok', 'finance_pop_order_order_time', 'finance_pop_order_pay_time', 'finance_pop_order_pay_status', 'finance_pop_order_check_id', 'finance_pop_order_finance_time', 'create_time', 'is_del'], 'integer'],
            [['finance_record_log_id','finance_order_channel_statuspayment','finance_order_channel_endpayment','finance_pop_order_number', 'finance_order_channel_title', 'finance_pay_channel_title', 'finance_pop_order_customer_tel', 'finance_pop_order_order2', 'finance_pop_order_channel_order', 'finance_pop_order_pay_title'], 'safe'],
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
    public static  function alltime($time,$styp=1)
    {
    	if($time==0 || $time==""){
    	$name='未知';	
    	}else{
    		if($styp==1){
    			$name=date('Y-m-d H:i:s',$time);
    		}else{
    			$name=date('Y-m-d',$time);}
    	}
    	return $name;
    }
    
    
    
    /**
    * 获取阿姨资料
    * @date: 2015-10-13
    * @author: peak pan
    * @return:
    **/
    
    public static  function Workerinfo($Workerid,$name)
    {
    	
    $WorkerInfo= Worker::getWorkerInfo($Workerid);
    	if(count($WorkerInfo)>0){
    		$nameinfo=$WorkerInfo[$name];
    	}else{
    		$nameinfo='未查到此阿姨';
    	}
    	return $nameinfo;
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
    
    /**
    * 对账记录里面 未处理笔数
    * @date: 2015-10-13
    * @author: peak pan
    * @return:
    **/
    public static  function countnub($id)
    {
    	$sumt=FinancePopOrder::find()
    	->andWhere(['finance_record_log_id' => $id])
    	->andWhere(['finance_pop_order_pay_status' => '0'])->asArray()->all();
    	if(count($sumt)>0){
    		$name='<font color="red">'.count($sumt).'条</font>';
    	}else{
    		$name=0;
    	}
    	return $name;
    }
    
    /**
     * 对账记录里面 未处理金额
     * @date: 2015-10-13
     * @author: peak pan
     * @return:
     **/
    public static  function summoney($id)
    {
    	$sumt=FinancePopOrder::find()->select(['sum(finance_pop_order_sum_money) as sumoney'])
    	->andWhere(['finance_record_log_id' => $id])
    	->andWhere(['finance_pop_order_pay_status' => '0'])->asArray()->all(); 
    	if(count($sumt)>0){
    		$name='<font color="red">'.$sumt[0]['sumoney'].'</font>';
    	}else{
    		$name=0;
    	}
    	return $name;
    }
    
    /**
    * 欺负是否是淘宝订单
    * @date: 2015-10-15
    * @author: peak pan
    * @return:
    **/
    public static  function get_stypname($idname)
    {
    	if($idname==7){
    		$name='手续费';
    	}else{
    		$name='优惠金额';
    	}
    	return $name;
    }
    
    /**
    * 通过账期查找渠道id
    * @date: 2015-10-15
    * @author: peak pan
    * @return:
    **/
    public static  function get_channleid($id)
    {
    
    	if($id){
    	$channlenameid=FinancePopOrder::find()->select(['finance_order_channel_id'])
    		->andWhere(['finance_record_log_id' => $id])->asArray()->one();
    	$idname=$channlenameid['finance_order_channel_id'];
    	}else{
    	$idname=0;
    	}
    	return $idname;
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
    	}elseif($date==1){
    		return '<font color="blue">已处理</font>';
    	}elseif ($date==3){
    		return '<font color="orange">坏 账</font>';
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
    		return '<font color="blue">'.$date.'</font>';
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
     * 状态分析方法
     * @date: 2015-10-14
     * $refund 退款金额 主要满足美团使用  $stypeid 订单状态数据
     * @author: peak pan
     * @return:
     **/
    
    private function stutasinfo_look($channleid,$namestype,$stypeid,$refund){
		//1  已经付款   2 已取消  
		$mestatus_tui=['17']; //退款
		$mestatus_yes=['2,3,4,5,6,7,8,9,10,11,12'];//正常结算

		/*   1  美团赵轮   2	大众点评赵轮   3	京东到家   4	糯米网团购   5	大众点评团购6	支付宝服务窗
		 *   7	 支付宝8	淘宝团购  9	淘宝  10	百度钱包    11	百度B环      12    百度订单分发
		 *   13	App到位    14	手机微信       15	App微信     16	移动app    17	充值订单 
		 **/

		if($channleid==1){
		//1 美团赵轮订单  在状态项里面不存在状态项    1 正常订单和退款 2 补偿订单 3 退款
			if($refund>0){
			if(in_array($stypeid,$mestatus_tui && $namestype==1 && $namestype==3) ){
				return 1;
			}else {
				return 5;
			}
			}
		}elseif ($channleid==2){
		//2	大众点评赵轮
			return 1;
			
		}elseif ($channleid==3){
		//3	京东到家	
			return 1;
		}elseif($channleid==5){
		//	5	大众点评团购
			return 1;
		}elseif ($channleid==6){
		//6	支付宝服务窗
			return 1;
		}elseif ($channleid==7){
		//7	支付宝
			return 1;
		}elseif ($channleid==8){
		//8	淘宝团购	
		}elseif ($channleid==13){
		//13	App到位	
			return 1;
		}elseif ($channleid==14){
		//14	手机微信
			return 1;
		}elseif ($channleid==15){
		//15	App微信	
			return 1;
		}else{
		//16	移动app	
			return 1;
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
	    foreach ($alinfo as $rt){
	    		$tuty=$rt['finance_header_where'];
	    		if($tuty=='order_channel_promote'){
	    		$tyyu[$tuty][]=$rt['finance_header_key'];
	    		}else {
	    		$tyyu[$tuty]=$rt['finance_header_key'];
	    		}
	    }
    	$hder_info=$tyyu;
    	//一列数据
    	foreach ($value as $rtyy){
    		$dateinfo[]=$rtyy;
    	}
    	
    	if($channelid==1){
    		//美团对账
    		$orderdateinfo=$this->get_beautifulgroupon($hder_info,$dateinfo,$channelid);
    		return $orderdateinfo; 
    	}elseif ($channelid==7){
    		//淘宝对账
    		$orderdateinfo=$this->get_taobaodata($hder_info,$dateinfo,$channelid);
    		return $orderdateinfo;
    		
    	}elseif ($channelid==14){
    		//微信对账	
    		$orderdateinfo=$this->get_infodata($hder_info,$dateinfo,$channelid);
    		return $orderdateinfo;
    	}elseif ($channelid==1111){
    		//微信对账
    		$orderdateinfo=$this->get_infodata($hder_info,$dateinfo,$channelid);
    		return $orderdateinfo;
    		
    	}elseif ($channelid==1112){
    		//微信对账
    		$orderdateinfo=$this->get_infodata($hder_info,$dateinfo,$channelid);
    		return $orderdateinfo;
    		
    	}else{
    		//其他对账
    		$orderdateinfo=$this->get_infodata();
    		return $orderdateinfo;
    		
    	}
    	
    }  
    
  		//美团对账处理   
		public function get_beautifulgroupon($hder_info,$dateinfo,$channelid){
    	//对应退款金额
	    if(isset($hder_info['refund'])){
	    	$refund=$dateinfo[$hder_info['refund']];
	    	if($refund>0){
	    		$order_status=2;
	    	}else{
	    		$order_status=1;
	    	}
	    	//支付状态 2 退款
	    	$accmay=$refund;
	    }else{
	    	//支付状态 支付
	    	$order_status=1;
	    	$refund=0;
	    }
    //总金额
    $getorder_money=$dateinfo[$hder_info['order_money']];
    //对应系统订单号
    $getorder=$dateinfo[$hder_info['order_channel_order_num']];
    
    //对应数据表折扣金额（渠道营销费）
    if(is_array($hder_info['order_channel_promote'])){
    	foreach ($hder_info['order_channel_promote'] as $zkje){
    		$site[]=$dateinfo[$zkje];
    	}
    	$promote=array_sum($site);
    	//实际支付金额
    	$accmay=$getorder_money-$promote;
    }else{
    	$promote=0;
    	$accmay=$getorder_money;
    }
     
     
    //手续费
    if(isset($hder_info['decrease'])){
    	$decrease=$dateinfo[$hder_info['decrease']];
    }else{
    	$decrease=0;
    }
     
    //状态分类
    if(isset($hder_info['function_way'])){
    	$function_way=$dateinfo[$hder_info['function_way']];
    }else{
    	$function_way='';
    }
     
     
    //var_dump($refund);exit;
    //打开订单库开始比对
    //订单对账
    $OrderExtPop = new Order;
    $orderInfo = $OrderExtPop::find()->joinWith(['orderExtPop'])->where(['orderExtPop.order_pop_order_code'=>$getorder])->one();
    //第三方运营费
     
    if (isset($orderInfo->order_code)) {
    	$orderdateinfo=$orderInfo->getAttributes();
    	//$erty=$orderInfo->orderExtPay->pay_channel_id;
    	if(isset($orderInfo->orderExtPay)){
    	$orderdateinfo['pay_channel_id']=$orderInfo->orderExtPay->pay_channel_id;
    	$orderdateinfo['order_pay_channel_name']=$orderInfo->orderExtPay->order_pay_channel_name;//支付渠道名称
    	$orderdateinfo['order_use_coupon_money']=$orderInfo->orderExtPay->order_use_coupon_money;//使用优惠卷金额
    	$orderdateinfo['order_use_promotion_money']=$orderInfo->orderExtPay->order_use_promotion_money;//使用促销金额
    	$orderdateinfo['order_pay_money']=$orderInfo->orderExtPay->order_pay_money;//支付金额
    	$orderdateinfo['coupon_id']=$orderInfo->orderExtPay->coupon_id;
    	}
    	//第三方运营费用
    	$orderdateinfo['order_pop_operation_money']=$promote;
    	$orderdateinfo['finance_pop_order_reality_pay']=$accmay;
    	$orderdateinfo['order_use_coupon_money']=$promote;
    	if($orderInfo->orderExtCustomer){
    		$orderdateinfo['order_customer_phone']=$orderInfo->orderExtCustomer->order_customer_phone;
    	}else {
    		$orderdateinfo['order_customer_phone']=0;
    	}
    
    	if($orderInfo->orderExtWorker){
    		$orderdateinfo['worker_id']=$orderInfo->orderExtWorker->worker_id;
    	}else {
    		$orderdateinfo['worker_id']=0;
    	}
    
    	$orderdateinfo['order_channel_order_num']=$getorder;
    	//1 支付 2 退款
    	$orderdateinfo['order_before_status_dict_id']=$order_status;
    		
    	//比对金额
    	//1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
    
    	if($orderdateinfo['order_money']==$getorder_money){
    
    		//第三方对账订单   ——  比对上了金额相同，在比对状态
    		if($function_way){
    			//有状态
    			$ststusinfo=$this->stutasinfo_look($channelid,$function_way,$orderInfo->orderExtStatus->order_status_dict_id,$refund);
    			$orderdateinfo['finance_pop_order_pay_status_type']=$ststusinfo;
    		}else {
    			//无状态
    			$orderdateinfo['finance_pop_order_pay_status_type']=1;
    		}
    
    	}else {
    		$orderdateinfo['order_money']=$getorder_money;
    		$orderdateinfo['finance_pop_order_pay_status_type']=4;
    	}
    }else {
    	//在订单表查询无数据 1 确实没有 2视为充值订单
    	if($getorder_money >=1000){
    		//查询胜强的充值表
    		//查询存在
    		$alinfo_es=\core\models\GeneralPay\GeneralPay::getGeneralPayByInfo(['general_pay_transaction_id'=>$getorder],'general_pay_status,customer_id,created_at,general_pay_money,general_pay_source,general_pay_transaction_id,general_pay_mode');
    
    
    		/* 	 if($alinfo_es){
    		 //充值订单存在 开始比对金额
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
    		}  */
    
    	}
    		
    	//三有我没有
    	$orderdateinfo['order_channel_order_num']=$getorder;
    	$orderdateinfo['order_money']=$getorder_money;
    	//$alinfo['order_channel_promote']=$promote;
    	$orderdateinfo['order_status_name']=0;
    	$orderdateinfo['channel_id']=$channelid;
    	$orderdateinfo['order_channel_name']=0;
    	$orderdateinfo['order_customer_phone']=0;
    	$orderdateinfo['order_booked_begin_time']=0;
    	$orderdateinfo['order_booked_end_time']=0;
    	$orderdateinfo['order_booked_worker_id']=0;
    	$orderdateinfo['order_pay_type']=0;
    	$orderdateinfo['pay_channel_id']=0;
    	$orderdateinfo['order_pay_channel_name']=0;
    	$orderdateinfo['finance_pop_order_reality_pay']=$accmay;
    	$orderdateinfo['order_use_coupon_money']=$promote;
    	$orderdateinfo['order_customer_phone']=0;
    	$orderdateinfo['worker_id']=0;
    	$orderdateinfo['order_use_promotion_money']=0;
    	$orderdateinfo['order_code']=0;
    	$orderdateinfo['order_service_type_id']=0;
    	$orderdateinfo['worker_id']=0;
    	$orderdateinfo['order_pay_money']=0;
    	$orderdateinfo['created_at']=0;
    	$orderdateinfo['coupon_id']=0;
    	$orderdateinfo['order_before_status_dict_id']=$order_status;
    	$orderdateinfo['finance_pop_order_pay_status_type']=2;
    		
    }
    return $orderdateinfo;
    
    }  

    //淘宝对账
    public function get_taobaodata($hder_info,$dateinfo,$channelid){
    	
    	//对应退款金额
    	if(isset($hder_info['refund'])){
    		$refund=$dateinfo[$hder_info['refund']];
    		if($refund>0){
    			$order_status=2;
    		}else{
    			$order_status=1;
    		}
    		//支付状态 2 退款
    		$accmay=$refund;
    	}else{
    		//判断收入金额如果收入金额为零，肯定是退款的
    		if($dateinfo[$hder_info['order_money']]==0){
    			$order_status=3;
    		}else {
    			$order_status=1;
    		}
    		//支付状态 支付
    		$refund=0;
    	}
    	
    	
    	//总金额
    	$getorder_money=$dateinfo[$hder_info['order_money']];
    	//对应系统订单号
    	$getorder=$dateinfo[$hder_info['order_channel_order_num']];
    	
    	//对应数据表折扣金额（渠道营销费）
    	if(isset($hder_info['order_channel_promote'])){
    		foreach ($hder_info['order_channel_promote'] as $zkje){
    			$site[]=$dateinfo[$zkje];
    		}
    		$promote=array_sum($site);
    		//实际支付金额
    		$accmay=$getorder_money-$promote;
    	}else{
    		$promote=$dateinfo[$hder_info['decrease']];
    		$accmay=$getorder_money;
    	}
    	 
    	 
    	//手续费
    	if(isset($hder_info['decrease'])){
    		$decrease=$dateinfo[$hder_info['decrease']];
    	}else{
    		$decrease=0;
    	}
    	 
    	//状态分类
    	if(isset($hder_info['function_way'])){
    		$function_way=$dateinfo[$hder_info['function_way']];
    	}else{
    		$function_way='';
    	}
    	 
    	 
    	//var_dump($refund);exit;
    	//打开订单库开始比对
    	//订单对账
    	$OrderExtPop = new Order;
    	$orderInfo = $OrderExtPop::find()->joinWith(['orderExtPop'])->where(['orderExtPop.order_pop_order_code'=>trim($getorder)])->one();
    	//第三方运营费
    	 
    	if (isset($orderInfo->order_code)) {
    	
    		$orderdateinfo=$orderInfo->getAttributes();
    		//$erty=$orderInfo->orderExtPay->pay_channel_id;
    		if($orderInfo->orderExtPay){
    		$orderdateinfo['pay_channel_id']=$orderInfo->orderExtPay->pay_channel_id;
    		$orderdateinfo['order_pay_channel_name']=$orderInfo->orderExtPay->order_pay_channel_name;//支付渠道名称
    		$orderdateinfo['order_use_coupon_money']=$orderInfo->orderExtPay->order_use_coupon_money;//使用优惠卷金额
    		$orderdateinfo['order_use_promotion_money']=$orderInfo->orderExtPay->order_use_promotion_money;//使用促销金额
    		$orderdateinfo['order_pay_money']=$orderInfo->orderExtPay->order_pay_money;//支付金额
    		$orderdateinfo['coupon_id']=$orderInfo->orderExtPay->coupon_id;
    	}
    		//第三方运营费用
    		$orderdateinfo['order_pop_operation_money']=$promote;
    		$orderdateinfo['finance_pop_order_reality_pay']=$accmay;
    		$orderdateinfo['order_use_coupon_money']=$promote;
    		if($orderInfo->orderExtCustomer){
    			$orderdateinfo['order_customer_phone']=$orderInfo->orderExtCustomer->order_customer_phone;
    		}else {
    			$orderdateinfo['order_customer_phone']=0;
    		}
    	
    		if($orderInfo->orderExtWorker){
    			$orderdateinfo['worker_id']=$orderInfo->orderExtWorker->worker_id;
    		}else {
    			$orderdateinfo['worker_id']=0;
    		}
    	
    		$orderdateinfo['order_channel_order_num']=$getorder;
    		//1 支付 2 退款 3 手续费  4转账
    		$orderdateinfo['order_before_status_dict_id']=$order_status;
    	
    		//比对金额
    		//1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
    		if($orderdateinfo['order_money']==$getorder_money){
    	
    			//第三方对账订单   ——  比对上了金额相同，在比对状态
    			if($function_way){
    				//有状态
    				$ststusinfo=$this->stutasinfo_look($channelid,$function_way,$orderInfo->orderExtStatus->order_status_dict_id,$refund);
    				$orderdateinfo['finance_pop_order_pay_status_type']=$ststusinfo;
    			}else {
    				//无状态
    				$orderdateinfo['finance_pop_order_pay_status_type']=1;
    			}
    	
    		}else {
    			$orderdateinfo['order_money']=$getorder_money;
    			$orderdateinfo['finance_pop_order_pay_status_type']=4;
    		}
    	}else {
    		//在订单表查询无数据 1 确实没有 2视为充值订单
    		if($getorder_money >=1000){
    			//查询胜强的充值表
    			//查询存在
    			$alinfo_es=\core\models\GeneralPay\GeneralPay::getGeneralPayByInfo(['general_pay_transaction_id'=>$getorder],'general_pay_status,customer_id,created_at,general_pay_money,general_pay_source,general_pay_transaction_id,general_pay_mode');
    	
    	
    			/* 	 if($alinfo_es){
    			 //充值订单存在 开始比对金额
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
    			}  */
    	
    		}
    	
    		//三有我没有
    		$orderdateinfo['order_channel_order_num']=$getorder;
    		$orderdateinfo['order_money']=$getorder_money;
    		//$alinfo['order_channel_promote']=$promote;
    		$orderdateinfo['order_status_name']=0;
    		$orderdateinfo['channel_id']=$channelid;
    		$orderdateinfo['order_channel_name']=0;
    		$orderdateinfo['order_customer_phone']=0;
    		$orderdateinfo['order_booked_begin_time']=0;
    		$orderdateinfo['order_booked_end_time']=0;
    		$orderdateinfo['order_booked_worker_id']=0;
    		$orderdateinfo['order_pay_type']=0;
    		$orderdateinfo['pay_channel_id']=0;
    		$orderdateinfo['order_pay_channel_name']=0;
    		$orderdateinfo['finance_pop_order_reality_pay']=$accmay; //实际收款金额
    		$orderdateinfo['order_use_coupon_money']=$promote;//优惠金额
    		$orderdateinfo['order_customer_phone']=0;
    		$orderdateinfo['worker_id']=0;
    		$orderdateinfo['order_use_promotion_money']=0;
    		$orderdateinfo['order_code']=0;
    		$orderdateinfo['order_service_type_id']=0;
    		$orderdateinfo['worker_id']=0;
    		$orderdateinfo['order_pay_money']=0;
    		$orderdateinfo['created_at']=0;
    		$orderdateinfo['coupon_id']=0;
    		$orderdateinfo['order_before_status_dict_id']=$order_status;
    		$orderdateinfo['finance_pop_order_pay_status_type']=2;
    	
    	}
    	return $orderdateinfo;
    }

    public function OrderPayStatus($paramsinfo,$lastidRecordLogid)
    {
    $stype= 2;
    $sumtone=FinancePopOrder::find()->select(['sum(finance_pop_order_sum_money) as sumoney'])
    	->where(['finance_pop_order_pay_status'=>'0'])
    ->andWhere(['finance_pop_order_pay_status_type' => $paramsinfo])
    ->andWhere(['finance_record_log_id' => $lastidRecordLogid])
    ->asArray()->all();
    
    $sumttoo=FinancePopOrder::find()->select(['sum(finance_pop_order_discount_pay) as reality_pay'])
    ->where(['finance_pop_order_pay_status'=>'0'])
    ->andWhere(['finance_pop_order_pay_status_type' => $paramsinfo])
    ->andWhere(['finance_record_log_id' => $lastidRecordLogid])
    ->andWhere(['finance_pop_order_status' =>$stype])
    ->asArray()->all();
    $suminfodata=$sumtone[0]['sumoney']-abs($sumttoo[0]['reality_pay']);
    return $suminfodata?$suminfodata:0;
    	
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

        $query->orderBy(['id'=>SORT_DESC]);
        $query->andFilterWhere([
            'id' => $this->id,
        	'finance_record_log_id' => $this->finance_record_log_id,
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
            ->andFilterWhere(['>=', 'finance_order_channel_statuspayment', $this->finance_order_channel_statuspayment])
            
            ->andFilterWhere(['<=', 'finance_order_channel_endpayment', $this->finance_order_channel_endpayment])
            
            
       		->andFilterWhere(['like', 'finance_pop_order_pay_title', $this->finance_pop_order_pay_title]);

        return $dataProvider;
    }
}
