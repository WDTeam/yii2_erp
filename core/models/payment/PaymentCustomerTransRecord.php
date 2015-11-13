<?php

namespace core\models\payment;

use core\models\payment\Payment;
use core\models\customer\Customer;
use core\models\operation\coupon\CouponRule;
use core\models\order\OrderSearch;
use core\models\operation\OperationPayChannel;

use dbbase\models\payment\PaymentCustomerTransRecordLog;

use Yii;
use yii\behaviors\TimestampBehavior;

class PaymentCustomerTransRecord extends \dbbase\models\payment\PaymentCustomerTransRecord
{



    /**
     * 根据用户ID返回消费记录
     * @param $customer_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getCustomerPaymentTransRecord($customer_id)
    {
        return PaymentCustomerTransRecord::find()->where(["customer_id"=>$customer_id])->asArray()->all();
    }

    /**
     * 创建交易记录
     * @param $data 数据
     */
    private static function createPyamentTransRecordLog($data)
    {
        //记录日志
        $obj = new PaymentCustomerTransRecordLog();
        $obj->insertLog($data);
    }

    /**
     * 分析创建(支付/充值)交易记录
     * @param $order_id 订单ID|支付记录ID
     * @param $order_channel_id 渠道ID
     * @param string $type  支付|充值类型
     * @param int $orderStatus  支付|充值类型
     * @return bool
     */
    public static function analysisRecord($order_id,$order_channel_id=0,$type='order_pay',$orderStatus = 1,$payment_data = [])
    {
        $obj = new self();
        //判断是订单支付还是购买服务卡充值
        if($type == 'order_pay')
        {
            //如果支付订单,查询订单数据
            $fields = [
                'id as order_id',
                'order_code',
                'order_batch_code',
                'channel_id',
                'order_channel_name',
                'order_money',
                'customer_id',
                'order_customer_phone',
                'pay_channel_id',
                'order_pay_channel_name',
                'order_pay_money',
                'order_use_acc_balance',
                'card_id',
                'order_use_card_money',
                'coupon_id',
                'order_use_coupon_money',
                'order_use_promotion_money',
                'order_pop_order_money'
            ];
            $dataArray = OrderSearch::getOrderInfo($order_id,$fields,$orderStatus);
            $data = current($dataArray);

            //周期订单,多次回调
            if(count($dataArray) > 1)
            {
                foreach( $dataArray as $data )
                {
                    self::analysisRecord($data['order_id'],$data['channel_id'],'order_pay',1,$payment_data);
                }
                return true;
            }

            /*
            //判断是普通订单还是周期订单
            if(count($dataArray) > 1)
            {
                //计算周期订单总金额和优惠券金额
                $data = $dataArray[0];

                //计算总金额
                $balancePay = 0;    //余额支付
                $couponMoney = 0;   //优惠券金额
                $serviceCardPay = 0;    //服务卡支付
                $orderMoney = 0;    //订单总额
                $onlinePay = 0; //在线支付
                foreach( $dataArray as $val )
                {
                    $balancePay += $val['order_use_acc_balance'];   //余额支付
                    $couponMoney += $val['order_use_coupon_money']; //优惠券金额
                    $serviceCardPay += $val['order_use_card_money'];   //服务卡支付
                    $orderMoney += $val['order_money'];    //订单总额
                    $onlinePay += $val['order_pay_money'];    //在线支付
                }
                //重新赋值
                $data['order_id'] = $data['order_batch_code'];
                $data['order_use_acc_balance'] = $balancePay;
                $data['order_use_coupon_money'] = $couponMoney;
                $data['order_use_card_money'] = $serviceCardPay;
                $data['order_money'] = $orderMoney;
                //如果实际支付金额存在,使用实际支付金额,否则使用订单金额
                $data['order_pay_money'] = $onlinePay;
            }
            else
            {
                $data = current($dataArray);
            }
            */
            //组装数据
            $transRecord['admin_id'] = !empty(Yii::$app->user->id) ? Yii::$app->user->id : '0';             //管理员ID
            $transRecord['admin_name'] = !empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : 'system';           //管理员名称

            $transRecord['order_code'] = $data['order_code'];           //订单编号
            $transRecord['order_batch_code'] = $data['order_batch_code'];           //周期订单编号

            $transRecord["payment_customer_trans_record_mode"] = 1;      //交易方式:1消费,2=充值,3=退款,4=赔偿

            $transRecord["payment_customer_trans_record_service_card_on"] = $data['card_id'];                   //服务卡号
            $transRecord["payment_customer_trans_record_service_card_pay"] = $data['order_use_card_money'];     //服务卡支付

            $transRecord["payment_customer_trans_record_coupon_id"] = !empty($data['coupon_id']) ? $data['coupon_id'] : '';    //优惠券ID
            $transRecord["payment_customer_trans_record_coupon_code"] = !empty($data['order_use_coupon_code']) ? $data['order_use_coupon_code'] : '';//优惠券编码
            $transRecord["payment_customer_trans_record_coupon_money"] = $data['order_use_coupon_money'];    //优惠券金额

            $transRecord["payment_customer_trans_record_online_balance_pay"] = $data['order_use_acc_balance'];    //余额支付
            $transRecord["payment_customer_trans_record_order_total_money"] = $data['order_money'];               //订单总额
            $transRecord["payment_customer_trans_record_pre_pay"] = $data['order_pop_order_money'];    //预付费
            $transRecord['payment_customer_trans_record_cash'] = ($data['pay_channel_id'] == 2) ? $data['order_money'] : 0;   //现金支付
            $transRecord['payment_customer_trans_record_online_pay'] = !empty($payment_data['payment_actual_money']) ? $payment_data['payment_actual_money'] : $data['order_pay_money'];    //在线支付
            $transRecord['order_channel_id'] = $data['channel_id'];   //订单渠道
            $transRecord['payment_customer_trans_record_order_channel'] = $data['order_channel_name'];   //订单渠道名称
        }
        elseif($type == 'payment')
        {
            //如果充值,查询充值记录
            $data = Payment::getPaymentPayStatusData($order_id);
            $transRecord["payment_customer_trans_record_mode"] = 2; //交易方式:1消费,2=充值,3=退款,4=赔偿
            //TODO::以后可能增加现金购买服务卡,在此判断支付信息中的现金支付字段
            $transRecord['payment_customer_trans_record_online_pay'] = $data['payment_actual_money'];    //在线支付
            //TODO::创建服务卡
            $transRecord["payment_customer_trans_record_service_card_on"] = mt_rand(10000,99999); //服务卡号
            $transRecord["payment_customer_trans_record_service_card_current_balance"] = 1000; //服务卡支付

            $scenario = true;
        }
        else
        {
            return false;
//            exit('未知类型');
        }

        //公用部分
        $transRecord['customer_id'] = $data['customer_id'];     //用户ID
        $transRecord['customer_phone'] = $data['order_customer_phone'];     //用户手机号码
        $transRecord['order_id'] = $data['order_id'];           //订单ID
        $transRecord['pay_channel_id'] = !empty($payment_data['payment_channel_id']) ? $payment_data['payment_channel_id'] : $data['pay_channel_id'];   //	支付渠道
        $transRecord['payment_customer_trans_record_pay_channel'] = !empty($payment_data['payment_channel_name']) ? $payment_data['payment_channel_name'] : $data['order_pay_channel_name']; //	支付渠道名称
        $transRecord['payment_customer_trans_record_mode_name'] = self::getCustomerTransRecordModeByName($transRecord['payment_customer_trans_record_mode']); //	交易方式名称
        $transRecord['payment_customer_trans_record_transaction_id'] = !empty($payment_data['payment_transaction_id']) ? $payment_data['payment_transaction_id'] : 0; //交易流水号
        $transRecord['payment_customer_trans_record_eo_order_id'] = !empty($payment_data['payment_eo_order_id']) ? $payment_data['payment_eo_order_id'] : 0; //商户订单号

        //创建记录日志
        self::createPyamentTransRecordLog($transRecord);

        //根据条件筛选
        if( !empty($scenario) )
        {
            //在线充值服务卡
            $transRecord['scenario'] = 4;
            $status = $obj->rechargeServiceCardPay($transRecord);
        }
        elseif( (!empty($transRecord['payment_customer_trans_record_service_card_on']) || $transRecord['payment_customer_trans_record_online_balance_pay'] > 0) && $transRecord['payment_customer_trans_record_online_pay'] > 0 )
        {
            //在线支付（在线+余额+服务卡）
            $transRecord['scenario'] = 1;
            $status = $obj->onlineCradBalancePay($transRecord);
        }
        elseif( $transRecord['payment_customer_trans_record_online_pay'] > 0 )
        {
            //在线支付
            $transRecord['scenario'] = 10;
            $status = $obj->onlinePay($transRecord);
        }
        elseif( !empty($transRecord['payment_customer_trans_record_service_card_on']) )
        {
            //服务卡支付 + 优惠券
            $transRecord['scenario'] = 7;  //支付场景
            $status = $obj->onlineServiceCardPay($transRecord);
        }
        elseif( $transRecord['payment_customer_trans_record_online_balance_pay'] > 0 )
        {
            //余额支付 + 优惠券
            $transRecord['scenario'] = 8;
            $status = $obj->onlineBalancePay($transRecord);
        }
        elseif( $transRecord['payment_customer_trans_record_pre_pay'] > 0 )
        {
            //预付费
            $transRecord['scenario'] = 3;
            $status = $obj->perPay($transRecord);
        }
        elseif( $transRecord['payment_customer_trans_record_cash'] > 0 )
        {
            //现金
            $transRecord['scenario'] = 2;
            $status = $obj->cardPay($transRecord);
        }
        else
        {
            return false;
            //exit('没有此条件');
        }


        return $status;
    }

