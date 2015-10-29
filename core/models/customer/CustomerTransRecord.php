<?php

namespace core\models\customer;

use dbbase\models\customer\CustomerTransRecordLog;
use dbbase\models\finance\FinancePayChannel;
use dbbase\models\payment\GeneralPayCommon;
use Yii;
use yii\behaviors\TimestampBehavior;

class CustomerTransRecord extends \dbbase\models\customer\CustomerTransRecord
{

    /**
     * 根据用户ID返回消费记录
     * @param $customer_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function queryRecord($customer_id)
    {
        return CustomerTransRecord::find()->where(["customer_id"=>$customer_id])->asArray()->all();
    }
    /**
     * 创建交易记录
     * @param $data 数据
     */
    public static function createRecord($data)
    {
        //记录日志
        $obj = new self;
        $obj->on('insertLog',[new CustomerTransRecordLog(),'insertLog'],$data);
        $obj->trigger('insertLog');

        //验证是否存在场景
        if(empty($data['scenario'])){
            return false;
        }

        //使用场景
        $obj->scenario = $data['scenario'];
        $obj->attributes = $data;
        return $obj->add();
    }

    /**
     * 分析创建(支付/充值)交易记录
     * @param $data 数据对象
     * @return bool
     */
    public static function analysisRecord($data)
    {
        //公用部分
        $data['order_channel_id'] = $data['general_pay_source']; //订单渠道
        $data['customer_trans_record_transaction_id'] = !empty($data['general_pay_transaction_id']) ? $data['general_pay_transaction_id'] : 0; //交易流水号

        //用户充值
        if( empty($data['order_id']) ){
            //创建服务卡
            //serviceCard::create();
            $card = [
                'card_on' => mt_rand(10000,99999),
            ];

            //服务卡充值
            $data['customer_trans_record_mode'] = 2; //交易方式:1消费,2=充值,3=退款,4=补偿
            $data['customer_trans_record_online_service_card_on'] =  $card['card_on'];//服务卡号
            $data['customer_trans_record_online_service_card_pay'] = $data['general_pay_money'];//服务卡支付
            $data['scenario'] = 4;//支付场景(用户充值)

        }else{

        //支付订单

            //查询订单信息
            $orderInfo = GeneralPayCommon::orderInfo($data['order_id']);
            $data['customer_trans_record_online_service_card_on'] = $orderInfo->orderExtPay->card_id;    //服务卡ID
            $data['customer_trans_record_online_service_card_pay'] = $orderInfo->orderExtPay->order_use_card_money;   //服务卡内容
            $data['customer_trans_record_coupon_money'] = $orderInfo->orderExtPay->order_use_coupon_money; //优惠券金额
            $data['customer_trans_record_online_balance_pay'] = $orderInfo->orderExtPay->order_use_acc_balance;  //余额支付
            $data['customer_trans_record_order_total_money'] = $orderInfo->order_money;  //订单总额
            $data['order_pop_order_money'] = $orderInfo->orderExtPop->order_pop_order_money;  //预付费

            $data['customer_trans_record_online_pay'] = !empty($data['general_pay_actual_money']) ? $data['general_pay_actual_money'] : 0;  //在线支付
            $data['customer_trans_record_pre_pay'] = !empty($data['customer_trans_record_pre_pay']) ? $data['customer_trans_record_pre_pay'] : 0;  //预付费
            $data['customer_trans_record_cash'] = !empty($data['customer_trans_record_cash']) ? $data['customer_trans_record_cash'] : 0;
            $data['customer_trans_record_mode'] = 1; //交易方式:1消费,2=充值,3=退款,4=补偿

            //服务卡 or 余额 + 在线 + 优惠券
            if( (!empty($data['customer_trans_record_online_service_card_on']) || $data['customer_trans_record_online_balance_pay'] > 0) && $data['customer_trans_record_online_pay'] > 0 )
            {
                $data['scenario'] = 1;  //支付场景
            }
            elseif( $data['customer_trans_record_online_pay'] > 0 )
            {
                //在线支付
                $data['scenario'] = 10;  //支付场景
            }
            elseif( !empty($data['customer_trans_record_online_service_card_on']) )
            {
            //服务卡支付 + 优惠券
                $data['scenario'] = 7;  //支付场景
            }
            elseif( $data['customer_trans_record_online_balance_pay'] > 0 )
            {
            //余额支付 + 优惠券
                $data['scenario'] = 8;  //支付场景
            }
            elseif( $data['customer_trans_record_pre_pay'] > 0 )
            {
            //预付费
                $data['scenario'] = 3;  //支付场景
            }
            elseif( $data['customer_trans_record_cash'] > 0 )
            {
            //现金
                $data['scenario'] = 2;  //支付场景
            }
            else
            {
                exit('没有此条件');
            }
        }

        return self::createRecord($data);
    }

    /**
     * 退款交易记录
     * @param $data
     */
    public static function refundRecord($data)
    {
        //公用部分
        $data['order_channel_id'] = $data['general_pay_source']; //订单渠道
        $data['customer_trans_record_online_pay'] = !empty($data['general_pay_actual_money']) ? $data['general_pay_actual_money'] : 0;  //在线支付
        $data['customer_trans_record_transaction_id'] = !empty($data['general_pay_transaction_id']) ? $data['general_pay_transaction_id'] : 0; //交易流水号
        $data['customer_trans_record_mode'] = 3; //交易方式:1消费,2=充值,3=退款,4=补偿

        //查询订单信息
        $orderInfo = GeneralPayCommon::orderInfo($data['order_id']);
        $data['customer_trans_record_online_service_card_on'] = $orderInfo->orderExtPay->card_id;    //服务卡ID
        $data['customer_trans_record_online_service_card_pay'] = $orderInfo->orderExtPay->order_use_card_money;   //服务卡内容
        $data['customer_trans_record_coupon_money'] = $orderInfo->orderExtPay->order_use_coupon_money; //优惠券金额
        $data['customer_trans_record_online_balance_pay'] = $orderInfo->orderExtPay->order_use_acc_balance;  //余额支付
        $data['customer_trans_record_order_total_money'] = $orderInfo->order_money;  //订单总额
        $data['customer_trans_record_pre_pay'] = $orderInfo->orderExtPop->order_pop_order_money;  //预付费
        $data['customer_trans_record_cash'] = !empty($data['customer_trans_record_cash']) ? $data['customer_trans_record_cash'] : 0;  //现金支付

        $data['scenario'] = 9;  //支付场景
        return self::createRecord($data);
    }

}


