<?php

namespace dbbase\models\payment;

use dbbase\models\finance\FinanceOrderChannel;
use dbbase\models\finance\FinancePayChannel;
use core\models\customer\Customer;
use core\models\payment\PaymentCustomerTransRecord;
use core\models\order\Order;
use core\models\order\OrderSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%payment}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
 * @property string $payment_money
 * @property string $payment_actual_money
 * @property integer $payment_source
 * @property string $payment_source_name
 * @property integer $payment_mode
 * @property integer $payment_status
 * @property string $payment_transaction_id
 * @property string $payment_eo_order_id
 * @property string $payment_memo
 * @property integer $payment_type
 * @property string $admin_id
 * @property string $payment_admin_name
 * @property string $worker_id
 * @property string $handle_admin_id
 * @property string $payment_handle_admin_name
 * @property string $payment_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_reconciliation
 */
class PaymentCommon extends \yii\db\ActiveRecord
{






    /**
     * 获取订单信息
     * @param $order_id
     */
    public static function orderInfo($order_id){
        $orderSearch = new OrderSearch();
        $orderInfo = $orderSearch->getOne($order_id);
        return $orderInfo;
    }

    /**
     * 充值支付
     * @param $attribute 支付或订单详细数据
     */
    public static function payment($attribute)
    {
        switch($attribute['payment_type'])
        {
            case 1:
                //支付普通订单交易记录
                PaymentCustomerTransRecord::analysisRecord($attribute['order_id'],$attribute['payment_source'],'order_pay',1);
                //验证支付金额是否一致
                if( $attribute['payment_money'] == $attribute['payment_actual_money'] )
                {
                    Order::isPaymentOnline($attribute['order_id'],0,$attribute['payment_source'],$attribute['payment_source_name'],$attribute['payment_transaction_id']);
                }
                break;
            case 2:
                //支付周期订单交易记录
                PaymentCustomerTransRecord::analysisRecord($attribute['order_id'],$attribute['payment_source'],'order_pay',2);
                //验证支付金额是否一致
                if( $attribute['payment_money'] == $attribute['payment_actual_money'] )
                {
                    Order::isBatchPaymentOnline($attribute['order_id'],0,$attribute['payment_source'],$attribute['payment_source_name'],$attribute['payment_transaction_id']);
                }
                break;
            case 3:
                //支付充值交易记录
                PaymentCustomerTransRecord::analysisRecord($attribute['order_id'],$attribute['payment_source'],'payment');
                break;
        }
        //支付充值
        //TODO::后期在交易记录接口调用创建服务卡
        return true;
    }

    /**
     * 订单支付退款
     * @param $data
     */
    public static function orderRefund($data)
    {
        $orderInfo = self::orderInfo($data['order_id']);
        //获取余额支付
        $balancePay = $orderInfo->orderExtPay->order_use_acc_balance;  //余额支付

        //获取服务卡支付
        $service_card_on = $orderInfo->orderExtPay->card_id;    //服务卡ID
        $service_card_pay = $orderInfo->orderExtPay->order_use_card_money;   //服务卡内容

        //执行自有退款
        if( !empty($balancePay) ){
            //余额支付退款
            Customer::incBalance($data['customer_id'],$balancePay);
        }elseif( !empty($service_card_on) && !empty($service_card_pay) ){
            //服务卡支付退款
        }
    }





    /**
     * 返回支付数据
     * @param $order_id 订单ID
     * @param $payment_status 支付状态
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPayStatus($order_id,$payment_status){
        $where = ['order_id'=>$order_id,'payment_status'=>$payment_status];
        return Payment::find()->where($where)->one();
    }

    /**
     * 支付成功发送短信
     * @param $customer_id 用户ID
     */
    public function smsSend($data)
    {
        $phone = Customer::getCustomerPhone($data->data['customer_id']);
        $msg = !empty($data->data['msg']) ? $data->data['msg'] : '支付成功!!!';
        Yii::$app->sms->send($phone,$msg);
    }



}