    /**
     * 退款交易记录
     * @param $data
     */
    public static function refundRecord($order_id, $type='order_refund',$orderStatus = 1,$payment_data = [])
    {
        $money = 0;
        //查询订单信息
        $orderInfo = OrderSearch::getOrderInfo($order_id);
        $orderInfo = current($orderInfo);

        //公用部分
        $transRecord['customer_id'] = $orderInfo['customer_id'];     //用户ID
        $transRecord['customer_phone'] = $orderInfo['order_customer_phone'];     //用户手机号码

        $transRecord['admin_id'] = Yii::$app->user->id;
        $transRecord['admin_name'] = Yii::$app->user->identity->username;

        $transRecord['order_id'] = $order_id;           //订单ID
        $transRecord['order_code'] = $orderInfo['order_code'];
        $transRecord['order_batch_code'] = $orderInfo['order_batch_code'];

        //订单渠道
        $transRecord['order_channel_id'] = $orderInfo['channel_id'];   //订单渠道
        $transRecord['payment_customer_trans_record_order_channel'] = $orderInfo['order_channel_name']; //	订单渠道名称

        //支付渠道
        $transRecord['pay_channel_id'] = !empty($payment_data['payment_channel_id']) ? $payment_data['payment_channel_id'] : 20;   //	支付渠道
        $transRecord['payment_customer_trans_record_pay_channel'] = OperationPayChannel::get_post_name($transRecord['pay_channel_id']); //	支付渠道名称

        //交易方式
        $transRecord["payment_customer_trans_record_mode"] = 3;      //交易方式:1消费,2=充值,3=退款,4=赔偿
        $transRecord['payment_customer_trans_record_mode_name'] = self::getCustomerTransRecordModeByName($transRecord['payment_customer_trans_record_mode']); //	交易方式名称

        //服务卡
        $transRecord["payment_customer_trans_record_service_card_on"] = $orderInfo['card_id'];                   //服务卡号
        $transRecord["payment_customer_trans_record_service_card_pay"] = $orderInfo['order_use_card_money'];     //服务卡支付

        //优惠券
        $transRecord["payment_customer_trans_record_coupon_id"] = !empty($orderInfo['coupon_id']) ? $orderInfo['coupon_id'] : 0;    //优惠券ID
        $transRecord["payment_customer_trans_record_coupon_money"] = $orderInfo['order_use_coupon_money'];    //优惠券金额

        //余额支付
        $transRecord["payment_customer_trans_record_online_balance_pay"] = $orderInfo['order_use_acc_balance'];    //余额支付
        $transRecord["payment_customer_trans_record_pre_pay"] = $orderInfo['order_pop_order_money'];    //预付费
        $transRecord['payment_customer_trans_record_cash'] = 0;   //现金支付
        $transRecord['payment_customer_trans_record_online_pay'] = $orderInfo['order_pay_money'];    //在线支付

        //退款总额 = 服务卡金额+优惠券+余额支付+预付费+现金+在线支付
        $money =
            $transRecord["payment_customer_trans_record_service_card_pay"]
            +
            $transRecord["payment_customer_trans_record_coupon_money"]
            +
            $transRecord["payment_customer_trans_record_online_balance_pay"]
            +
            $transRecord["payment_customer_trans_record_pre_pay"]
            +
            $transRecord['payment_customer_trans_record_cash']
            +
            $transRecord['payment_customer_trans_record_online_pay'];

        //订单总额
        $transRecord["payment_customer_trans_record_order_total_money"] = $orderInfo['order_money'];

        //退款总额
        $transRecord['payment_customer_trans_record_refund_money'] = $money;

        //商户订单号
        $transRecord['payment_customer_trans_record_eo_order_id'] = self::createOutTradeNo('00', $order_id);

        //创建记录日志
        self::createPyamentTransRecordLog($transRecord);

        //执行退款
        $model = new PaymentCustomerTransRecord();
        return $model->refundSource($transRecord);
    }

