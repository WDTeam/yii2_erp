<?php

namespace core\models\payment;

use dbbase\models\payment\GeneralPayCommon;
use dbbase\models\payment\GeneralPayRefund;
use core\models\customer\CustomerTransRecord;
use core\models\customer\Customer;
use Yii;

class GeneralPay extends \dbbase\models\payment\GeneralPay
{



    /**
     * @param $condition
     * @param $fileds
     * @return array|GeneralPay|null
     */
    public static function getGeneralPayByInfo($condition,$fileds = '*')
    {
        return GeneralPay::find()->select($fileds)->where($condition)->asArray()->one();
    }

    /**
     * 服务卡支付
     * @param $data  订单数据
     */
    public static function serviceCradPay($data)
    {
        //用户服务卡扣款
        //ServiceCard();
        //用户交易记录
        return CustomerTransRecord::analysisRecord($data);
    }

    /**
     * 余额支付
     * @param $data  订单数据
     */
    public static function balancePay($data)
    {

        $data['general_pay_source'] = 20;
        //获取订单数据
        $orderInfo = GeneralPayCommon::orderInfo($data['order_id']);
        $orderInfo = array_merge($orderInfo->getAttributes(),$orderInfo->orderExtPay->getAttributes());
        dump($orderInfo);exit;
        //用户服务卡扣款
        Customer::decBalance($data['customer_id'],$orderInfo['order_use_acc_balance']);
        dump(Customer::decBalance($data['customer_id'],$orderInfo['order_use_acc_balance']));
        exit;
        //用户交易记录
        return CustomerTransRecord::analysisRecord(array_merge($orderInfo,$data));
    }

    /**
     * 现金支付
     * @param $data 订单数据
     */
    public static function cashPay($data)
    {
        //用户交易记录
        return CustomerTransRecord::analysisRecord($data);
    }

    /**
     * 预付费
     * @param $data 订单数据
     */
    public static function prePay($data)
    {
        //用户交易记录
        return CustomerTransRecord::analysisRecord($data);
    }

    /**
     * 批量下单支付接口
     * @param $customer_id
     * @param $channel_id
     * @param $order_id
     */
    public static function getBatchPayParams($customer_id, $channel_id, $order_id)
    {
        //判断订单编号是否正确
        if(!is_array($order_id))
        {
            return ['status'=>0 , 'info'=>'订单编号必须是一个数组', 'data'=>''];
        }

        //判断订单支付状态

        //查询订单金额
        $orderPayMoney = GeneralPaySearch::getOrderSumMoney($order_id);
        if( empty($orderPayMoney) )
        {
            return ['status'=>0 , 'info'=>'未找到订单支付金额或订单金额为0', 'data'=>''];
        }

        //支付数据组装
        $payData = [
            "general_pay_money" => $orderPayMoney,
            "customer_id" => $customer_id,
            "general_pay_source" => $channel_id,
            "partner" => $partner,
            "order_id" => $order_id
        ];

    }

