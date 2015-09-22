<?php

namespace boss\controllers;

use Yii;
use common\models\GeneralPay;
use common\models\GeneralPayLog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
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
            $data = GeneralPay::find()->where(['order_id'=>$data['order_id'],'general_pay_status'=>1,'is_del'=>1])->one();
            if(!empty($data)){
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
            echo "验证成功!";

        }else{
            var_dump($model->errors);
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

         $_POST = array (
          'discount' => '0.00',
          'payment_type' => '1',
          'subject' => 'e家洁会员充值',
          'trade_no' => '2015070561619716',
          'buyer_email' => 'lsqpy@163.com',
          'gmt_create' => '2015-07-05 13:07:39',
          'notify_type' => 'trade_status_sync',
          'quantity' => '1',
          'out_trade_no' => 'ALAPP_2015070513073239657_6',
          'seller_id' => '2088801136967007',
          'notify_time' => '2015-07-05 13:07:40',
          'body' => 'e家洁会员充值0.01元',
          'trade_status' => 'TRADE_FINISHED',
          'is_total_fee_adjust' => 'N',
          'total_fee' => '0.01',
          'gmt_payment' => '2015-07-05 13:07:40',
          'seller_email' => '47632990@qq.com',
          'gmt_close' => '2015-07-05 13:07:40',
          'price' => '0.01',
          'buyer_id' => '2088002074138164',
          'notify_id' => '82c817f0e5b8a8e60cd1b0f82706a0be2w',
          'use_coupon' => 'N',
          'sign_type' => 'RSA',
          'sign' => 'UTtBWOmbrxA3XnU2Sz9kwC32s5S+ZLF+ZlaxTKfD2PYH+q/RwJDv1BnuG1PWdyQDwAf5J8QpeTTjaAXhRWUs8Naa9F/HWWZA9iB5WoHcZd10A7nRioB6wV61gaGYczBD30+9L+wiBnlotJVJR+xf0AOp11YDaHiH8bNHNSHg9ak=',
        );

        $request = yii::$app->request;

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //POST数据
        if(!empty($_GET['debug'])){
            $post = $_POST;
        }else{
            $post = $request->post();
        }
        //记录日志
        $post['general_pay_log_price'] = $post['total_fee'];   //支付金额
        $post['general_pay_log_shop_name'] = $post['subject'];   //商品名称
        $post['general_pay_log_eo_order_id'] = $post['out_trade_no'];   //订单ID
        $post['general_pay_log_transaction_id'] = $post['buyer_id'];   //交易流水号
        $post['general_pay_log_status_bool'] = $post['trade_status'];   //支付状态
        $post['general_pay_log_status'] = $post['trade_status'];   //支付状态
        $GeneralPayLogModel->insertLog($post);

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0,'is_del'=>1])->one();

        //验证支付结果
        if(!empty($model)){

            //配置参数
            $config = Yii::$app->params['alipay_web_config'];

            //验证签名
            $alipayNotify = new \AlipayNotify($config);
            if(!empty($_GET['debug'])){
                $verify_result = true;
            }else{
                $verify_result = $alipayNotify->verifyNotify();
            }

            //签名验证成功
            if($verify_result) {
                if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
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
        }
        echo !empty($status) ? 'success' : 'fail';
    }

    /**
     * 微信APP回调
     */
    public function actionWxAppNotify($post=null, $msg=null){
        //判断是回调数据还是处理数据
        if(is_null($post) && is_null($msg)){
            $notify = new \WxPayNotify();
            $notify->Handle(false);
        }else{

            $result = array (
                'appid' => 'wx7558e67c2d61eb8f',
                'attach' => 'e家洁在线支付',
                'bank_type' => 'CMB_CREDIT',
                'cash_fee' => '3500',
                'fee_type' => 'CNY',
                'is_subscribe' => 'Y',
                'mch_id' => '10037310',
                'nonce_str' => 'k67hrd7daokfetzwz1fcyehuz8vgehsb',
                'openid' => 'o7KvajgiK1XXWInL-eMJjjrlQAmc',
                //'out_trade_no' => 'WXWAP_2015092211069962_32551',
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'sign' => '45A1A35B9F557FC5D914DF9E0D39EE21',
                'time_end' => '20150922110653',
                'total_fee' => '3500',
                'trade_type' => 'JSAPI',
                'transaction_id' => '1006560062201509220954999431',
            );

            //实例化模型
            $GeneralPayLogModel = new GeneralPayLog();

            //记录日志
            $post['general_pay_log_price'] = $post['total_fee'];   //支付金额
            $post['general_pay_log_shop_name'] = $post['attach'];   //商品名称
            $post['general_pay_log_eo_order_id'] = $post['out_trade_no'];   //订单ID
            $post['general_pay_log_transaction_id'] = $post['transaction_id'];   //交易流水号
            $post['general_pay_log_status_bool'] = $post['result_code'];   //支付状态
            $post['general_pay_log_status'] = $post['result_code'];   //支付状态
            $GeneralPayLogModel->insertLog($post);

            //实例化模型
            $model = new GeneralPay();

            //获取交易ID
            $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

            if(!array_key_exists("transaction_id", $post)){
                $msg = "输入参数不正确";
                return false;
            }

            //查询订单，判断订单真实性
            //if(!$this->Queryorder($post["transaction_id"])){
            //    $msg = "订单查询失败";
            //    return false;
            //}

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

                if($model->save(false)) $status = true;

            }
            return empty($status) ? false : true;
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
