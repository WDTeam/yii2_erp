<?php

namespace core\models\payment;

use core\models\customer\Customer;
use core\models\order\OrderSearch;
use core\models\payment\Payment;

use dbbase\models\finance\FinanceOrderChannel;
use dbbase\models\payment\PaymentCustomerTransRecordLog;
use dbbase\models\finance\FinancePayChannel;
use dbbase\models\payment\PaymentCommon;

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
        $obj = new self;
        $obj->on('insertLog',[new PaymentCustomerTransRecordLog(),'insertLog'],$data);
        $obj->trigger('insertLog');
    }

    /**
     *
     * @param $order_id 订单ID|支付记录ID
     * @param $order_channel_id 渠道ID
     * @param string $type  支付|充值类型
     * @return bool
     */
    /**
     * 分析创建(支付/充值)交易记录
     * @param $order_id 订单ID|支付记录ID
     * @param $order_channel_id 渠道ID
     * @param string $type  支付|充值类型
     * @param int $orderStatus  支付|充值类型
     * @return bool
     */
    public static function analysisRecord($order_id,$order_channel_id,$type='order_pay',$orderStatus = 1)
    {
        $obj = new self();
        if($type == 'order_pay')
        {
            //如果支付订单,查询订单数据
            $fields = [
                'id as order_id',
                'order_batch_code',
                'channel_id',
                'order_money',
                'customer_id',
                'order_pay_type',
                'order_pay_money',
                'order_use_acc_balance',
                'card_id',
                'order_use_card_money',
                'order_use_coupon_money',
                'order_use_promotion_money',
                'order_pop_order_money'
            ];
            $dataArray = OrderSearch::getOrderInfo($order_id,$fields,$orderStatus);
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
                $data['order_pay_money'] = $onlinePay;
            }
            else
            {
                $data = current($dataArray);
            }
            //组装数据
            $transRecord["payment_customer_trans_record_mode"] = 1;      //交易方式:1消费,2=充值,3=退款,4=赔偿
            $transRecord["payment_customer_trans_record_service_card_on"] = $data['card_id'];                   //服务卡号
            $transRecord["payment_customer_trans_record_service_card_pay"] = $data['order_use_card_money'];     //服务卡支付
            $transRecord["payment_customer_trans_record_coupon_money"] = $data['order_use_coupon_money'];    //优惠券金额
            $transRecord["payment_customer_trans_record_online_balance_pay"] = $data['order_use_acc_balance'];    //余额支付
            $transRecord["payment_customer_trans_record_order_total_money"] = $data['order_money'];               //订单总额
            $transRecord["payment_customer_trans_record_pre_pay"] = $data['order_pop_order_money'];    //预付费
            $transRecord['payment_customer_trans_record_cash'] = ($data['order_pay_type'] == 1) ? $data['order_money'] : 0;   //现金支付
            $transRecord['payment_customer_trans_record_online_pay'] = $data['order_pay_money'];    //在线支付
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
        //获取订单渠道
        $orderChannelInfo = FinanceOrderChannel::get_order_channel_info($order_channel_id);

        //公用部分
        $transRecord['customer_id'] = $data['customer_id'];     //用户ID
        $transRecord['order_id'] = $data['order_id'];           //订单ID
        $transRecord['order_channel_id'] = $order_channel_id;   //订单渠道
        $transRecord['payment_customer_trans_record_order_channel'] = $orderChannelInfo->finance_order_channel_name; //	订单渠道名称
        $transRecord['pay_channel_id'] = $orderChannelInfo->pay_channel_id;   //	支付渠道
        $transRecord['payment_customer_trans_record_pay_channel'] = FinancePayChannel::getPayChannelByName($orderChannelInfo->pay_channel_id); //	支付渠道名称
        $transRecord['payment_customer_trans_record_mode_name'] = self::getCustomerTransRecordModeByName($transRecord['payment_customer_trans_record_mode']); //	交易方式名称

        //创建记录日志
        try {
            self::createPyamentTransRecordLog($transRecord);
        } catch (Exception $e) {
            //创建记录日志失败
        }

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
            //如果order_pay_money == 0 直接返回false,去线上支付
            if( $data['order_pay_money'] != 0 && $data['order_pay_money'] > 0){
                $status = false;
            }else{
                //余额支付 + 优惠券
                $transRecord['scenario'] = 8;
                $status = $obj->onlineBalancePay($transRecord);
            }
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
        //dump($transRecord);
        return $status;
    }

    /**
     * 退款交易记录
     * @param $data
     */
    public static function refundRecord($data)
    {
        //公用部分
        $data['order_channel_id'] = $data['payment_source']; //订单渠道
        $data['payment_customer_trans_record_online_pay'] = !empty($data['payment_actual_money']) ? $data['payment_actual_money'] : 0;  //在线支付
        $data['payment_customer_trans_record_transaction_id'] = !empty($data['payment_transaction_id']) ? $data['payment_transaction_id'] : 0; //交易流水号
        $data['payment_customer_trans_record_mode'] = 3; //交易方式:1消费,2=充值,3=退款,4=补偿

        //查询订单信息
        $orderInfo = PaymentCommon::orderInfo($data['order_id']);
        $data['payment_customer_trans_record_service_card_on'] = $orderInfo->orderExtPay->card_id;    //服务卡ID
        $data['payment_customer_trans_record_service_card_pay'] = $orderInfo->orderExtPay->order_use_card_money;   //服务卡内容
        $data['payment_customer_trans_record_coupon_money'] = $orderInfo->orderExtPay->order_use_coupon_money; //优惠券金额
        $data['payment_customer_trans_record_online_balance_pay'] = $orderInfo->orderExtPay->order_use_acc_balance;  //余额支付
        $data['payment_customer_trans_record_order_total_money'] = $orderInfo->order_money;  //订单总额
        $data['payment_customer_trans_record_pre_pay'] = $orderInfo->orderExtPop->order_pop_order_money;  //预付费
        $data['payment_customer_trans_record_cash'] = !empty($data['payment_customer_trans_record_cash']) ? $data['payment_customer_trans_record_cash'] : 0;  //现金支付

        $data['scenario'] = 9;  //支付场景
        return self::createRecord($data);
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
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = bcsub($customerBalance['customer_balance'],$data["payment_customer_trans_record_online_balance_pay"]);
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //TODO::取服务卡余额计算
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = !empty($lastResult['payment_customer_trans_record_service_card_on']) ? $lastResult['payment_customer_trans_record_service_card_on'] : 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] :0;
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data["payment_customer_trans_record_order_total_money"]);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);

        //扣除用户余额
        if( !empty($data["payment_customer_trans_record_online_balance_pay"]) && $data["payment_customer_trans_record_online_balance_pay"] > 0 )
        {
            //用户服务卡扣款
            Customer::decBalance($data['customer_id'],$data['payment_customer_trans_record_online_balance_pay']);
        }

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
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['customer_balance'];

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = !empty($lastResult['payment_customer_trans_record_service_card_on']) ? $lastResult['payment_customer_trans_record_service_card_on'] : 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] :0;
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
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['customer_balance'];
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = !empty($lastResult['payment_customer_trans_record_service_card_on']) ? $lastResult['payment_customer_trans_record_service_card_on'] : 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] :0;
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
        //保留两位小数
        bcscale(2);
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['customer_balance'];
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = 0;
        //服务卡当前余额
        //$data["payment_customer_trans_record_service_card_current_balance"] = $data['payment_customer_trans_record_service_card_pay'];
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = !empty($lastResult['payment_customer_trans_record_total_money']) ? $lastResult['payment_customer_trans_record_total_money'] :0;
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 4;
        $this->attributes = $data;
        return $this->doSave();

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
        //保留两位小数
        bcscale(2);
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['customer_balance'];
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //服务卡卡号
        //TODO::获取服务卡余额
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = !empty($lastResult['payment_customer_trans_record_service_card_on']) ? $lastResult['payment_customer_trans_record_service_card_on'] : 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] :0;
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data['payment_customer_trans_record_order_total_money']);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 8;
        $this->attributes = $data;

        //用户服务卡扣款
        Customer::decBalance($data['customer_id'],$data['payment_customer_trans_record_online_balance_pay']);

        return $this->doSave();
    }

    /**
     * 余额在线支付(8)
     */
    private function onlineBalancePay($data)
    {
        //保留两位小数
        bcscale(2);
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = bcsub($customerBalance['customer_balance'],$data["payment_customer_trans_record_online_balance_pay"]);
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = !empty($lastResult['payment_customer_trans_record_service_card_on']) ? $lastResult['payment_customer_trans_record_service_card_on'] : 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] :0;
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data['payment_customer_trans_record_order_total_money']);
        //签名
        unset($data['scenario']);
        $data['payment_customer_trans_record_verify'] = self::sign($data);
        //使用场景
        $this->scenario = 8;
        $this->attributes = $data;

        //用户服务卡扣款
        Customer::decBalance($data['customer_id'],$data['payment_customer_trans_record_online_balance_pay']);

        return $this->doSave();
    }

    /**
     * 退款（订单）：把订单金额原路退回(9)
     */
    private function refundSource($data)
    {
        //保留两位小数
        bcscale(2);

        if( !empty($data["payment_customer_trans_record_service_card_on"]) && !empty($data["payment_customer_trans_record_service_card_pay"]) ){
            //TODO::获取服务卡余额
            $serviceCardBalance = 0;
            //服务卡卡号
            $data["payment_customer_trans_record_service_card_on"] = $serviceCardBalance['payment_customer_trans_record_service_card_on'];
            //服务卡之前余额
            $data["payment_customer_trans_record_service_card_befor_balance"] = $serviceCardBalance;
            //服务卡当前余额
            $data["payment_customer_trans_record_service_card_current_balance"] = bcadd($serviceCardBalance,$data['payment_customer_trans_record_service_card_pay']);
        }
        else
        {
            //获取用户余额
            $customerBalance = Customer::getBalanceById($data['customer_id']);
            //之前余额
            $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
            //当前余额
            $data["payment_customer_trans_record_current_balance"] = bcadd($customerBalance['customer_balance'],$data["payment_customer_trans_record_online_balance_pay"]);
        }

        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //交易总额
        $data["payment_customer_trans_record_total_money"] = bcadd($lastResult['payment_customer_trans_record_total_money'],$data['payment_customer_trans_record_order_total_money']);
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
        //获取用户余额
        $customerBalance = Customer::getBalanceById($data['customer_id']);
        //之前余额
        $data["payment_customer_trans_record_befor_balance"] = $customerBalance['customer_balance'];
        //当前余额
        $data["payment_customer_trans_record_current_balance"] = $customerBalance['customer_balance'];
        //获取当前用户最后一次交易记录
        $lastResult = $this->lastTranRecordResult($data['customer_id']);
        //服务卡卡号
        $data["payment_customer_trans_record_service_card_on"] = !empty($lastResult['payment_customer_trans_record_service_card_on']) ? $lastResult['payment_customer_trans_record_service_card_on'] : 0;
        //服务卡之前余额
        $data["payment_customer_trans_record_service_card_befor_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $data["payment_customer_trans_record_service_card_current_balance"] = !empty($lastResult['payment_customer_trans_record_service_card_current_balance']) ? $lastResult['payment_customer_trans_record_service_card_current_balance'] :0;
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

}


