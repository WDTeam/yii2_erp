<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinancePopOrder;
use common\models\FinanceHeader;
use common\models\Order;
use common\models\GeneralPay;

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
		//$orderinfo= new Order;
		
    	if($channelid=='17'){
		//充值对账
    		/* 
    		`customer_id` int(11) unsigned NOT NULL COMMENT '用户ID',
    		`order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
    		`general_pay_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '发起充值/交易金额',
    		`general_pay_actual_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际充值/交易金额',
    		`general_pay_source` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡',
    		`general_pay_source_name` varchar(20) NOT NULL COMMENT '数据来源名称',
    		`general_pay_mode` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿',
    		`general_pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0=失败,1=成功',
    		`general_pay_transaction_id` varchar(40) NOT NULL DEFAULT '0' COMMENT '第三方交易流水号',
    		`general_pay_eo_order_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '商户ID(第三方交易)',
    		`general_pay_memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    		`general_pay_is_coupon` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否返券',
    		`admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
    		`general_pay_admin_name` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员名称',
    		`worker_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售卡阿姨ID',
    		`handle_admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '办卡人ID',
    		`general_pay_handle_admin_id` varchar(30) NOT NULL DEFAULT '' COMMENT '办卡人名称',
    		`general_pay_verify` varchar(32) NOT NULL DEFAULT '' COMMENT '支付验证',
    		`created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    		`updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
    		`is_del` tinyint(1) NOT NULL DEFAULT '1' COMMENT '删除',
    		
    		
    		id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
    		`order_code` varchar(64) NOT NULL DEFAULT '' COMMENT '订单号',
    		`order_parent_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
    		`order_is_parent` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '有无子订单 1有 0无',
    		`created_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    		`updated_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    		`order_before_status_dict_id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态变更前订单状态字典ID',
    		`order_before_status_name` varchar(128) NOT NULL DEFAULT '' COMMENT '状态变更前订单状态',
    		`order_status_dict_id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态字典ID',
    		`order_status_name` varchar(128) NOT NULL DEFAULT '' COMMENT '订单状态',
    		`order_flag_send` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
    		`order_flag_urgent` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '加急',
    		`order_flag_exception` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '异常 1无经纬度',
    		`order_service_type_id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单服务类别ID',
    		`order_service_type_name` varchar(128) NOT NULL DEFAULT '' COMMENT '订单服务类别',
    		`order_src_id` smallint(4) unsigned DEFAULT '0' COMMENT '订单来源，订单入口id',
    		`order_src_name` varchar(128) NOT NULL DEFAULT '' COMMENT '订单来源，订单入口名称',
    		`channel_id` int(10) unsigned DEFAULT '0' COMMENT '下单渠道ID',
    		`order_channel_name` varchar(64) NOT NULL DEFAULT '' COMMENT '下单渠道名称',
    		`order_channel_order_num` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道订单编号',
    		`customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
    		`order_ip` int(10) NOT NULL DEFAULT '0' COMMENT '下单IP',
    		`order_customer_phone` varchar(16) NOT NULL DEFAULT '' COMMENT '用户手机号',
    		`order_booked_begin_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预约开始时间',
    		`order_booked_end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预约结束时间',
    		`order_booked_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预约服务数量',
    		`address_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '地址ID',
    		`order_address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址 包括 联系人 手机号',
    		`order_unit_money` decimal(8,0) NOT NULL DEFAULT '0' COMMENT '订单单位价格',
    		`order_money` decimal(8,0) NOT NULL DEFAULT '0' COMMENT '订单金额',
    		`order_booked_worker_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '指定阿姨',
    		`order_customer_need` varchar(255) DEFAULT '' COMMENT '用户需求',
    		`order_customer_memo` varchar(255) DEFAULT '' COMMENT '用户备注',
    		`order_cs_memo` varchar(255) DEFAULT '' COMMENT '客服备注',
    		`order_pay_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式 0线上支付 1现金支付',
    		`pay_channel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付渠道id',
    		`order_pay_channel_name` varchar(128) NOT NULL DEFAULT '' COMMENT '支付渠道名称',
    		`order_pay_flow_num` varchar(255) DEFAULT NULL COMMENT '支付流水号',
    		`order_pay_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
    		`order_use_acc_balance` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '使用余额',
    		`card_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '服务卡ID',
    		`order_use_card_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '使用服务卡金额',
    		`coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券ID',
    		`order_use_coupon_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '使用优惠卷金额',
    		`promotion_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销id',
    		`order_use_promotion_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '使用促销金额',
    		`order_lock_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否锁定 1锁定 0未锁定',
    		`worker_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阿姨id',
    		`worker_type_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阿姨职位类型ID',
    		`order_worker_type_name` varchar(64) NOT NULL DEFAULT '' COMMENT '阿姨职位类型',
    		`order_worker_send_type` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '阿姨接单方式 0未接单 1阿姨抢单 2客服指派 3门店指派',
    		`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
    		`comment_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价id',
    		`order_customer_hidden` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '客户端是否已删除',
    		`order_pop_pay_money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。',
    		`invoice_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发票id',
    		`checking_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对账id',
    		`admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id  0客户操作 1系统操作',
    		
    		 */
    		
    	$alinfo_es=GeneralPay::find()
		->select('general_pay_status,customer_id,,,,,,,,,,,,,,,,,,,,,,',$getorder])
    		->asArray()->one();
    	
			if($alinfo_es['general_pay_status']==1){
			$status='成功';	
			}else{
			$status='失败';
			}
    		$alinfo['order_status_name']=$status;
    		$alinfo['channel_id']=$channelid;
    		$alinfo['order_channel_name']='充值订单';
    		$alinfo['customer_id']=0;
    		$alinfo['order_customer_phone']=$alinfo_es['customer_id']; //用户手机号 通过用户id调取用户信息
    		$alinfo['order_booked_begin_time']=0;
    		$alinfo['order_booked_end_time']=0;
    		$alinfo['order_money']=0;
    		$alinfo['order_booked_worker_id']=0;
    		$alinfo['order_pay_type']=0;
    		$alinfo['pay_channel_id']=0;
    		$alinfo['order_pay_channel_name']=0;
    		$alinfo['order_use_coupon_money']=0;
    		$alinfo['order_channel_order_num']=0;
    		$alinfo['order_customer_phone']=0;
    		$alinfo['worker_id']=0;
    		$alinfo['order_use_promotion_money']=0;
    		$alinfo['order_code']=0;
    		$alinfo['order_service_type_id']=0;
    		$alinfo['order_pay_money']=0;
    		$alinfo['created_at']=0;
    		$alinfo['coupon_id']=0;
    		$alinfo['order_before_status_dict_id']=0;
    	
    	
    		
    	}else{
    	//订单对账	
    	$alinfo=Order::find()
    		->select('order_status_name,channel_id,order_channel_name,customer_id,order_customer_phone,order_booked_begin_time,order_booked_end_time,order_money,order_booked_worker_id,order_pay_type,pay_channel_id,order_pay_channel_name,order_use_coupon_money,order_channel_order_num,order_customer_phone,worker_id,order_use_promotion_money,order_code,order_service_type_id,order_pay_money,created_at,coupon_id,order_before_status_dict_id')
    		->andWhere(['=','order_channel_order_num',$getorder])
    		->asArray()->one();
    	}
		if ($alinfo) {
			//比对金额
			//1 金额比对成功   2 三有我没有  3 我有三没有   4 金额比对失败  5状态不对的
			if($alinfo['order_money']==$getorder_money){ 
				$alinfo['finance_pop_order_pay_status_type']=1;
			  }else{
				$alinfo['finance_pop_order_pay_status_type']=4;
			}
			
			
		}else {
		//三有我没有
	    $alinfo['order_channel_order_num']=$getorder;
		$alinfo['order_money']=$getorder_money;	
		//$alinfo['order_channel_promote']=$promote;
		$alinfo['order_status_name']=0;
		$alinfo['channel_id']=0;
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
