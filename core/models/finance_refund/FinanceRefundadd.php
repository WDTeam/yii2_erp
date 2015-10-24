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
            [['finance_refund_stype', 'create_time'], 'required'],
            [['finance_refund_money', 'finance_refund_discount'], 'number'],
            [['finance_refund_stype', 'finance_refund_pay_create_time', 'finance_pay_channel_id', 'finance_refund_pay_status', 'finance_order_channel_id',  'isstatus', 'create_time', 'is_del'], 'integer'],
            [['finance_refund_reason'], 'string', 'max' => 255],
            [['finance_pay_channel_title', 'finance_refund_pay_flow_num'], 'string', 'max' => 80],
            [['finance_order_channel_title'], 'string', 'max' => 30]
        ];
    }

	
    
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
