<?php

namespace boss\controllers;

use Yii;
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
            $order = GeneralPay::find()->where(['order_id'=>$data['order_id'],'general_pay_status'=>1,'is_del'=>1])->one();
            if(!empty($order)){
                exit("订单已经支付过");
            }
        }

        //在线支付（online_pay），在线充值（pay）
        $scenario = empty($data['order_id']) ? 'pay' : 'online_pay';

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
/*
        $dataProvider = new ActiveDataProvider([
            'query' => GeneralPay::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    */
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
     * 退款
     * @return mixed
     */
    public function actionRefund()
    {

    }

    /**
     * 支付宝APP回调
     */
    public function actionAlipayAppNotify(){

        $request = yii::$app->request;

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //POST数据
        if(!empty($_GET['debug'])){
            $_POST = array (
                "discount"=> "0.00",
                "payment_type"=> "1",
                "subject"=> "e家洁会员充值",
                "trade_no"=> "2015092510430816",
                "buyer_email"=> "lsqpy@163.com",
                "gmt_create"=> "2015-09-25 21:13:20",
                "notify_type"=> "trade_status_sync",
                "quantity"=> "1",
                "out_trade_no"=> "ali_app_0925_8467_65",
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

        //写入文本日志
        $GeneralPayLogModel->writeLog($post);

        //记录日志
        $_post['general_pay_log_price'] = $post['total_fee'];   //支付金额
        $_post['general_pay_log_shop_name'] = $post['subject'];   //商品名称
        $_post['general_pay_log_eo_order_id'] = $post['out_trade_no'];   //订单ID
        $_post['general_pay_log_transaction_id'] = $post['buyer_id'];   //交易流水号
        $_post['general_pay_log_status_bool'] = $post['trade_status'];   //支付状态
        $_post['general_pay_log_status'] = $post['trade_status'];   //支付状态
        $GeneralPayLogModel->insertLog($_post);

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0,'is_del'=>1])->one();

        //验证支付结果
        if(!empty($model)){

            //验证签名
            $alipay = new \alipay_class;
            $verify_result = $alipay->callback();
            
            if(!empty($_GET['debug'])){
                $verify_result = true;
            }
            //签名验证成功
            if($verify_result) {
                $model->id = $GeneralPayId; //ID
                $model->general_pay_status = 1; //支付状态
                $model->general_pay_actual_money = $post['total_fee'];
                $model->general_pay_transaction_id = $post['trade_no'];
                $model->general_pay_is_coupon = 1;
                $model->general_pay_eo_order_id = $post['out_trade_no'];
                $model->general_pay_verify = md5(1);

                if($model->save(false)) $status = true;
            }
        }
        echo !empty($status) ? 'success' : 'fail';
    }

    /**
     * 微信APP回调
     */
    public function actionWxAppNotify(){

        $notify = new \wxpay_class();
        //调用微信验证
        $notify->callback();
        //获取微信数据
        $post = $notify->getNotifyData();
        //获取验证状态
        $status = $notify->notify();
        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $_post['general_pay_log_price'] = $post['total_fee'];   //支付金额
        $_post['general_pay_log_shop_name'] = $post['attach'];   //商品名称
        $_post['general_pay_log_eo_order_id'] = $post['out_trade_no'];   //订单ID
        $_post['general_pay_log_transaction_id'] = $post['transaction_id'];   //交易流水号
        $_post['general_pay_log_status_bool'] = $post['result_code'];   //支付状态
        $_post['general_pay_log_status'] = $post['result_code'];   //支付状态
        $GeneralPayLogModel->insertLog($_post);

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0,'is_del'=>1])->one();

        //验证支付结果
        if(!empty($model)){

            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_fee'],100,true);
            $model->general_pay_transaction_id = $post['trade_no'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['out_trade_no'];
            $model->general_pay_verify = md5(1);

            $model->save(false);

        }
        echo $status;
    }

    /**
     *  百付宝APP回调
     */
    public function actionBfbAppNotify()
    {

        $request = yii::$app->request;

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

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
                'order_no' => 'BAid63146id24245',
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
        $_post['general_pay_log_price'] = $post['total_amount'];   //支付金额
        $_post['general_pay_log_shop_name'] = '百付宝';   //商品名称
        $_post['general_pay_log_eo_order_id'] = $post['order_no'];   //订单ID
        $_post['general_pay_log_transaction_id'] = $post['bfb_order_no'];   //交易流水号
        $_post['general_pay_log_status_bool'] = $post['pay_result'];   //支付状态
        $_post['general_pay_log_status'] = $post['pay_result'];   //支付状态
        $GeneralPayLogModel->insertLog($_post);

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['order_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0,'is_del'=>1])->one();

        //验证签名
        $bfb = new \bfbpay_class();
        $sign = $bfb->callback();

        //验证支付结果
        if( !empty($model) && !empty($sign) ){

            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_fee'],100,true);
            $model->general_pay_transaction_id = $post['bfb_order_no'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['order_no'];
            $model->general_pay_verify = md5(1);

            $model->save(false);
            $bfb->notify();
        }

    }

    /**
     * 银联APP回调
     */
    public function actionUpAppNotify()
    {
        $request = yii::$app->request;

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //POST数据
        if(!empty($_GET['debug'])){
            $_POST = array (
                'bank_no' => '',
                'bfb_order_create_time' => '20150714115504',
                'bfb_order_no' => '2015071415006100041110555687771',
                'buyer_sp_username' => '',
                'currency' => '1',
                'extra' => '',
                'fee_amount' => '0',
                'input_charset' => '1',
                'order_no' => 'BAid63146id24245',
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
            $post = $_POST;
        }else{
            $post = $request->post();
        }

        //记录日志
        $_post['general_pay_log_price'] = $post['total_amount'];   //支付金额
        $_post['general_pay_log_shop_name'] = '银联支付';   //商品名称
        $_post['general_pay_log_eo_order_id'] = $post['order_no'];   //订单ID
        $_post['general_pay_log_transaction_id'] = $post['bfb_order_no'];   //交易流水号
        $_post['general_pay_log_status_bool'] = $post['pay_result'];   //支付状态
        $_post['general_pay_log_status'] = $post['pay_result'];   //支付状态
        $GeneralPayLogModel->insertLog($_post);

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['order_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0,'is_del'=>1])->one();

        //验证签名
        $class = new \uppay_class();
        $sign = $class->callback();

        //验证支付结果
        if( !empty($model) && !empty($sign) ){

            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_fee'],100,true);
            $model->general_pay_transaction_id = $post['bfb_order_no'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['order_no'];
            $model->general_pay_verify = md5(1);

            $model->save(false);
            $class->notify();
        }

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

}
