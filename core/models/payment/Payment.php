<?php

namespace core\models\payment;

use core\models\finance\FinancePayChannel;
use core\models\operation\OperationServiceCardSellRecord;
use core\models\order\Order;
use core\models\order\OrderOtherDict;
use core\models\payment\PaymentCustomerTransRecord;
use core\models\customer\Customer;
use core\models\order\OrderSearch;


use Yii;
use yii\base\Exception;

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
     * 查询支付详细数据
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
    public static function getPayParams( $payment_type, $customer_id, $channel_id, $order_id, $ext_params=[] )
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
                $pay_money = 0;    //在线支付
                $order_use_acc_balance = 0;  //使用余额
                $card_id = 0;
                $order_use_card_money = 0;   //使用服务卡

                //判断是普通订单还是周期订单
                if(count($dataArray) > 1)
                {
                    //计算周期订单总金额和优惠券金额
                    foreach( $dataArray as $val )
                    {
                        $pay_money += $val['order_pay_money'];    //在线支付
                        $order_use_acc_balance += $val['order_use_acc_balance'];    //使用余额
                        $card_id = $val['card_id'];    //服务卡号
                        $order_use_card_money += $val['order_use_card_money'];    //使用服务卡
                    }
                }
                else
                {
                    $one = current($dataArray);
                    $pay_money = $one['order_pay_money'];   //在线支付
                    $order_use_acc_balance = $one['order_use_acc_balance'];    //使用余额
                    $card_id = $one['card_id'];    //服务卡号
                    $order_use_card_money = $one['order_use_card_money'];    //使用服务卡
                }

                //判断余额是否和订单余额不一致
                if( !empty($order_use_acc_balance) && $order_use_acc_balance > 0 )
                {
                    //获取用户余额
                    $customerBalance = Customer::getBalanceById($customer_id);
                    if($customerBalance['balance'] < $order_use_acc_balance)
                    return ['status'=>0 , 'info'=>'用户余额不足,请重新下单', 'data'=>''];
                }

                //判断服务卡余额是否和订单服务卡支付不一致
                if( !empty($order_use_card_money) && $order_use_card_money > 0 && !empty($card_id) )
                {
                    //TODO::服务卡逻辑
                }
                $payment_mode = 1;//在线支付
                break;
            case 3: //3充值订单
                //TODO::获取服务卡金额
