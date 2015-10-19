<?php

namespace boss\controllers;
use Yii;
use core\models\Customer;
use core\models\CustomerTransRecord\CustomerTransRecord;
use common\models\GeneralPay;
use common\models\GeneralPayLog;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GeneralPayController implements the CRUD actions for GeneralPay model.
 */
class GeneralPayController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 发送给支付接口的数据
     * @param integer $pay_money 支付金额
     * @param integer $customer_id 消费者ID
     * @param integer $channel_id 渠道ID
     * @param integer $order_id 订单ID
     * @param integer $partner 第三方合作号
     */
    public function actionGetPay()
    {
        //接收数据
        $request = yii::$app->request;
        $data = $request->get();

        //实例化模型
        $model = new GeneralPay();

        //查询订单是否已经支付过
        if( !empty($data['order_id']) ){
            $order = GeneralPay::find()->where(['order_id'=>$data['order_id'],'general_pay_status'=>1])->one();
            if(!empty($order)){
                exit("订单已经支付过");
            }
        }

        //在线支付（online_pay），在线充值（pay）
        if(empty($data['order_id'])){
            if($data['general_pay_source'] == '2'){
                //微信H5
                $scenario = 'wx_h5_pay';
                $data['openid'] = $data['params']['openid'];    //微信openid
            }elseif($data['general_pay_source'] == '7'){
                //百度直达号
                $scenario = 'zhidahao_h5_pay';
                $data['customer_name'] = $data['params']['customer_name'];  //商品名称
                $data['customer_mobile'] = $data['params']['customer_mobile'];  //用户电话
                $data['customer_address'] = $data['params']['customer_address'];  //用户地址
                $data['order_source_url'] = $data['params']['order_source_url'];  //订单详情地址
                $data['page_url'] = $data['params']['page_url'];  //订单跳转地址
                $data['detail'] = $data['params']['detail'];  //订单详情
            }else{
                $scenario = 'pay';
            }
            //交易方式
            $data['general_pay_mode'] = 1;//充值
        }else{
            if($data['general_pay_source'] == '2'){
                //微信H5
                $scenario = 'wx_h5_online_pay';
            }elseif($data['general_pay_source'] == '7'){
                //百度直达号
                $scenario = 'zhidahao_h5_online_pay';
                $data['customer_name'] = $data['params']['customer_name'];  //商品名称
                $data['customer_mobile'] = $data['params']['customer_mobile'];  //用户电话
                $data['customer_address'] = $data['params']['customer_address'];  //用户地址
                $data['order_source_url'] = $data['params']['order_source_url'];  //订单详情地址
                $data['page_url'] = $data['params']['page_url'];  //订单跳转地址
                $data['detail'] = $data['params']['detail'];  //订单详情
            }else{
                $scenario = 'online_pay';
            }
            //交易方式
            $data['general_pay_mode'] = 3;//在线支付
        }

        //支付来源
        $data['general_pay_source_name'] = $model->source($data['general_pay_source']);

        //使用场景
        $model->scenario = $scenario;
        $model->attributes = $data;

        //验证数据
        if( $model->validate() && $model->save() ){

            //返回组装数据
            $model->call_pay();

        }else{
            echo json_encode(['code'=>'-1' , 'msg'=>['alertMsg'=>$model->errors]]);
        }

    }

    /**
     * 接收第三方支付数据
     * @return mixed
     */
    public function actionReceive()
    {

    }

    /**
     * 查询支付第三方第三方支付数据
     * @return mixed
     */
    public function actionSearch()
    {

    }


    /**
     * 支付宝APP回调
     * wx-js-notify
     */
    public function actionWxH5Notify()
    {
        if(!empty($_GET['debug'])){
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
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $post['total_fee'],   //支付金额
            'general_pay_log_shop_name' => $post['attach'],   //商品名称
            'general_pay_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['transaction_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['return_code']),   //支付状态
            'general_pay_log_status' => $post['return_code'],   //支付状态
            'pay_channel_id' => 10,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

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
                $model->id = $GeneralPayId; //ID
                $model->general_pay_status = 1; //支付状态
                $model->general_pay_actual_money = $post['total_fee'];
                $model->general_pay_transaction_id = $post['transaction_id'];
                $model->general_pay_is_coupon = 1;
                $model->general_pay_eo_order_id = $post['out_trade_no'];
                $model->general_pay_verify = $model->makeSign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    $model->save(false);
                    $attribute = $model->getAttributes();
                    if(!empty($model->order_id)){
                        //支付订单
                        GeneralPay::orderPay($attribute);
                    }else{
                        //充值支付
                        GeneralPay::pay($attribute);
                    }

                    $transaction->commit();

                    //发送短信事件
                    $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                    $this->trigger('paySms');
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
     */
    public function actionAlipayAppNotify()
    {
        $request = yii::$app->request;

        //POST数据
        if(!empty($_GET['debug'])){
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
            $post = $request->post();
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $post['total_fee'],   //支付金额
            'general_pay_log_shop_name' => $post['subject'],   //商品名称
            'general_pay_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['buyer_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['trade_status']),   //支付状态
            'general_pay_log_status' => $post['trade_status'],   //支付状态
            'pay_channel_id' => 6,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //验证签名
            $alipay = new \alipay_class;
            $verify_result = $alipay->callback();

            if(!empty($_GET['debug']))
            {
                $verify_result = true;
            }

            //签名验证成功
            if($verify_result)
            {
                $model->id = $GeneralPayId; //ID
                $model->general_pay_status = 1; //支付状态
                $model->general_pay_actual_money = $post['total_fee'];
                $model->general_pay_transaction_id = $post['trade_no'];
                $model->general_pay_is_coupon = 1;
                $model->general_pay_eo_order_id = $post['out_trade_no'];
                $model->general_pay_verify = $model->makeSign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    $model->save(false);
                    $attribute = $model->getAttributes();
                    if(!empty($model->order_id)){
                        //支付订单
                        GeneralPay::orderPay($attribute);
                    }else{
                        //充值支付
                        GeneralPay::pay($attribute);
                    }

                    $transaction->commit();

                    //发送短信事件
                    $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                    $this->trigger('paySms');
                    echo $this->notify();
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
     * 金额单位为【分】
     */
    public function actionWxAppNotify()
    {
        //file_put_contents('/tmp/pay/test.txt',json_encode($_POST));
        //file_put_contents('/tmp/pay/test1.txt',json_encode($_GET));
        //http://dev.boss.1jiajie.com/general-pay/wx-app-notify?r=/general-pay/wx-app-notify&
        //bank_type=0&discount=0&fee_type=1&input_charset=UTF-8&
        //notify_id=envUQL970OImimNqSbr02zP5_Zq5nrw-luZ8ADWHtVsc_30p2GXJ51YmMHoAqccbbeZBlGI2Ken5nHuMzIRqYgLX_4kw4QXg&out_trade_no=15101258091&
        //partner=1217983401&product_fee=1&sign=A9A2D759AC57CA47ACC80436C4C6A876&sign_type=MD5&time_end=20151012165432&total_fee=1&
        //trade_mode=1&trade_state=0&transaction_id=1217983401381510128537567810&transport_fee=0

        $request = yii::$app->request;

        $class = new \wxpay_class();
        if(!empty($_GET['debug'])){
            $post = $_POST = [
                "r" => "/general-pay/wx-app-notify",
                "bank_type" => "0",
                "discount" => "0",
                "fee_type" => "1",
                "input_charset" => "UTF-8",
                "notify_id" => "envUQL970OImimNqSbr02zP5_Zq5nrw-luZ8ADWHtVsc_30p2GXJ51YmMHoAqccbbeZBlGI2Ken5nHuMzIRqYgLX_4kw4QXg",
                "out_trade_no" => "15101258091",
                "partner" => "1217983401",
                "product_fee" => "1",
                "sign" => "A9A2D759AC57CA47ACC80436C4C6A876",
                "sign_type" => "MD5",
                "time_end" => "20151012165432",
                "total_fee" => "1",
                "trade_mode" => "1",
                "trade_state" => "0",
                "transaction_id" => "1217983401381510128537567810",
                "transport_fee" => "0"
            ];
            $status = 'error';
        }else{
            //调用微信验证
            $post = $request->get();
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //实例化模型
        $model = new GeneralPay();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $model->toMoney($post['total_fee'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => '微信支付',   //商品名称
            'general_pay_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['transaction_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['trade_state']),   //支付状态
            'general_pay_log_status' => $post['trade_state'],   //支付状态
            'pay_channel_id' => 11,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );

        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        if(!empty($_GET['debug'])){
            $status = true;
        }else{
            $status = $class->callback();
        }

        //验证支付结果
        if(!empty($model) && !empty($status)){
            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_fee'],100,'/');
            $model->general_pay_transaction_id = $post['transaction_id'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['out_trade_no'];
            $model->general_pay_verify = $model->makeSign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                //change customer balance
                $attribute = $model->getAttributes();
                if(!empty($model->order_id)){
                    //支付订单
                    GeneralPay::orderPay($attribute);
                }else{
                    //充值支付
                    GeneralPay::pay($attribute);
                }
                $transaction->commit();
                $class->notify();

                //发送短信事件
                $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                $this->trigger('paySms');

            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }

    }

    /**
     *  百付宝APP回调
     *  金额单位为【分】
     */
    public function actionBfbAppNotify()
    {
        $request = yii::$app->request;

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();
        $model = new GeneralPay();

        //POST数据
        if(!empty($_GET['debug'])){
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
            $post = $request->get();
        }

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $model->toMoney($post['total_amount'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => '百付宝',   //商品名称
            'general_pay_log_eo_order_id' => $post['order_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['bfb_order_no'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['pay_result']),   //支付状态
            'general_pay_log_status' => $post['pay_result'],   //支付状态
            'pay_channel_id' => 8,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['order_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证签名
        $class = new \bfbpay_class();
        if(!empty($_GET['debug'])){
            $sign = $class->callback();
        }else{
            $sign = true;
        }

        //验证支付结果
        if( !empty($model) && !empty($sign) ){
            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_amount'],100,'/');
            $model->general_pay_transaction_id = $post['bfb_order_no'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['order_no'];
            $model->general_pay_verify = $model->makeSign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                //change customer balance
                $attribute = $model->getAttributes();
                if(!empty($model->order_id)){
                    //支付订单
                    GeneralPay::orderPay($attribute);
                }else{
                    //充值支付
                    GeneralPay::pay($attribute);
                }

                $transaction->commit();

                //发送短信事件
                $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                $this->trigger('paySms');

                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }

        }
    }

    /**
     * 银联APP回调
     */
    public function actionUpAppNotify()
    {
        $request = yii::$app->request;

        //实例化模型
        $model = new GeneralPay();

        //POST数据
        if(!empty($_GET['debug'])){
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
            ) ;
            $post = $_POST;
        }else{
            $post = $request->post();
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $model->toMoney($post['settleAmt'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => $post['reqReserved'],   //商品名称
            'general_pay_log_eo_order_id' => $post['orderId'],   //订单ID
            'general_pay_log_transaction_id' => $post['queryId'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['respMsg']),   //支付状态
            'general_pay_log_status' => $post['respMsg'],   //支付状态
            'pay_channel_id' => 12,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );

        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['orderId']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证签名
        $class = new \uppay_class();
        $sign = $class->callback();

        //验证支付结果
        if( !empty($model) && !empty($sign) ){

            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['settleAmt'],100,'/');
            $model->general_pay_transaction_id = $post['queryId'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['orderId'];
            $model->general_pay_verify = $model->makeSign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                $attribute = $model->getAttributes();
                if(!empty($model->order_id)){
                    //支付订单
                    GeneralPay::orderPay($attribute);
                }else{
                    //充值支付
                    GeneralPay::pay($attribute);
                }
                $transaction->commit();

                //发送短信事件
                $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                $this->trigger('paySms');

                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * 用户服务卡支付
     */
    public function serviceCradPay()
    {

    }

    /**
     * 用户余额支付
     */
    public function balancePay()
    {

    }


    /**
     * Lists all GeneralPay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => GeneralPay::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GeneralPay model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new GeneralPay model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GeneralPay;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GeneralPay model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GeneralPay model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GeneralPay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GeneralPay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GeneralPay::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 财务是否对账
     * @param $id   对账ID
     * @param $status   对账状态
     * @return bool
     * @throws NotFoundHttpException
     */
    public function modifyRecontiliation($id , $status)
    {
        $model = $this->findModel($id);
        $model->id = $id;
        $model->is_reconciliation = intval($status);
        return $model->save(false);
    }

    public function actionTest()
    {
        //'2088801136967007','898111448161364','1500610004','1217983401'
        $param = [
            "pay_money" => 100,
            "customer_id" => 1,
            "channel_id" => 3,
            "partner" => '1217983401',
        ];

        $data = \core\models\GeneralPay\GeneralPay::getPayParams(100,1,5,'1217983401');
        dump($data);
        exit;
        $param = [
            'sp_refund_no' => date("ymd",time()).mt_rand(1000,9999).'1',
            'order_no' => '150927830311',   //订单号
            'cashback_amount' => '1',     //退款总额(分单位)
            'return_url' => "http://".$_SERVER['HTTP_HOST']."/general-pay/bfb-refund-notify",   //服务器异步通知地址
        ];

        $bfb = new \bfbrefund_class();
        $bfb->refund($param);

        exit;
        $wx = new \wxrefund_class();
        dump($wx->refundQuery($param));
        exit;
        $wx->refund($param);
        exit;
        $wx = new \wxpay_class();
        $wx->orderQuery();

        exit;

    }

}
