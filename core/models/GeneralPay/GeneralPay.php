<?php

namespace core\models\GeneralPay;

use common\models\GeneralPayRefund;
use core\models\CustomerTransRecord\CustomerTransRecord;
use core\models\Customer;
use Yii;

class GeneralPay extends \common\models\GeneralPay
{

    /**
     * @inheritdoc
     * @return GeneralPayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeneralPayQuery(get_called_class());
    }

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
        //用户服务卡扣款
        Customer::decBalance($data['customer_id'],$data['general_pay_actual_money']);
        //用户交易记录
        return CustomerTransRecord::analysisRecord($data);
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
     * 调用(调起)在线支付,发送给支付接口的数据
     * @param integer $pay_money 支付金额
     * @param integer $customer_id 消费者ID
     * @param integer $channel_id 渠道ID
     * @param integer $order_id 订单ID
     * @param integer $partner 第三方合作号
     */
    public static function getPayParams( $pay_money,$customer_id,$channel_id,$partner,$order_id=0,$ext_params=[] )
    {

        $data = [
            "general_pay_money" => $pay_money,
            "customer_id" => $customer_id,
            "general_pay_source" => $channel_id,
            "partner" => $partner,
            "order_id" => $order_id
        ];

        $data = array_merge($data,$ext_params);

        //实例化模型
        $model = new GeneralPay();

        //查询订单是否已经支付过
        if( !empty($data['order_id']) )
        {
            $order = $model->getPayStatus($data['order_id'],1);

            if(!empty($order))
            {
                return ['status'=>0 , 'info'=>'订单已经支付过', 'data'=>''];
            }
        }

        //在线支付（online_pay），在线充值（pay）
        if(empty($data['order_id']))
        {
            if($channel_id == '2'){
                $scenario = 'wx_h5_pay';
                $data['openid'] = $ext_params['openid'];    //微信openid
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