    /**
     * 记录模式
     * @param $mode_id
     * @return string
     */
    public static function getCustomerTransRecordModeByName($mode_id)
    {
        switch($mode_id){
            case 1 :
                return '消费';
                break;
            case 2 :
                return '充值';
                break;
            case 3 :
                return '退款';
                break;
            case 4 :
                return '补偿';
                break;
        }
    }

    /**
     * 签名
     */
    public static function sign($data)
    {
        ksort($data);
        //加密字符串
        $str='1jiajie.com';
        //排除的字段
        $notArray = ['id','payment_customer_trans_record_verify','created_at','updated_at'];
        //加密签名
        foreach( $data as $name=>$val )
        {
            $value = is_numeric($val) ? (int)$val : $val;
            if( !empty($value) && !in_array($name,$notArray))
            {
                if(is_numeric($value) && $value < 1) continue;
                $str .= $value;
            }
        }
        //1jiajie.com      1205620inc144715045249754977115111000658563退款2E家洁2会员充值渠道2
        //1jiajie.comsystem1205620inc144715045249754977115111000658563退款2E家洁2会员充值渠道2
        //return $str;
        return md5(md5($str).'1jiajie.com');
    }

    /**
     * 在线支付(1)
     */
    private function onlineCradBalancePay($data)
    {
        //保留两位小数
        bcscale(2);
        //如果使用余额支付,扣除余额,否则获取当前用户余额
        if( !empty($data['payment_customer_trans_record_online_balance_pay']) && $data['payment_customer_trans_record_online_balance_pay'] > 0 )
        {
            //获取扣除余额后的详细信息
            $customerBalanceInfo = Customer::operateBalance($data['customer_id'],$data['payment_customer_trans_record_online_balance_pay'],$data['payment_customer_trans_record_eo_order_id'],1);
            if($customerBalanceInfo['response'] != 'success' && $data['customer_id'] != $customerBalanceInfo['customer_id'])
            {
                return false;
            }
            //之前余额
            $data["payment_customer_trans_record_befor_balance"] = $customerBalanceInfo['begin_balance'];
            //当前余额
            $data["payment_customer_trans_record_current_balance"] = $customerBalanceInfo['end_balance'];
            //余额交易流水号
            $data["payment_customer_trans_record_balance_transaction_id"] = $customerBalanceInfo['trans_serial'];
        }
        else
        {
            //获取用户余额
            $customerBalance = Customer::getBalanceById($data['customer_id']);
            //如果余额返回数据错误,余额 = 0 ;
            $customerBalance['balance'] = !empty($customerBalance['balance']) ? $customerBalance['balance'] : 0;
            //之前余额
            $data["payment_customer_trans_record_befor_balance"] = $customerBalance['balance'];
            //当前余额
            $data["payment_customer_trans_record_current_balance"] = $customerBalance['balance'];
            //余额交易流水号
            $data["payment_customer_trans_record_balance_transaction_id"] = 0;
        }

        //如果使用服务卡支付
        if( !empty($data["payment_customer_trans_record_service_card_on"]) && !empty($data['payment_customer_trans_record_service_card_pay']) && $data['payment_customer_trans_record_service_card_pay'] > 0 )
        {
            //获取服务卡扣费后详细信息
            //TODO::ZHANGRENZHAO
        }
        else
        {
            //获取服务卡扣费后详细信息
            //TODO::ZHANGRENZHAO
            //服务卡卡号
            $data["payment_customer_trans_record_service_card_on"] = 0;
            //服务卡之前余额
            $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
            //服务卡当前余额
            $data["payment_customer_trans_record_service_card_current_balance"] = 0;
        }
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data["payment_customer_trans_record_order_total_money"]);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);

        //使用场景
        $this->scenario = 1;
        $this->attributes = $data;
        return $this->doSave();
    }

    /**
     * 现金支付(2)
     */
    private function cardPay($data)
    {
        //保留两位小数
        bcscale(2);

        //TODO::刘道强
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //如果余额返回数据错误,余额 = 0 ;
        $customerBalance['balance'] = !empty($customerBalance['balance']) ? $customerBalance['balance'] : 0;
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['balance'];
        //余额交易流水号
        $data["payment_customer_trans_record_balance_transaction_id"] = 0;

        //TODO::张仁钊
        //如果使用服务卡支付
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = 0;
        //服务卡支付金额
        $data["payment_customer_trans_record_service_card_pay"] = 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = 0;
        //服务卡交易流水号
        $data["payment_customer_trans_record_service_card_transaction_id"] = 0;

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data["payment_customer_trans_record_order_total_money"]);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 2;
        $this->attributes = $data;
        return $this->doSave();
    }

    /**
     * 预付费(3)
     */
    private function perPay($data)
    {
        //保留两位小数
        bcscale(2);

        //TODO::刘道强
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //如果余额返回数据错误,余额 = 0 ;
        $customerBalance['balance'] = !empty($customerBalance['balance']) ? $customerBalance['balance'] : 0;
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['balance'];
        //余额交易流水号
        $data["payment_customer_trans_record_balance_transaction_id"] = 0;

        //TODO::张仁钊
        //如果使用服务卡支付
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = 0;
        //服务卡支付金额
        $data["payment_customer_trans_record_service_card_pay"] = 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = 0;
        //服务卡交易流水号
        $data["payment_customer_trans_record_service_card_transaction_id"] = 0;

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data["payment_customer_trans_record_order_total_money"]);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 3;
        $this->attributes = $data;
        return $this->doSave();
    }

    /**
     *  充值（服务卡）(4)
     */
    private function rechargeServiceCardPay($data)
    {

    }

    /**
     * 退款(服务卡)(5)
     */
    private function refundServiceCardPay($data){}

    /**
     * 补偿(6)
     */
    private function compensation($data){}

    /**
     * 服务卡(在线支付)(7)
     */
    private function onlineServiceCardPay($data)
    {

    }

    /**
     * 余额在线支付(8)
     */
    private function onlineBalancePay($data)
    {
        //支付渠道
        $data['pay_channel_id'] = 20;   //支付渠道
        $data['payment_customer_trans_record_pay_channel'] = OperationPayChannel::get_post_name($data['pay_channel_id']); //	支付渠道名称

        //保留两位小数
        bcscale(2);

        //根据订单ID创建交易流水号
        $data['payment_customer_trans_record_eo_order_id'] = self::createOutTradeNo('90', $data['order_id']);

        //TODO::潘高峰
        //优惠券支付
        if( !empty($data['payment_customer_trans_record_coupon_id']) && !empty($data['payment_customer_trans_record_coupon_money']) && $data['payment_customer_trans_record_coupon_money'] > 0 )
        {
            //获取优惠券信息
            $customerCoupon = CouponRule::get_couponinfo($data['customer_phone'], $data['payment_customer_trans_record_coupon_id'], $data['payment_customer_trans_record_coupon_money'], $data['payment_customer_trans_record_eo_order_id'], $data['order_id']);
            $data['payment_customer_trans_record_coupon_id'] = $customerCoupon['data']['coupon_userinfo_id'];   //优惠券ID
            $data['payment_customer_trans_record_coupon_code'] = $customerCoupon['data']['coupon_userinfo_code'];   //优惠券CODE
            $data['payment_customer_trans_record_coupon_money'] = $customerCoupon['data']['coupon_userinfo_price'];   //优惠券金额
            $data['payment_customer_trans_record_coupon_transaction_id'] = $customerCoupon['data']['transaction_id'];   //优惠券交易流水号
        }

        //获取扣除余额后的详细信息
        $customerBalanceInfo = Customer::operateBalance($data['customer_id'], $data['payment_customer_trans_record_online_balance_pay'], $data['payment_customer_trans_record_eo_order_id'], 1);
        if($customerBalanceInfo['response'] != 'success' && $data['customer_id'] != $customerBalanceInfo['customer_id'])
        {
            return false;
        }

        //TODO::刘道强
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalanceInfo['begin_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalanceInfo['end_balance'];
        //余额交易流水号
        $data["payment_customer_trans_record_balance_transaction_id"] = $customerBalanceInfo['trans_serial'];

        //TODO::张仁钊
        //如果使用服务卡支付
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = 0;
        //服务卡支付金额
        $data["payment_customer_trans_record_service_card_pay"] = 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = 0;
        //服务卡交易流水号
        $data["payment_customer_trans_record_service_card_transaction_id"] = 0;

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data['payment_customer_trans_record_order_total_money']);

        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 8;
        $this->attributes = $data;

        return $this->doSave();
    }

    /**
     * 退款（订单）：把订单金额原路退回(9)
     */
    private function refundSource($data)
    {
        //保留两位小数
        bcscale(2);
        //TODO::刘道强
        //退余额
        if( !empty($data['payment_customer_trans_record_online_balance_pay']) && $data['payment_customer_trans_record_online_balance_pay'] > 0 ){
            //获取扣除余额后的详细信息
            $customerBalanceInfo = Customer::operateBalance($data['customer_id'], $data['payment_customer_trans_record_online_balance_pay'], $data['payment_customer_trans_record_eo_order_id'], -1);
            if($customerBalanceInfo['response'] != 'success' && $data['customer_id'] != $customerBalanceInfo['customer_id'])
            {
                return false;
            }
            //之前余额
            $data["payment_customer_trans_record_befor_balance"] = $customerBalanceInfo['begin_balance'];
            //当前余额
            $data["payment_customer_trans_record_current_balance"] = $customerBalanceInfo['end_balance'];
            //余额交易流水号
            $data["payment_customer_trans_record_balance_transaction_id"] = $customerBalanceInfo['trans_serial'];
        }
        else
        {
            //获取用户余额
            $customerBalance = Customer::getBalanceById($data['customer_id']);
            //如果余额返回数据错误,余额 = 0 ;
            $customerBalance['balance'] = !empty($customerBalance['balance']) ? $customerBalance['balance'] : 0;
            //之前余额
            $data["payment_customer_trans_record_befor_balance"] = $customerBalance['balance'];
            //当前余额
            $data["payment_customer_trans_record_current_balance"] = $customerBalance['balance'];
            //余额交易流水号
            $data["payment_customer_trans_record_balance_transaction_id"] = 0;
        }

        //服务卡退款记录
        //TODO::张仁钊
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = 0;

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcsub($lastResult['payment_customer_trans_record_total_money'],$data['payment_customer_trans_record_order_total_money']);
        //退款总额
        $data['payment_customer_trans_record_refund_money'] = $data['payment_customer_trans_record_refund_money'];

        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 9;
        $this->attributes = $data;
        return $this->doSave();

    }

    /**
     * 在线支付（在线）(10)
     */
    private function onlinePay($data)
    {
        //保留两位小数
        bcscale(2);

        //TODO::刘道强
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //如果余额返回数据错误,余额 = 0 ;
        $customerBalance['balance'] = !empty($customerBalance['balance']) ? $customerBalance['balance'] : 0;
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['balance'];
        //余额交易流水号
        $data["payment_customer_trans_record_balance_transaction_id"] = 0;


        //TODO::潘高峰
        //优惠券支付
        if( !empty($data['payment_customer_trans_record_coupon_id']) && !empty($data['payment_customer_trans_record_coupon_money']) && $data['payment_customer_trans_record_coupon_money'] > 0 )
        {
            //获取优惠券信息
            $customerCoupon = CouponRule::get_couponinfo($data['customer_phone'], $data['payment_customer_trans_record_coupon_id'], $data['payment_customer_trans_record_coupon_money'], $data['payment_customer_trans_record_eo_order_id'], $data['order_id']);
            $data['payment_customer_trans_record_coupon_id'] = $customerCoupon['coupon_userinfo_id'];   //优惠券ID
            $data['payment_customer_trans_record_coupon_code'] = $customerCoupon['coupon_userinfo_code'];   //优惠券CODE
            $data['payment_customer_trans_record_coupon_money'] = $customerCoupon['coupon_userinfo_price'];   //优惠券金额
            $data['payment_customer_trans_record_coupon_transaction_id'] = $customerCoupon['transaction_id'];   //优惠券交易流水号
        }


        //TODO::张仁钊
        //如果使用服务卡支付
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = 0;
        //服务卡支付金额
        $data["payment_customer_trans_record_service_card_pay"] = 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = 0;
        //服务卡交易流水号
        $data["payment_customer_trans_record_service_card_transaction_id"] = 0;

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data['payment_customer_trans_record_order_total_money']);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 10;
        $this->attributes = $data;
        return $this->doSave();
    }

    /**
     * 获取用户交易记录最后一条数据
     * @param $customer_id  用户ID
     * @return array|null|\yii\db\ActiveRecord
     */
    private function lastTranRecordResult($customer_id){
        return $result = PaymentCustomerTransRecord::find()->select(
            [
                'customer_id',
                'payment_customer_trans_record_total_money',
                'payment_customer_trans_record_service_card_on',
                'payment_customer_trans_record_service_card_current_balance',
                'payment_customer_trans_record_service_card_befor_balance'
            ]
        )->where(['customer_id'=>$customer_id])->orderBy(['id' => SORT_DESC])->asArray()->one();
    }

    /**
     * 生成商户订单号
     * 年月日+交易类型+随机数+ORDER_ID
     * type:01 正常订单 02 退款 03 赔付
     * channel : 90余额, 91优惠券 ,92服务卡
     * @return bool|string 订单号
     */
    private static function createOutTradeNo($channel='00', $order_id=0)
    {
        //组装支付订单号
        $rand = mt_rand(100,999);
        $date = date("ymd",time());
        //生成商户订单号
        $trans_record_eo_order_id = payment::PAYMENT_CODE.$date.$channel.$rand.$order_id;
        return $trans_record_eo_order_id;
    }

}


