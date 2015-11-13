<?php

namespace boss\controllers\payment;

use boss\components\BaseAuthController;
use boss\models\payment\Payment;
use boss\models\payment\PaymentSearch;

use core\models\customer\Customer;
use core\models\payment\CustomerTransRecord;
use core\models\payment\PaymentCustomerTransRecord;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;


/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends BaseAuthController
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
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Payment model.
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
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
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
    public function modifyRecontiliation($id, $status)
    {
        $model = $this->findModel($id);
        $model->id = $id;
        $model->is_reconciliation = intval($status);
        return $model->save(false);
    }

    public function actionTest()
    {

        //充值
        $order_id = 1;
        $order_channel_id = 24;
        $type = 'payment';
        //在线支付
        $order_id = 1;
        $order_channel_id = 24;
        $type = 'order_pay';
        //在线支付+优惠券
        $order_id = 1;
        $order_channel_id = 24;
        $type = 'order_pay';
        //余额支付
//        $order_id = 1;
//        $order_channel_id = 20;
//        $type = 'order_pay';

//        //服务卡支付
//        $order_id = 3;
//        $order_channel_id = 24;
//        $type = 'order_pay';
        //现金支付
//        $order_id = 1;
//        $order_channel_id = 24;
//        $type = 'order_pay';
//        //预付费支付
//        $order_id = 1;
//        $order_channel_id = 24;
//        $type = 'order_pay';

        //余额+在线支付
//        $order_id = 5;
//        $order_channel_id = 24;
//        $type = 'order_pay';
//        //余额+在线支付+优惠券
//        $order_id = 6;
//        $order_channel_id = 24;
//        $type = 'order_pay';
//        //余额+优惠券
//        $order_id = 1212;
//        $order_channel_id = 24;
//        $type = 'order_pay';
        $data = PaymentCustomerTransRecord::analysisRecord($order_id, $order_channel_id, $type, 1);
        var_dump($data);

        exit;
        Yii::$app->redis->set('a', '12345');
        //Yii::$app->cache->set();
        echo Yii::$app->redis->get('a'), "\n";

        //Yii::$app->cache->set('test1', 'haha..', 5);
        //echo '1 ', Yii::$app->cache->get('test1'), "\n";
        //sleep(6);
        //echo '2 ', Yii::$app->cache->get('test1'), "\n";

        exit;

        $model = new Payment();
        $model->getPayChannelList();
        exit;
        $data = Payment::orderRefund(1, 1010);
        dump($data);
        exit;

        /**
         * 调用(调起)在线支付,发送给支付接口的数据
         * @param integer $payment_type 支付类型,1普通订单,2周期订单,3充值订单
         * @param integer $customer_id 消费者ID
         * @param integer $channel_id 渠道ID
         * @param integer $order_id 订单ID
         * @param integer $partner 第三方合作号
         */
        //$data = \core\models\payment\Payment::getPayParams(2,1,24,'Z681511017247639');
        //dump($data);
        //exit;

        exit;

        $customerBalance = Customer::getBalanceById(1);
        dump($customerBalance['balance']);
        exit;

        $attr = [
            'id' => 45,
            'address_id' => 9,
        ];
        $order = \core\models\order\OrderSearch::getOne($attr['id']);
        //dump($order->orderExtWorker->worker_id);exit;
        $data = $order->modify($attr);
        var_dump($data);
        exit;
        $data = [
            'customer_id' => 1,  //用户ID
            'order_id' => 1, //订单ID
            'order_channel_id' => 1, //订单渠道
            'payment_customer_trans_record_order_channel' => 1,  //订单渠道名称
            'pay_channel_id' => 1,   //支付渠道
            'payment_customer_trans_record_pay_channel' => 1,    //支付渠道名称
            'payment_customer_trans_record_mode' => 1,   //交易方式:1消费,2=充值,3=退款,4=补偿
            'payment_customer_trans_record_mode_name' => 190,  //交易方式:1消费,2=充值,3=退款,4=补偿
            'payment_customer_trans_record_order_total_money' => 190,  //订单总额
            'payment_customer_trans_record_cash' => 190, //现金支付
            'scenario' => 190
        ];
        $d = \core\models\payment\PaymentCustomerTransRecord::createRecord($data);
        dump($d);
        exit;
        $str = 'a:23:{s:12:"payment_type";s:1:"1";s:7:"subject";s:19:"e家洁在线支付";s:8:"trade_no";s:16:"2015102942279250";s:11:"buyer_email";s:11:"18311474301";s:10:"gmt_create";s:19:"2015-10-29 15:57:07";s:11:"notify_type";s:17:"trade_status_sync";s:8:"quantity";s:1:"1";s:12:"out_trade_no";s:13:"1510290160566";s:9:"seller_id";s:16:"2088801136967007";s:11:"notify_time";s:19:"2015-10-29 15:57:09";s:4:"body";s:26:"e家洁在线支付0.02元";s:12:"trade_status";s:14:"TRADE_FINISHED";s:19:"is_total_fee_adjust";s:1:"N";s:9:"total_fee";s:4:"0.02";s:11:"gmt_payment";s:19:"2015-10-29 15:57:08";s:12:"seller_email";s:15:"47632990@qq.com";s:9:"gmt_close";s:19:"2015-10-29 15:57:08";s:5:"price";s:4:"0.02";s:8:"buyer_id";s:16:"2088802381237501";s:9:"notify_id";s:34:"2983afc3b92e376e84923e4c75e0f3574s";s:10:"use_coupon";s:1:"N";s:9:"sign_type";s:3:"RSA";s:4:"sign";s:172:"ZlCICZ/ar7ePcQalT2s1sI7o8Bqrt4picnzIxaucQeNi8GE/mmch4armXS2BKmlzSpyLcP9Ge+CSC2JOxRMZbSl2aZT4xy6qvllToCBBos4tcybujHR61lrIeY8nSnWlGFTq11N7+9aKHZ2GuNtpoRAPxQswJC+M6ekopYmelrc=";}||a:23:{s:12:"payment_type";s:1:"1";s:7:"subject";s:19:"e家洁在线支付";s:8:"trade_no";s:16:"2015102942279250";s:11:"buyer_email";s:11:"18311474301";s:10:"gmt_create";s:19:"2015-10-29 15:57:07";s:11:"notify_type";s:17:"trade_status_sync";s:8:"quantity";s:1:"1";s:12:"out_trade_no";s:13:"1510290160566";s:9:"seller_id";s:16:"2088801136967007";s:11:"notify_time";s:19:"2015-10-29 16:01:23";s:4:"body";s:26:"e家洁在线支付0.02元";s:12:"trade_status";s:14:"TRADE_FINISHED";s:19:"is_total_fee_adjust";s:1:"N";s:9:"total_fee";s:4:"0.02";s:11:"gmt_payment";s:19:"2015-10-29 15:57:08";s:12:"seller_email";s:15:"47632990@qq.com";s:9:"gmt_close";s:19:"2015-10-29 15:57:08";s:5:"price";s:4:"0.02";s:8:"buyer_id";s:16:"2088802381237501";s:9:"notify_id";s:34:"2983afc3b92e376e84923e4c75e0f3574s";s:10:"use_coupon";s:1:"N";s:9:"sign_type";s:3:"RSA";s:4:"sign";s:172:"KOL2stTNfEGWMrzf3tkwKzkBU0Riz2sTZqjVkthSgZDz3zZ7BgQqCktoruovPQX/vvqKeGNVcHwIvaSmTaToZaSPRVdDK+yA88gTcBtEGa6uQtIF0MXCsv40Cefx2DRfThzuUE1kUX1YK+lzOXq5W5qeY8g8SBZxtbP7+636wTI=";}';
        dump(unserialize($str));

        exit;
        \core\models\payment\PaymentSearch::getOrderMoney([48, 49, 50, 51]);

        exit;
        var_dump(preg_replace("/(\d+)/", 5, "'FULLTIME_WORKER_TIMEOUT' => 1"));
        exit;
        $data = \core\models\order\OrderSearch::getWorkerAndOrderAndDoneTime(1, '1445948100', '1445948900');
        dump($data);
        exit;

        $data = \core\models\order\OrderSearch::getWorkerAndOrderAndMonth(1, 2015, 10);
        dump($data);
        exit;
        $data = \core\models\order\OrderSearch::getOrderAndCustomer(3);
        dump($data);
        exit;

        $data = \core\models\payment\Payment::getPayParams('0.01', 1, 23, '1500610004');
        dump($data);
    }

}
