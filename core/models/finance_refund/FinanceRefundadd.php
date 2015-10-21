<?php

namespace core\models\finance_refund;

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
            [['finance_refund_tel', 'finance_refund_stype', 'create_time'], 'required'],
            [['finance_refund_money', 'finance_refund_discount'], 'number'],
            [['finance_refund_stype', 'finance_refund_pay_create_time', 'finance_pay_channel_id', 'finance_refund_pay_status', 'finance_order_channel_id', 'finance_refund_worker_id', 'isstatus', 'create_time', 'is_del'], 'integer'],
            [['finance_refund_pop_nub'], 'string', 'max' => 40],
            [['finance_refund_tel', 'finance_refund_worker_tel'], 'string', 'max' => 20],
            [['finance_refund_reason'], 'string', 'max' => 255],
            [['finance_pay_channel_title', 'finance_refund_pay_flow_num'], 'string', 'max' => 80],
            [['finance_order_channel_title'], 'string', 'max' => 30]
        ];
    }

	 
/****** 	
 *  finance_refund_pop_nub	varchar(40)	YES	NULL	第三方订单号
 *	finance_refund_tel	varchar(20)	NO	NULL	用户电话
 *	finance_refund_money	decimal(8,2)	YES	NULL	退款金额
 *	finance_refund_stype	smallint(2)	NO	NULL	申请方式
 *	finance_refund_reason	varchar(255)	YES	NULL	退款理由
 *	finance_refund_discount	decimal(6,2)	YES	NULL	优惠价格
 *	finance_refund_pay_create_time	int(10)	YES	NULL	订单支付时间
 *	finance_pay_channel_id	smallint(2)	YES	NULL	支付方式id
 * 	finance_pay_channel_title	varchar(80)	YES	NULL	支付方式名称
 *	finance_refund_pay_status	smallint(2)	YES	NULL	支付状态 1支付 0 未支付 2 其他
 *	finance_refund_pay_flow_num	varchar(80)	YES	NULL	订单号
 *	finance_order_channel_id	smallint(5)	YES	NULL	订单渠道id
 *	finance_order_channel_title	varchar(30)	YES	NULL	订单渠道名称
 *	finance_refund_worker_id	int(10)	YES	NULL	服务阿姨
 *	finance_refund_worker_tel	varchar(20)	YES	NULL	阿姨电话
 *	isstatus	smallint(2)	YES	0	是否取消1 取消 2 退款的 3 财务已经审核 4 财务已经退款
 *	create_time	int(10)	NO	NULL	退款申请时间
 *	is_del	smallint(1)	YES	0	0 正常 1删除  
 *******/
    
     public function add(){
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
