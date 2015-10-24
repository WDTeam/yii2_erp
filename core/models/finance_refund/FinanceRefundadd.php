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
          //  [['finance_refund_pop_nub'], 'string', 'max' => 40],
            [['finance_refund_tel'], 'string', 'max' => 20],
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
    

	 
	 public function attributeLabels()
	 {
	 	return [
	 	'id' => Yii::t('core', '主键id'),
	 	'finance_refund_pop_nub' => Yii::t('core', '第三方订单号'),
	 	'customer_id' => Yii::t('core', '用户ID'),
	 	'finance_refund_tel' => Yii::t('core', '用户电话'),
	 	'finance_refund_money' => Yii::t('core', '退款金额'),
	 	'finance_refund_stype' => Yii::t('core', '申请方式'),
	 	'finance_refund_reason' => Yii::t('core', '退款理由'),
	 	'finance_refund_discount' => Yii::t('core', '优惠价格'),
	 	'finance_refund_pay_create_time' => Yii::t('core', '订单支付时间'),
	 	'finance_pay_channel_id' => Yii::t('core', '支付方式id'),
	 	'finance_pay_channel_title' => Yii::t('core', '支付方式名称'),
	 	'finance_refund_pay_status' => Yii::t('core', '支付状态 1支付 0 未支付 2 其他'),
	 	'finance_refund_pay_flow_num' => Yii::t('core', '订单号'),
	 	'finance_order_channel_id' => Yii::t('core', '订单渠道id'),
	 	'finance_order_channel_title' => Yii::t('core', '订单渠道名称'),
	 	'finance_refund_worker_id' => Yii::t('core', '服务阿姨'),
	 	'finance_refund_worker_tel' => Yii::t('core', '阿姨电话'),
	 	'finance_refund_check_id' => Yii::t('core', '确认人id'),
	 	'finance_refund_check_name' => Yii::t('core', '确认人'),
	 	'finance_refund_shop_id' => Yii::t('core', '门店'),
	 	'finance_refund_province_id' => Yii::t('core', '省份'),
	 	'finance_refund_city_id' => Yii::t('core', '城市'),
	 	'finance_refund_county_id' => Yii::t('core', '区'),
	 	'isstatus' => Yii::t('core', '是否取消1 取消 2 退款的 3 财务已经审核 4 财务已经退款'),
	 	'create_time' => Yii::t('core', '退款申请时间'),
	 	'is_del' => Yii::t('core', '0 正常 1删除'),
	 	];
	 }
	 
 
}
