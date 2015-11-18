<?php
/**
* 退款控制器api对接
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

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
            [['finance_refund_money', 'finance_refund_discount', 'finance_refund_pay_flow_num'], 'number'],
            [['finance_refund_stype', 'finance_refund_pay_create_time', 'finance_pay_channel_id', 'finance_refund_pay_status', 'finance_order_channel_id',  'isstatus', 'create_time', 'is_del'], 'integer'],
            [['finance_refund_reason'], 'string', 'max' => 255],
            [['finance_pay_channel_title'], 'string', 'max' => 80],
            [['finance_order_channel_title'], 'string', 'max' => 30]
        ];
    }

	
    
     public function add($order){
     		$numb='06'.date('ymdhis',time()).rand(1111,999999);
	     	$this->finance_refund_code=$numb;
	     	$this->customer_id=$order->orderExtCustomer->customer_id;
	     	$this->finance_refund_tel=$order->orderExtCustomer->order_customer_phone;
	     	$this->finance_refund_money=$order->orderExtPay->order_pay_money; //支付金额
	     	$this->order_use_acc_balance=$order->orderExtPay->order_use_acc_balance;//使用余额
	     	$this->order_use_card_money=$order->orderExtPay->order_use_card_money;//使用服务卡金额
	     	$this->order_use_promotion_money=$order->orderExtPay->order_use_promotion_money; //使用促销金额
	     	$this->finance_refund_stype=1;
	     	$this->finance_refund_reason=$order->order_cancel_cause_memo;
	     	$this->finance_refund_discount=$order->orderExtPay->order_use_coupon_money;//使用优惠卷金额
	     	$this->finance_refund_pay_create_time=$order->created_at;
	     	$this->finance_pay_channel_id=$order->orderExtPay->pay_channel_id;
	     	$this->finance_pay_channel_title=$order->orderExtPay->order_pay_channel_name;
	     	$this->finance_refund_pay_status='1';
	     	$this->finance_refund_pay_flow_num=$order->order_code;  //订单号
	     	$this->finance_order_channel_id=$order->channel_id;
	     	$this->finance_order_channel_title=$order->order_channel_name;
	     	$this->finance_refund_worker_id=$order->orderExtWorker->worker_id;
	     	$this->finance_refund_worker_tel=$order->orderExtWorker->order_worker_phone;
	     	$this->finance_refund_check_id=0;
	     	$this->finance_refund_check_name=0;
	     	$this->finance_refund_shop_id=$order->district_id;
	     	$this->finance_refund_province_id=$order->city_id;
	     	$this->finance_refund_city_id=$order->city_id;
	     	$this->finance_refund_county_id=$order->city_id;
	     	$this->isstatus=2;
	        $this->create_time=time();
	     	$this->is_del=0;
			if($this->save()){
				return  $numb;
			}else{
				return  false;
			}			
	 
	 }
    
}