    /**
     * 调用(调起)在线支付,发送给支付接口的数据
     * @param integer $pay_money 支付金额
     * @param integer $customer_id 消费者ID
     * @param integer $channel_id 渠道ID
     * @param integer $order_id 订单ID
     * @param integer $partner 第三方合作号
     */
    public static function getPayParams( $pay_money,$customer_id,$channel_id,$partner,$order_id=0,$ext_params=[] )
    {
        //实例化模型
        $model = new GeneralPay();

        //查询订单是否已经支付过1
        if( !empty($order_id) )
        {
            //查询订单支付状态
            $order = GeneralPaySearch::searchPayStatus($order_id,1);
            if(!empty($order))
            {
                return ['status'=>0 , 'info'=>'订单已经支付过', 'data'=>''];
            }

            //获取订单支付金额
            $orderInfo = GeneralPayCommon::orderInfo($order_id);
            $pay_money = $orderInfo->orderExtPay->order_pay_money;
            if( $pay_money <= 0 )
            {
                return ['status'=>0 , 'info'=>'未找到订单支付金额', 'data'=>''];
            }
        }

        $data = [
            "general_pay_money" => $pay_money,
            "customer_id" => $customer_id,
            "general_pay_source" => $channel_id,
            "partner" => $partner,
            "order_id" => $order_id
        ];
        $data = array_merge($data,$ext_params);

        //在线支付（online_pay），在线充值（pay）
        if(empty($data['order_id']))
        {
            if($channel_id == '2'){
                $scenario = 'wx_h5_pay';
                $data['openid'] = $ext_params['openid'];    //微信openid
            }elseif($channel_id == '6' || $channel_id == '24'){
                $scenario = 'alipay_web_pay';
                $data['return_url'] = !empty($ext_params['return_url']) ? $ext_params['return_url'] :'';    //同步回调地址
                $data['show_url'] = !empty($ext_params['show_url']) ? $ext_params['show_url']: '';    //显示商品URL
            }elseif($channel_id == '7'){
                $scenario = 'zhidahao_h5_pay';
                $data['customer_name'] = $ext_params['customer_name'];  //商品名称
                $data['customer_mobile'] = $ext_params['customer_mobile'];  //用户电话
                $data['customer_address'] = $ext_params['customer_address'];  //用户地址
                $data['order_source_url'] = $ext_params['order_source_url'];  //订单详情地址
                $data['page_url'] = $ext_params['page_url'];  //订单跳转地址
                $data['goods_name'] = $ext_params['goods_name'];  //订单名称
                $data['detail'] = $ext_params['detail'];  //订单详情
            }else{
                $scenario = 'pay';
            }
            //交易方式
            $data['general_pay_mode'] = 1;//充值
        }
        else
        {
            if($channel_id == '2'){
                $scenario = 'wx_h5_online_pay';
                $data['openid'] = $ext_params['openid'];    //微信openid
            }elseif($channel_id == '6' || $channel_id == '24'){
                $scenario = 'alipay_web_online_pay';
                $data['return_url'] = !empty($ext_params['return_url']) ? $ext_params['return_url'] :'';    //同步回调地址
                $data['show_url'] = !empty($ext_params['show_url']) ? $ext_params['show_url']: '';    //显示商品URL
            }elseif($channel_id == '7'){
                $scenario = 'zhidahao_h5_online_pay';
                $data['customer_name'] = $ext_params['customer_name'];  //商品名称
                $data['customer_mobile'] = $ext_params['customer_mobile'];  //用户电话
                $data['customer_address'] = $ext_params['customer_address'];  //用户地址
                $data['order_source_url'] = $ext_params['order_source_url'];  //订单详情地址
                $data['page_url'] = $ext_params['page_url'];  //订单跳转地址
                $data['goods_name'] = $ext_params['goods_name'];  //订单名称
                $data['detail'] = $ext_params['detail'];  //订单详情
            }else{
                $scenario = 'online_pay';
            }
            //交易方式
            $data['general_pay_mode'] = 3;//在线支付
        }

        //支付来源,定义分发支付渠道
        $data['general_pay_source_name'] = $model->source($data['general_pay_source']);

        //使用场景
        $model->scenario = $scenario;

        $model->attributes = $data;

        //验证数据
        if( $model->validate() && $model->save() )
        {
            //返回组装数据
            return ['status'=>0 , 'info'=>'数据返回成功', 'data'=>$model->call_pay($data)];
        }
        else
        {
            return ['status'=>0 , 'info'=>'数据返回失败', 'data'=>$model->errors];
        }
    }


    /**
     * 银联支付回调
     * @param $data
     */
    public function upAppNotify($data){
        parent::upAppNotify($data);
    }

    /**
     * 支付宝APP回调
     * @param $data
     */
    public function alipayAppNotify($data){
        parent::alipayAppNotify($data);
    }

    /**
     * 支付宝APP回调
     * @param $data
     */
    public function alipayWapNotify($data){
        parent::alipayWapNotify($data);
    }

    /**
     * 微信APP回调
     * @param $data
     */
    public function wxAppNotify($data){
        parent::wxAppNotify($data);
    }

    /**
     * 百付宝APP回调
     * @param $data
     */
    public function bfbAppNotify($data){
        parent::bfbAppNotify($data);
    }

    /**
     * 微信H5回调
     * @param $data
     */
    public function wxH5Notify($data){
        parent::wxH5Notify($data);
    }

    /**
     * 百度直达号回调
     * @param $data
     */
    public function zhidahaoH5Notify($data){
        parent::zhidahaoH5Notify($data);
    }

}