//                OperationServiceCardSellRecord->createServiceCardSellRecord($customer_id, $order_id, 0, 1, 'APP客户端', 1000);
                $payment_mode = 2;//充值
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
            'payment_mode' => $payment_mode,
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

        //获取渠道ID和名称
        $data['payment_channel_id'] = self::getParamsToPayChannel($data['payment_source']);
        $data['payment_channel_name'] = FinancePayChannel::getPayChannelByName($data['payment_channel_id']);

        //使用场景
        $model->scenario = $scenario;
        $model->attributes = $data;

        //插入数据
        if($model->doSave())
        {
            //分配渠道
            return ['status'=>1 , 'info'=>'数据返回成功', 'data'=>$model->getPayChannelData($data)];
        }
        else
        {
            return ['status'=>0 , 'info'=>'数据返回失败', 'data'=>$model->errors];
        }
    }

    /**
     * 转换支付渠道
     * @param $toid
     * 7	支付宝
     * 8	百度钱包
     * 10	微信后台
     * 12	银联后台
     * 13	财付通
     * 20   余额支付
     */
    public static function getParamsToPayChannel($toid)
    {
        switch($toid)
        {
            case 1:
                $channel = 13;//财付通
                break;
            case 2:
                $channel = 10;//微信后台
                break;
            case 3:
                $channel = 8;//百度钱包
                break;
            case 4:
                $channel = 12;//银联后台
                break;
            case 5:
                $channel = 7;//支付宝
                break;
            case 6:
                $channel = 7;//支付宝
                break;
            case 7:
                $channel = 8;//百度钱包
                break;
            case 20:
                $channel = 20;//余额支付
                break;
            case 21:
                $channel = 21;//微博
                break;
            case 23:
                $channel = 10;//微信后台
                break;
            case 24:
                $channel = 7;//支付宝
                break;
        }
        return $channel;

    }

    /**
     * 根据来源ID获取支付渠道参数
     * @param $source_id    渠道ID(提前定义)
     * @return array|\json数据|mixed
     */
    private function getPayChannelData($channelData)
    {
        switch($channelData['payment_source']){
            case 1:
                return $this->wxApp($channelData);
                break;
            case 2:
                return $this->wxH5($channelData);
                break;
            case 3:
                return $this->bfbApp($channelData);
                break;
            case 4:
                return $this->upApp($channelData);
                break;
            case 5:
                return $this->alipayApp($channelData);
                break;
            case 6:
                return $this->alipayWeb($channelData);
                break;
            case 7:
                return $this->zhidahaoH5($channelData);
                break;
            case 20:
                return $this->payHt($channelData);
                break;
            case 21:
                return $this->weiboH5($channelData);
                break;
            case 23:
                return $this->wxNative($channelData);
                break;
            case 24:
                return $this->alipayWap($channelData);
                break;
        }
    }

    /**
     * 获取回调地址
     * @param $type_name 类型
     */
    public function notifyUrl($type_name)
    {
        return "http://".$_SERVER['HTTP_HOST']."/".yii::$app->controller->id."/".$type_name."-notify";
    }

    /**
     * 获取支付subject内容
     * @return string
     */
    public function subject()
    {
        return $this->payment_type == 3 ? 'e家洁会员充值' : 'e家洁在线支付';
    }

    /**
     * 获取支付body内容
     * @return string
     */
    public function body()
    {
        return $this->payment_type == 3 ? 'e家洁会员充值'.$this->payment_money.'元' : 'e家洁在线支付'.$this->payment_money.'元';
    }

    /**
     * 生成商户订单号
     * 年月日+交易类型+随机数+自增ID
     * 01 正常订单 02 退款 03 赔付
     * @return bool|string 订单号
     */
    public function createOutTradeNo($type=1,$order_id=0)
    {
        $id = empty($this->id) ?  $order_id : $this->id;
        if( empty($id) ) return false;
        switch($type)
        {
            case 1 :
                $transType = '01';
                break;
            case 2 :
                $transType = '02';
                break;
            case 3 :
                $transType = '03';
                break;
        }
        //组装支付订单号
        $rand = mt_rand(1000,9999);
        $date = date("ymd",time());
        //生成商户订单号
        $payment_eo_order_id = $date.$transType.$rand.$id;
        //查询当前ID的数据是否存在
        $model = Payment::findOne($id);
        $model->setAttribute('payment_eo_order_id',$payment_eo_order_id);
        $model->doSave();
        return $payment_eo_order_id;
    }

    /**
     * 转换金额
     * @param $money1   实际金额
     * @param $money2   基数
     * @param $method   +,-,*,%
     * @param $num 保留几位小树
     * @return float    实际金额
     */
    public function toMoney($money1, $money2, $method = '*',$num=2)
    {
        $toMoney = '';
        bcscale($num); //保留两位小数
        switch($method)
        {
            case '+' :
                $toMoney = bcadd($money1,$money2);
                break;
            case '-' :
                $toMoney = bcsub($money1,$money2);
                break;
            case '*' :
                $toMoney = bcmul($money1,$money2);
                break;
            case '/' :
                $toMoney = bcdiv($money1,$money2);
                break;
        }
        return $toMoney;
    }

    /**
     * 微信APP(1)
     */
    private function wxApp($data)
    {
        $param = [
            "body"	=> $this->body(),
            "out_trade_no"	=> $this->createOutTradeNo(),
            "payment_money"	=> $this->toMoney($this->payment_money,100,'*',0),
            'time_start' => date("YmdHis"),
            'time_expire' => date("YmdHis", time() + 600000),
            "trade_type" => "WX",
            "subject" => $this->subject(),
            "notify_url" => $this->notifyUrl('wx-app'),
        ];
        $class = new \wxpay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 微信H5(2)
     */
    private function wxH5($data)
    {
        $param = [
            "body"	=> $this->body(),
            "out_trade_no"	=> $this->createOutTradeNo(),
            "payment_money"	=> $this->toMoney($this->payment_money,100,'*',0),
            'time_start' => date("YmdHis"),
            'time_expire' => date("YmdHis", time() + 600000),
            "trade_type" => "JSAPI",
            "subject" => $this->subject(),
            "notify_url" => $this->notifyUrl('wx-h5'),
            'openid' => $data['openid'],//'o7Kvajh91Fmh_KYzhwX0LWZtpMPM',//$data['openid'],
        ];

        $class = new \wxjspay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 百度钱包APP(3)
     */
    private function bfbApp($data)
    {
        $param = [
            'out_trade_no'=>$this->createOutTradeNo(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'payment_money'=>$this->toMoney($this->payment_money,100,'*',0),
            'notify_url'=>$this->notifyUrl('bfb-app'),
        ];

        $class = new \bfbpay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 银联APP(4)
     */
    private function upApp($data)
    {
        $param = [
            'out_trade_no'=>$this->createOutTradeNo(),
            'subject'=>$this->subject(),
            'payment_money'=>$this->toMoney($this->payment_money,100,'*',0),
            'notify_url'=>$this->notifyUrl('up-app'),
        ];
        $class = new \uppay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 支付宝APP(5)
     */
    private function alipayApp($data)
    {
        $param = [
            'out_trade_no'=>$this->createOutTradeNo(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'payment_money'=>$this->payment_money,
            'notify_url'=>$this->notifyUrl('alipay-app'),
        ];
        $class = new \alipay_class();
        $msg = $class->get($param);
        return $msg;
    }


    /**
     * 支付宝WEB(6)
     * 第三方支付思路：
     * 1 客户端请求服务端，带有指定数据
     * 2 服务端将支付所需参数返回给客户端
     * 3 服务端创建支付记录（未支付状态）
     */
    private function alipayWeb($data)
    {
        $param = [
            'out_trade_no'=>$this->createOutTradeNo(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'total_fee'=>$this->payment_money,
            'notify_url'=>$this->notifyUrl('alipay-web'),
            "return_url"	=> $data['return_url'],
            "show_url"	=> $data['show_url'],
        ];
        $class = new \alipay_web_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 直达号支付(7)
     */
    private function zhidahaoH5($data)
    {
        $param = [
            'out_trade_no'=>$this->createOutTradeNo(),
            'goods_name'=>$data['goods_name'],
            'payment_money'=>$this->toMoney($this->payment_money,100,'*',0),
            'detail' => $data['detail'],
            'order_source_url' => $data['order_source_url'],
            'return_url' => $this->notifyUrl('zhidahao-h5'),
            'page_url' => $data['page_url'],
            'customer_name' => $data['customer_name'],
            'customer_mobile' => $data['customer_mobile'],
            'customer_address' => $data['customer_address'],
        ];
        $class = new \zhidahao_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 后台支付/余额支付(20)
     */
    private function payHt($data){}

    /**
     * 新浪微博支付(21)
     */
    private function weiboH5($data){}

    /**
     * 微信native(22)
     * @return mixed
     */
    private function wxNative($data)
    {
        $param = [
            "body"	=> $this->body(),
            "out_trade_no"	=> $this->createOutTradeNo(1,1),
            "payment_money"	=> $this->toMoney($this->payment_money,100,'*',0),
            'time_start' => date("YmdHis"),
            'time_expire' => date("YmdHis", time() + 600000),
            "trade_type" => "NATIVE",
            "subject" => $this->subject(),
            "notify_url" => $this->notifyUrl('wx-native'),
        ];
        $class = new \wxjspay_class();
        $msg = $class->nativeGet($param);
        return $msg;
    }

    /**
     * 支付宝APP(24)
     */
    private function alipayWap($data)
    {
        $param = [
            'out_trade_no'=>$this->createOutTradeNo(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'total_fee'=>$this->payment_money,
            'notify_url'=>$this->notifyUrl('alipay-wap'),
            'return_url'=>empty($data['return_url']) ? '' : $data['return_url'],
            'show_url'=>empty($data['show_url']) ? '' : $data['show_url'],
        ];
        $class = new \alipay_wap_class();
        $msg = $class->get($param);
        return $msg;
    }




    /**
     * 银联支付回调
     * @param $data
     */
    public function upAppNotify($data){
        //POST数据
        if(!empty($data['debug'])){
            $_POST = array (
                "accessType" => "0",
                "bizType" => "000201",
                "certId" => "21267647932558653966460913033289351200",
                "currencyCode" => "156",
                "encoding" => "utf-8",
                "merId" => "898111448161364",
                "orderId" => "151010743932",
                "queryId" => "201510101112461438298",
                "reqReserved" => "透传信息",
                "respCode" => "00",
                "respMsg" => "Success!",
                "settleAmt" => "1",
                "settleCurrencyCode" => "156",
                "settleDate" => "1010",
                "signMethod" => "01",
                "traceNo" => "143829",
                "traceTime" => "1010111246",
                "txnAmt" => "1",
                "txnSubType" => "01",
                "txnTime" => "20151010111246",
                "txnType" => "01",
                "version" => "5.0.0",
                "signature" => "GnmVKKUPgdLc11K8zrwL5w5cTx1bieDdTniC2Psh7WEuk4y+53l8OzvE41KsJNyxBuBWAPBgypK+8jNJmGUU2x+tMU5Z0liIKVD5HWhboHxlwZvh0vMGfB8vlmIcbYipxUuWz3Jin11I6O8W6mvTAb76wJXrcbqZD1PKtVP7/5ldxpYsRh/MmEfeDFCcxqMk0uS/ON7XagGKkYSOxCcDMmQ4xRhNzLOthO8vkK6vPDWuowNjFdQXV8A2K9MxVqJNrR5QgR52Hm0dy9z5o09YhjDhMgwlyqRAgaBRbVDNt7qJXFyp3lcxwU9sJBkpOCYV6Cwi/03sWJA+W87U6+gN9Q=="
            );
            $post = $_POST;
        }else{
            $post = yii::$app->request->post();
        }

        //记录日志
        $dataLog = array(
            'payment_log_price' => $this->toMoney($post['settleAmt'],100,'/'),   //支付金额
            'payment_log_shop_name' => $post['reqReserved'],   //商品名称
            'payment_log_eo_order_id' => $post['orderId'],   //订单ID
            'payment_log_transaction_id' => $post['queryId'],   //交易流水号
            'payment_log_status_bool' => $post['respMsg'],   //支付状态
            'payment_log_status' => $post['respMsg'],   //支付状态
            'pay_channel_id' => 12,  //支付渠道ID
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );

        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $paymentId = $this->getPaymentId($post['orderId']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //验证签名
        $class = new \uppay_class();
        $sign = $class->callback();

        //验证支付结果
        if( !empty($model) && !empty($sign) )
        {

            $model->id = $paymentId; //ID
            $model->payment_actual_money = $model->toMoney($post['settleAmt'],100,'/');
            $model->payment_transaction_id = $post['queryId'];
            $model->payment_eo_order_id = $post['orderId'];

            //异常状态
            if( $model->payment_actual_money != $model->payment_money)
            {
                $model->payment_status = 2; //异常状态
            }else{
                $model->payment_status = 1; //支付状态
            }
            $model->payment_verify = $model->sign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                //保存数据
                $model->doSave();
                //保存交易记录
                $this->paymentTransRecord($model->getAttributes());
                //提交数据
                $transaction->commit();
                //TODO::发送短信

                //通知回调成功
                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * 支付宝APP回调
     * @param $data
     */
    public function alipayAppNotify($data){
        //POST数据
        if(!empty($data['debug'])){
            $_POST = array (
                "discount"=> "0.00",
                "payment_type"=> "1",
                "subject"=> "e家洁会员充值",
                "trade_no"=> "2015092510165",
                "buyer_email"=> "lsqpy@163.com",
                "gmt_create"=> "2015-09-25 21:13:20",
                "notify_type"=> "trade_status_sync",
                "quantity"=> "1",
                "out_trade_no"=> "150925846765",
                "seller_id"=> "2088801136967007",
                "notify_time"=> "2015-09-25 21:13:21",
                "body"=> "e家洁会员充值0.01元",
                "trade_status"=> "TRADE_FINISHED",
                "is_total_fee_adjust"=> "N",
                "total_fee"=> "0.01",
                "gmt_payment"=> "2015-09-25 21:13:21",
                "seller_email"=> "47632990@qq.com",
                "gmt_close"=> "2015-09-25 21:13:21",
                "price"=> "0.01",
                "buyer_id"=> "2088002074138164",
                "notify_id"=> "6260ae5cc41e6aa3a42824ec032071df2w",
                "use_coupon"=> "N",
                "sign_type"=> "RSA",
                "sign"=> "T4Bkh9KljoFOTIossu5QtYPRUwj/7by/YLXNQ7efaxe0AwYDjFDFWTFts4h8yq2ceCH8weqYVBklj2btkF2/hKPuUifuJNB6lk8EtHckmJg0MzhGIBAvpteUAo+5Gs+wlI5eS5zmryBskuHOXSM7svb9wNCcL9pHAv8CM06Au+A="
            );
            $post = $_POST;
        }else{
            $post = yii::$app->request->post();
        }

        //记录日志
        $dataLog = array(
            'payment_log_price' => $post['total_fee'],   //支付金额
            'payment_log_shop_name' => $post['subject'],   //商品名称
            'payment_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'payment_log_transaction_id' => $post['buyer_id'],   //交易流水号
            'payment_log_status_bool' => $post['trade_status'],   //支付状态
            'payment_log_status' => $post['trade_status'],   //支付状态
            'pay_channel_id' => 6,  //支付渠道ID
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $paymentId = $this->getPaymentId($post['out_trade_no']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //验证签名
            $class = new \alipay_class;
            $verify_result = $class->callback();

            if(!empty($_GET['debug']))
            {
                $verify_result = true;
            }

            //签名验证成功
            if($verify_result)
            {

                $model->id = $paymentId; //ID
                $model->payment_actual_money = $post['total_fee'];
                $model->payment_transaction_id = $post['trade_no'];
                $model->payment_eo_order_id = $post['out_trade_no'];

                //异常状态
                if( $model->payment_actual_money != $model->payment_money)
                {
                    $model->payment_status = 2; //异常状态
                }else{
                    $model->payment_status = 1; //支付状态
                }
                $model->payment_verify = $model->sign();
                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    //保存数据
                    $model->doSave();
                    //保存交易记录
                    $this->paymentTransRecord($model->getAttributes());
                    //提交数据
                    $transaction->commit();
                    //TODO::发送短信

                    //通知回调成功
                    $class->notify();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * 支付宝APP回调
     * @param $data
     */
    public function alipayWapNotify($data)
    {
        //POST数据
        if(!empty($data['debug'])){

            $_POST = [
                "payment_type"=> "1",
                "subject"=> "e家洁在线支付",
                "trade_no"=> "2015110457346343",
                "buyer_email"=> "weibeinan2008@163.com",
                "gmt_create"=> "2015-11-04 18:15:36",
                "notify_type"=> "trade_status_sync",
                "quantity"=> "1",
                "out_trade_no"=> "1511040155118",
                "seller_id"=> "2088801136967007",
                "notify_time"=> "2015-11-04 18:39:23",
                "body"=> "e家洁在线支付0.02元",
                "trade_status"=> "TRADE_SUCCESS",
                "is_total_fee_adjust"=> "N",
                "total_fee"=> "0.02",
                "gmt_payment"=> "2015-11-04 18:15:36",
                "seller_email"=> "47632990@qq.com",
                "price"=> "0.02",
                "buyer_id"=> "2088412778636439",
                "notify_id"=> "e57d71ea1dc40bbf294a7df8d47171834e",
                "use_coupon"=> "N",
                "sign_type"=> "RSA",
                "sign"=> "fE6og70Ie7xUqwiFoJFImHu8n8Hxv7x1sDcWOo132jN23TUH4BhNhX14OvYKk0VJ71GpmFuPS7jhT3SCrtaK24l5OHxueDzJUfcVkDOdA0UOi5A1W8P3Mv8bAIKEP6kGhjWB8ittnGSLmkdDAZMIQmaUz0eoIR4NL8uhU3qv9Bk="
            ];

            $_POST = [
                "payment_type"=> "1",
                "subject"=> "e家洁在线支付",
                "trade_no"=> "2015110900001000440071876428",
                "buyer_email"=> "xihuange@126.com",
                "gmt_create"=> "2015-11-09 00:59:27",
                "notify_type"=> "trade_status_sync",
                "quantity"=> "1",
                "out_trade_no"=> "1511090135473",
                "seller_id"=> "2088801136967007",
                "notify_time"=> "2015-11-09 01:13:02",
                "body"=> "e家洁在线支付0.02元",
                "trade_status"=> "TRADE_SUCCESS",
                "is_total_fee_adjust"=> "N",
                "total_fee"=> "0.02",
                "gmt_payment"=> "2015-11-09 00:59:28",
                "seller_email"=> "47632990@qq.com",
                "price"=> "0.02",
                "buyer_id"=> "2088102035089446",
                "notify_id"=> "aade9f6f4bddbbfde2e9243e88a3101c4g",
                "use_coupon"=> "N",
                "sign_type"=> "RSA",
                "sign"=> "iAqB1XSFY7mlpuK9zjwW3zXSro+2HWKWG2h638ukP5lvAMa8+qZ/Fqqe6PQ7q0ktX7BPSgvGpaBAwhE/kK25hcaMniZRPyg/mBvmzuS+W2LXd8Zgj47R9hzWNKqX3RBaXtdnwbH06UXiG8wcUoxJSFPRCiLL2AozDw71Ya+aiZU="
            ];

            $post = $_POST;
        }else{
            $post = yii::$app->request->post();
        }

        //记录日志
        $dataLog = array(
            'payment_log_price' => $post['total_fee'],   //支付金额
            'payment_log_shop_name' => $post['subject'],   //商品名称
            'payment_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'payment_log_transaction_id' => $post['trade_no'],   //交易流水号
            'payment_log_status_bool' => $post['trade_status'],   //支付状态
            'payment_log_status' => $post['trade_status'],   //支付状态
            'pay_channel_id' => 7,  //支付渠道,支付宝
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $paymentId = $this->getPaymentId($post['out_trade_no']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //验证签名
            $class = new \alipay_wap_class();
            $verify_result = $class->callback();
            if(!empty($_GET['debug']))
            {
                $verify_result = true;
            }
            //签名验证成功
            if($verify_result)
            {


                $model->id = $paymentId; //ID
                $model->payment_actual_money = $post['total_fee'];
                $model->payment_transaction_id = $post['trade_no'];
                $model->payment_eo_order_id = $post['out_trade_no'];

                //异常状态
                if( $model->payment_actual_money != $model->payment_money)
                {
                    $model->payment_status = 2; //异常状态
                }else{
                    $model->payment_status = 1; //支付状态
                }
                $model->payment_verify = $model->sign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    //保存数据
                    $model->doSave();
                    //保存交易记录
                    $this->paymentTransRecord($model->getAttributes());
                    //提交数据
                    $transaction->commit();
                    //TODO::发送短信

                    //通知回调成功
                    echo $class->notify();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }

            }
        }
    }

    /**
     * 微信APP回调
     * @param $data
     */
    public function wxAppNotify($data){
        //{"r":"\/pay\/wx-app-notify","bank_type":"0","discount":"0","fee_type":"1","input_charset":"UTF-8","notify_id":"envUQL970OKMjmAE66VO3Jsn9_10yHjOEzrSRxvgoyTkhAiEPFWa9f-uJnVIq08EhcVXyTfMeLr9vym58gZhO_vUqbjYrDll","out_trade_no":"15110501657379","partner":"1217983401","product_fee":"2","sign":"E9441ED08D39D9FCE40C1372A5F4EDE0","sign_type":"MD5","time_end":"20151105001614","total_fee":"2","trade_mode":"1","trade_state":"0","transaction_id":"1217983401391511058440210952","transport_fee":"0"}

        $class = new \wxpay_class();
        if(!empty($data['debug'])){
            $GLOBALS['HTTP_RAW_POST_DATA'] = "<xml>
                <appid><![CDATA[wx7558e67c2d61eb8f]]></appid>
                <attach><![CDATA[e家洁在线支付]]></attach>
                <bank_type><![CDATA[CFT]]></bank_type>
                <cash_fee><![CDATA[1]]></cash_fee>
                <fee_type><![CDATA[CNY]]></fee_type>
                <is_subscribe><![CDATA[Y]]></is_subscribe>
                <mch_id><![CDATA[10037310]]></mch_id>
                <nonce_str><![CDATA[aoydf0e8u58c2scu2o441n1i5yxtxghr]]></nonce_str>
                <openid><![CDATA[o7Kvajh91Fmh_KYzhwX0LWZtpMPM]]></openid>
                <out_trade_no><![CDATA[15101922921]]></out_trade_no>
                <result_code><![CDATA[SUCCESS]]></result_code>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <sign><![CDATA[3E437AF36D969693DD705034A8FFD5F9]]></sign>
                <time_end><![CDATA[20151019102921]]></time_end>
                <total_fee>1</total_fee>
                <trade_type><![CDATA[JSAPI]]></trade_type>
                <transaction_id><![CDATA[1004390062201510191251335932]]></transaction_id>
                </xml>";
            $post = $_POST = [
                "r" => "/pay/wx-app-notify",
                "bank_type" => "0",
                "discount" => "0",
                "fee_type" => "1",
                "input_charset" => "UTF-8",
                "notify_id" => "envUQL970OKMjmAE66VO3Jsn9_10yHjOEzrSRxvgoyTkhAiEPFWa9f-uJnVIq08EhcVXyTfMeLr9vym58gZhO_vUqbjYrDll",
                "out_trade_no" => "15110501657379",
                "partner" => "1217983401",
                "product_fee" => "2",
                "sign" => "E9441ED08D39D9FCE40C1372A5F4EDE0",
                "sign_type" => "MD5",
                "time_end" => "20151105001614",
                "total_fee" => "2",
                "trade_mode" => "1",
                "trade_state" => "0",
                "transaction_id" => "1217983401391511058440210952",
                "transport_fee" => "0"
            ];
            $status = 'error';
        }else{
            //调用微信验证
            $post = $data;
        }

        //记录日志
        $dataLog = array(
            'payment_log_price' => $this->toMoney($post['total_fee'],100,'/'),   //支付金额
            'payment_log_shop_name' => '微信支付',   //商品名称
            'payment_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'payment_log_transaction_id' => $post['transaction_id'],   //交易流水号
            'payment_log_status_bool' => $post['trade_state'],   //支付状态
            'payment_log_status' => $post['trade_state'],   //支付状态
            'pay_channel_id' => 13,  //财付通
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );

        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $paymentId = $this->getPaymentId($post['out_trade_no']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //if(!empty($data['debug'])){
        //    $status = true;
        //}else{
            $status = $class->callback();
        //}

        //验证支付结果
        if(!empty($model) && !empty($status)){


            $model->id = $paymentId; //ID
            $model->payment_actual_money = $model->toMoney($post['total_fee'],100,'/');
            $model->payment_transaction_id = $post['transaction_id'];
            $model->payment_eo_order_id = $post['out_trade_no'];

            //异常状态
            if( $model->payment_actual_money != $model->payment_money)
            {
                $model->payment_status = 2; //异常状态
            }else{
                $model->payment_status = 1; //支付状态
            }
            $model->payment_verify = $model->sign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                //保存数据
                $model->doSave();
                //保存交易记录
                $this->paymentTransRecord($model->getAttributes());
                //提交数据
                $transaction->commit();
                //TODO::发送短信

                //通知回调成功
                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * 百付宝APP回调
     * @param $data
     */
    public function bfbAppNotify($data){
        //POST数据
        if(!empty($data['debug'])){
            $_GET = array (
                'bank_no' => '',
                'bfb_order_create_time' => '20150714115504',
                'bfb_order_no' => '2015071415006100041110555687771',
                'buyer_sp_username' => '',
                'currency' => '1',
                'extra' => '',
                'fee_amount' => '0',
                'input_charset' => '1',
                'order_no' => '150927830311',
                'pay_result' => '1',
                'pay_time' => '20150714115503',
                'pay_type' => '2',
                'sign_method' => '1',
                'sp_no' => '1500610004',
                'total_amount' => '1',
                'transport_amount' => '0',
                'unit_amount' => '1',
                'unit_count' => '1',
                'version' => '2',
                'sign' => 'eef8e524ef6b6dde1699b04421fc9bc5',
            ) ;
            $post = $_GET;
        }else{
            $post = $data;
        }

        //记录日志
        $dataLog = array(
            'payment_log_price' => $this->toMoney($post['total_amount'],100,'/'),   //支付金额
            'payment_log_shop_name' => '百付宝',   //商品名称
            'payment_log_eo_order_id' => $post['order_no'],   //订单ID
            'payment_log_transaction_id' => $post['bfb_order_no'],   //交易流水号
            'payment_log_status_bool' => $post['pay_result'],   //支付状态
            'payment_log_status' => $post['pay_result'],   //支付状态
            'pay_channel_id' => 8,  //百度钱包
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $paymentId = $this->getPaymentId($post['order_no']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //验证签名
        $class = new \bfbpay_class();
        if(!empty($_GET['debug'])){
            $sign = $class->callback();
        }else{
            $sign = true;
        }

        //验证支付结果
        if( !empty($model) && !empty($sign) ){

            $model->id = $paymentId; //ID
            $model->payment_actual_money = $model->toMoney($post['total_amount'],100,'/');
            $model->payment_transaction_id = $post['bfb_order_no'];
            $model->payment_eo_order_id = $post['order_no'];

            //异常状态
            if( $model->payment_actual_money != $model->payment_money)
            {
                $model->payment_status = 2; //异常状态
            }else{
                $model->payment_status = 1; //支付状态
            }
            $model->payment_verify = $model->sign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                //保存数据
                $model->doSave();
                //保存交易记录
                $this->paymentTransRecord($model->getAttributes());
                //提交数据
                $transaction->commit();
                //TODO::发送短信

                //通知回调成功
                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * 微信H5回调
     * @param $data
     */
    public function wxH5Notify($data){
        if(!empty($data['debug'])){
            $GLOBALS['HTTP_RAW_POST_DATA'] = "<xml>
                <appid><![CDATA[wx7558e67c2d61eb8f]]></appid>
                <attach><![CDATA[e家洁在线支付]]></attach>
                <bank_type><![CDATA[CFT]]></bank_type>
                <cash_fee><![CDATA[1]]></cash_fee>
                <fee_type><![CDATA[CNY]]></fee_type>
                <is_subscribe><![CDATA[Y]]></is_subscribe>
                <mch_id><![CDATA[10037310]]></mch_id>
                <nonce_str><![CDATA[aoydf0e8u58c2scu2o441n1i5yxtxghr]]></nonce_str>
                <openid><![CDATA[o7Kvajh91Fmh_KYzhwX0LWZtpMPM]]></openid>
                <out_trade_no><![CDATA[15101922921]]></out_trade_no>
                <result_code><![CDATA[SUCCESS]]></result_code>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <sign><![CDATA[3E437AF36D969693DD705034A8FFD5F9]]></sign>
                <time_end><![CDATA[20151019102921]]></time_end>
                <total_fee>1</total_fee>
                <trade_type><![CDATA[JSAPI]]></trade_type>
                <transaction_id><![CDATA[1004390062201510191251335932]]></transaction_id>
                </xml>";
            $post = array (
                "appid" => "wx7558e67c2d61eb8f",
                "attach" => "e家洁在线支付",
                "bank_type" => "CFT",
                "cash_fee" => "1",
                "fee_type" => "CNY",
                "is_subscribe" => "Y",
                "mch_id" => "10037310",
                "nonce_str" => "aoydf0e8u58c2scu2o441n1i5yxtxghr",
                "openid" => "o7Kvajh91Fmh_KYzhwX0LWZtpMPM",
                "out_trade_no" => "15101922921",
                "result_code" => "SUCCESS",
                "return_code" => "SUCCESS",
                "sign" => "3E437AF36D969693DD705034A8FFD5F9",
                "time_end" => "20151019102921",
                "total_fee" => "1",
                "trade_type" => "JSAPI",
                "transaction_id" => "1004390062201510191251335932"
            );
        }else{
            $post = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        }

        //实例化模型
        $model = new Payment();

        //记录日志
        $dataLog = array(
            'payment_log_price' => $model->toMoney($post['total_fee'],100,'/'),   //支付金额
            'payment_log_shop_name' => $post['attach'],   //商品名称
            'payment_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'payment_log_transaction_id' => $post['transaction_id'],   //交易流水号
            'payment_log_status_bool' => $post['return_code'],   //支付状态
            'payment_log_status' => $post['return_code'],   //支付状态
            'pay_channel_id' => 10,  //微信后台
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $paymentId = $model->getPaymentId($post['out_trade_no']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //验证签名
            //调用微信数据
            $class = new \wxjspay_class();
            $class->callback();
            $status = $class->notify();

            //签名验证成功
            if($status == 'SUCCESS')
            {

                $model->id = $paymentId; //ID
                $model->payment_actual_money = $model->toMoney($post['total_fee'],100,'/');
                $model->payment_transaction_id = $post['transaction_id'];
                $model->payment_eo_order_id = $post['out_trade_no'];
                //异常状态
                if( $model->payment_actual_money != $model->payment_money)
                {
                    $model->payment_status = 2; //异常状态
                }else{
                    $model->payment_status = 1; //支付状态
                }
                $model->payment_verify = $model->sign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    //保存数据
                    $model->doSave();
                    //保存交易记录
                    $this->paymentTransRecord($model->getAttributes());
                    //提交数据
                    $transaction->commit();
                    //TODO::发送短信

                    //通知回调成功
                    $class->notify();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * 百度直达号回调
     * @param $data
     */
    public function zhidahaoH5Notify($data)
    {
        if(!empty($data['debug'])){
            $post = [
                "order_no" => "15101980901",    //第三方的订单号
                "order_id" => "17600075",       //直达号中心订单号
                "sp_no" => "1049",              //商户号
                "pay_time" => "1445252232",     //支付时间
                "pay_result" => "1",            //1 支付成功,2 等待支付, 3 退款成功
                "paid_amount" => "1",           //成功支付现金金额(单位分)
                "coupons" => "0",               //优惠券使用金额(单位分)
                "promotion" => "0",             //立减金额(单位分)
                "sign" => "192efb9b70c26c4135d7550628f3e7cd"    //签名
            ];
            $_REQUEST = $post;
        }else{
            $post = $data;
        }

        //记录日志
        $dataLog = array(
            'payment_log_price' => $post['paid_amount'],   //支付金额
            'payment_log_shop_name' => '百度直达号支付',   //商品名称
            'payment_log_eo_order_id' => $post['order_no'],   //订单ID
            'payment_log_transaction_id' => $post['order_id'],   //交易流水号
            'payment_log_status_bool' => $post['pay_result'],   //支付状态
            'payment_log_status' => $post['pay_result'],   //支付状态
            'pay_channel_id' => 8,  //百度钱包
            'payment_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[new PaymentLog(),'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //实例化模型
        $model = new Payment();

        //获取交易ID
        $paymentId = $model->getPaymentId($post['order_no']);

        //查询支付记录
        $model = Payment::find()->where(['id'=>$paymentId,'payment_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //调用直达号数据
            $class = new \zhidahao_class();
            $status = $class->callback();

            //签名验证成功
            if( !empty($status) )
            {

                $model->id = $paymentId; //ID
                $model->payment_actual_money = $model->toMoney($post['paid_amount'],100,'/');
                $model->payment_transaction_id = $post['order_id'];
                $model->payment_eo_order_id = $post['order_no'];

                //异常状态
                if( $model->payment_actual_money != $model->payment_money)
                {
                    $model->payment_status = 2; //异常状态
                }else{
                    $model->payment_status = 1; //支付状态
                }
                $model->payment_verify = $model->sign();
                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    //保存数据
                    $model->doSave();
                    //保存交易记录
                    $this->paymentTransRecord($model->getAttributes());
                    //提交数据
                    $transaction->commit();
                    //TODO::发送短信

                    //通知回调成功
                    echo $class->notify();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * 获取记录ID
     * @param $out_trade_no 交易ID
     */
    public function getPaymentId($out_trade_no)
    {
        return substr($out_trade_no,12);
    }

    /**
     * 签名
     */
    public function sign()
    {
        $data = $this->getAttributes();
        ksort($data);
        //加密字符串
        $str='1jiajie.com';
        //排除的字段
        $notArray = ['id','payment_verify','created_at','updated_at'];
        //加密签名
        foreach( $data as $name=>$val )
        {
            $value = is_numeric($val) ? (int)$val : $val;
            if( $name == 'payment_money' || $name == 'payment_actual_money' )
            {
                $str .= number_format($val,2);
            }
            else if( !empty($value) && !in_array($name,$notArray))
            {
                if(is_numeric($value) && $value < 1) continue;
                $str .= $value;
            }
        }
        //1jiajie.com110.007支付宝1jjtb1511040155118140.0024220151104573463431
        //1jiajie.com110.027支付宝1jjtb1511040155118140.0024220151104573463431
        //return $str;
        return md5(md5($str).'1jiajie.com');
    }

    /**
     * 支付/充值,交易记录分配入口
     * @param $attribute 支付或订单详细数据
     */
    private function paymentTransRecord($attribute)
    {
        switch($attribute['payment_type'])
        {
            case 1:
                //支付普通订单交易记录
                PaymentCustomerTransRecord::analysisRecord($attribute['order_id'],$attribute['payment_channel_id'],'order_pay',1,$attribute);
                //验证支付金额是否一致
                if( $attribute['payment_money'] === $attribute['payment_actual_money'] )
                {
                    Order::isPaymentOnline($attribute['order_id'],$attribute['payment_channel_id'],$attribute['payment_channel_name'],$attribute['payment_transaction_id']);
                }
                else
                {
                    //金额有误,执行取消订单,调用退款,
                    Order::cancelByOrderId($attribute['order_id'], Order::ADMIN_SYSTEM,OrderOtherDict::NAME_CANCEL_ORDER_CUSTOMER_PAY_FAILURE,'异常支付');
                }
                break;
            case 2:
                //支付周期订单交易记录
                PaymentCustomerTransRecord::analysisRecord($attribute['order_id'],$attribute['payment_channel_id'],'order_pay',2,$attribute);
                //验证支付金额是否一致
                if( $attribute['payment_money'] === $attribute['payment_actual_money'] )
                {
                    Order::isBatchPaymentOnline($attribute['order_id'],$attribute['payment_channel_id'],$attribute['payment_channel_name'],$attribute['payment_transaction_id']);
                }
                else
                {
                    //金额有误,执行取消订单,调用退款
                    Order::cancelByOrderId($attribute['order_id'], Order::ADMIN_SYSTEM,OrderOtherDict::NAME_CANCEL_ORDER_CUSTOMER_PAY_FAILURE,'异常支付');
                }
                break;
            case 3:
                //支付充值交易记录
                PaymentCustomerTransRecord::analysisRecord($attribute['order_id'],$attribute['payment_channel_id'],'payment',2,$attribute);
                break;
            case 4:
                //退款
                PaymentCustomerTransRecord::refundRecord($attribute['order_id'], 'order_refund',1,$attribute['payment_data']);
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
    public static function orderRefund($order_id, $customer_id)
    {
        //获取订单信息
        $orderInfo = OrderSearch::getOrderInfo($order_id);
        $orderInfo = current($orderInfo);
        //执行自有退款
        if( empty($orderInfo['order_pay_money']) && $orderInfo['order_pay_money'] < 0 )
        {

            if( !empty($orderInfo['order_use_acc_balance']) && $orderInfo['order_use_acc_balance'] > 0 )
            {
                //余额支付退款
                //Customer::incBalance($customer_id,$orderInfo['order_use_acc_balance']);
                self::paymentTransRecord(['order_id'=>$order_id,'payment_type'=>4]);
            }
            elseif( !empty($orderInfo['card_id']) && !empty($orderInfo['order_use_card_money']) && $orderInfo['order_use_card_money'] > 0 )
            {
                //服务卡支付退款
                //TODO::调用服务卡退款
                //self::paymentTransRecord(['order_id'=>$order_id]);
            }
        }
        elseif( !empty($orderInfo['order_pay_money']) && $orderInfo['order_pay_money'] > 0 )
        {
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                //查询线上是否存在退款记录
                $refundInfo = Payment::find()->where(['payment_mode'=>3,'payment_status'=>1,'order_id'=>$order_id,'customer_id'=>$customer_id])->asArray()->one();
                if( !empty($refundInfo) )
                {
                    return ['status'=>0,'info'=>'已经退款过','data'=>''];
                }

                //查询线上是否存在支付记录
                $paymentInfo = Payment::find()->where(['payment_mode'=>1,'payment_status'=>1,'order_id'=>$order_id,'customer_id'=>$customer_id])->asArray()->one();
                if( empty($paymentInfo) )
                {
                    return ['status'=>0,'info'=>'未找到支付数据','data'=>''];
                }

                //线上退款,创建一条线上退款记录,状态未确认
                $model = new Payment();
                $model->scenario = 'refund';
                $model->setAttributes([
                    'customer_id' => $customer_id,
                    'order_id' => $order_id,
                    'payment_money' => $orderInfo['order_pay_money'],   //订单支付金额
                    'payment_actual_money' => $paymentInfo['payment_actual_money'],     //实际支付金额
                    'payment_source' => $paymentInfo['payment_source'],
                    'payment_channel_id' => $paymentInfo['payment_channel_id'],
                    'payment_channel_name' => $paymentInfo['payment_channel_name'],
                    'payment_mode' => 3,    //退款
                    'payment_status' => 1,  //退款成功状态
                    'payment_transaction_id'=>0,
                    'payment_memo' => '',
                    'payment_type' => 4,    //退款类型
                    'admin_id' => Yii::$app->user->id,
                    'payment_admin_name' => Yii::$app->user->identity->username,
                    'payment_eo_order_id'=>$model->createOutTradeNo(2,$order_id),
                    'payment_verify' => $model->sign(),
                ]);

                //调用退款
                if($model->doSave() && $model->paymentTransRecord(['order_id'=>$order_id,'payment_type'=>4,'payment_data'=>$paymentInfo]))
                {
                    $transaction->commit();
                    return ['status'=>1,'info'=>'已确认退记录完成','data'=>''];
                }else{
                    $transaction->rollBack();
                    return ['status'=>0,'info'=>'数据异常状态','data'=>''];
                }

            }catch(Exception $e){
                $transaction->rollBack();
                return ['status'=>0,'info'=>'数据异常状态','data'=>''];
            }
        }
        return ['status'=>0,'info'=>'未找到订单数据','data'=>''];
    }

    /**
     * 支付成功发送短信
     * @param $customer_id 用户ID
     */
    public function smsSend($customer_id, $msg )
    {
        $customerPhone = Customer::getCustomerPhone($customer_id);
        $msg = !empty($msg) ? $msg : '支付成功!!!';
        Yii::$app->sms->send($customerPhone,$msg);
    }

}
