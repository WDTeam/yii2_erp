<?php

namespace core\models\finance;

use Yii;
class FinanceRefundadd extends FinanceRefund
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_refund_stype', 'create_time'], 'required'],
            [['finance_refund_money', 'finance_refund_discount'], 'number'],
            [['finance_refund_stype', 'finance_refund_pay_create_time', 'finance_pay_channel_id', 'finance_refund_pay_status', 'finance_order_channel_id',  'isstatus', 'create_time', 'is_del'], 'integer'],
            [['finance_refund_reason'], 'string', 'max' => 255],
            [['finance_pay_channel_title', 'finance_refund_pay_flow_num'], 'string', 'max' => 80],
            [['finance_order_channel_title'], 'string', 'max' => 30]
        ];
    }

	
    
     public function add($date){
	     	$this->finance_refund_pop_nub='0';
	     	$this->customer_id=$date->order_id;
	     	$this->finance_refund_tel=$date->order_customer_phone;
	     	$this->finance_refund_money=$date->order_pay_money;
	     	$this->finance_refund_stype=1;
	     	$this->finance_refund_reason=$date->memo;
	     	$this->finance_refund_discount=$date->order_use_coupon_money;
	     	$this->finance_refund_pay_create_time=$date->created_at;
	     	$this->finance_pay_channel_id=$date->pay_channel_id;
	     	$this->finance_pay_channel_title=$date->order_pay_channel_name;
	     	$this->finance_refund_pay_status='1';
	     	$this->finance_refund_pay_flow_num=$date->order_code;
	     	$this->finance_order_channel_id=$date->channel_id;
	     	$this->finance_order_channel_title=$date->order_channel_name;
	     	$this->finance_refund_worker_id=$date->worker_id;
	     	$this->finance_refund_worker_tel=$date->order_worker_phone;
	     	$this->finance_refund_check_id=0;
	     	$this->finance_refund_check_name=0;
	     	$this->finance_refund_shop_id=$date->district_id;
	     	$this->finance_refund_province_id=$date->city_id;
	     	$this->finance_refund_city_id=$date->city_id;
	     	$this->finance_refund_county_id=$date->city_id;
	     	$this->isstatus=2;
	        $this->create_time=time();
	     	$this->is_del=0;
			$is_dataste=$this->save();
			if($is_dataste){
				$arra=['status'=>200,'msg'=>'数据库写入成功'];
				return  json_encode($arra);
			}else{
				$arra=['status'=>504,'msg'=>'数据库写入失败'];
				return  json_encode($arra);
			}
					
		/* }else{
			$arra=['status'=>401,'msg'=>'传入的不是一个数组'];
			return  json_encode($arra);	
		} */
	 
	 }
    
}
