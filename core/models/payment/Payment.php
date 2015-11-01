<?php

namespace core\models\payment;

use core\models\payment\PaymentCustomerTransRecord;
use core\models\customer\Customer;
use core\models\order\OrderSearch;

use dbbase\models\payment\PaymentCommon;
use dbbase\models\payment\PaymentRefund;

use Yii;

class Payment extends \dbbase\models\payment\Payment
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @param $condition
     * @param $fileds
     * @return array|Payment|null
     */
    public static function getPaymentByInfo($condition,$fileds = '*')
    {
        return Payment::find()->select($fileds)->where($condition)->asArray()->one();
    }


    /**
     * 通过支付ID获取支付成功数据
     * @param $id
     * @return array
     */
    public static function getPaymentPayStatusData($id)
    {
        $condition = ['id'=>$id,'payment_status'=>1];
        $query = new \yii\db\Query();
        $data = $query->from(self::tableName())->where($condition)->one();
        return $data;
    }


    /**
     * 调用(调起)在线支付,发送给支付接口的数据
     * @param $payment_type 支付类型,1普通订单,2周期订单,3充值订单
     * @param $customer_id  消费者ID
     * @param $channel_id   渠道ID
     * @param int $order_id 订单ID
     * @param array $ext_params 部分渠道扩展参数
     * @return array
     */
    public static function getPayParams( $payment_type,$customer_id,$channel_id,$order_id,$ext_params=[] )
    {
        //实例化模型
        $model = new Payment();

        //查询订单支付状态
        $order = PaymentSearch::searchPayStatus($order_id,1);
        if(!empty($order))
        {
            return ['status'=>0 , 'info'=>'订单已经支付过', 'data'=>''];
        }

        //判断支付类型
        switch($payment_type)
        {
            case 1: //1普通订单
            case 2: //2周期订单
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
                $dataArray = OrderSearch::getOrderInfo($order_id,$fields,$payment_type);

                $pay_money = 0;
                //判断是普通订单还是周期订单
                if(count($dataArray) > 1)
                {
                    //计算周期订单总金额和优惠券金额
                    foreach( $dataArray as $val )
                    {
                        $pay_money += $val['order_pay_money'];    //在线支付
                    }
                }
                else
                {
                    $one = current($dataArray);
                    $pay_money = $one['order_pay_money'];
                }
                $data['payment_mode'] = 3;//在线支付
                break;
            case 3: //3充值订单
                //TODO::获取服务卡金额
                $data['payment_mode'] = 1;//充值
                break;
        }

        //查询订单是否已经支付过1
        if( $pay_money <= 0 )
        {
            return ['status'=>0 , 'info'=>'未找到订单支付金额', 'data'=>''];
        }

        $data = [
            "payment_money" => $pay_money,
            "customer_id" => $customer_id,
            "payment_source" => $channel_id,
            "order_id" => $order_id,
            'payment_type' => $payment_type,
        ];

        $data = array_merge($data,$ext_params);

        //在线支付（online_pay），在线充值（pay）
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

        //支付来源,定义分发支付渠道
        $data['payment_source_name'] = $model->source($data['payment_source']);

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
