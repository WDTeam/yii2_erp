<?php

namespace core\models\CustomerTransRecord;

use common\models\CustomerTransRecordLog;
use common\models\FinancePayChannel;
use Yii;
use yii\behaviors\TimestampBehavior;

class CustomerTransRecord extends \common\models\CustomerTransRecord
{
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
     * @param $data 数据对象
     * @return bool
     */
    public static function analysisRecord($data)
    {
        //查询订单信息
        $orderSearch = new \core\models\order\OrderSearch;
        $orderInfo = $orderSearch->search(['OrderSearch'=>['id'=>1]])->query->one();
        $data['customer_trans_record_online_service_card_on'] = $orderInfo->orderExtPay->card_id;    //服务卡ID
        $data['customer_trans_record_online_service_card_pay'] = $orderInfo->orderExtPay->order_use_card_money;   //服务卡内容
        $data['customer_trans_record_coupon_money'] = $orderInfo->orderExtPay->order_use_coupon_money; //优惠券金额
        $data['customer_trans_record_online_balance_pay'] = $orderInfo->orderExtPay->order_use_acc_balance;  //余额支付
        $data['customer_trans_record_order_total_money'] = $orderInfo->order_money;  //订单总额
        $data['order_channel_id'] = $data['general_pay_source']; //订单渠道
        $data['customer_trans_record_transaction_id'] = $data['general_pay_transaction_id']; //交易流水号

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
            $data['customer_trans_record_mode'] = 1; //交易方式:1消费,2=充值,3=退款,4=补偿
            //服务卡 or 余额 + 在线 + 优惠券
            if( (!empty($data['customer_trans_record_online_service_card_on']) || !empty($data['customer_trans_record_online_balance_pay'])) && !empty($data['general_pay_money']) && !empty($data['customer_trans_record_coupon_money']) )
            {

                $data['scenario'] = 1;  //支付场景
            }
            elseif( !empty($data['customer_trans_record_online_service_card_on']) && !empty($data['customer_trans_record_coupon_money']) )
            {
            //服务卡支付 + 优惠券
                $data['scenario'] = 1;  //支付场景
            }
            elseif( !empty($data['customer_trans_record_online_balance_pay']) && !empty($data['customer_trans_record_coupon_money']) )
            {
            //余额支付 + 优惠券
                $data['scenario'] = 1;  //支付场景
            }
            elseif( !empty($data['customer_trans_record_online_service_card_on']) )
            {
            //服务卡
                $data['scenario'] = 1;  //支付场景
            }
            elseif( !empty($data['customer_trans_record_online_balance_pay']) )
            {
            //余额
                $data['scenario'] = 1;  //支付场景
            }else{
                exit("未知类型");
            }

        }

        var_dump(self::createRecord($data));
    }
}


