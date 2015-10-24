<?php

namespace boss\controllers;
use common\models\FinanceOrderChannel;
use common\models\pay\GeneralPayRefund;
use boss\models\pay\GeneralPaySearch;
use Yii;
use core\models\Customer;
use core\models\CustomerTransRecord\CustomerTransRecord;
use boss\models\pay\GeneralPay;
use common\models\pay\GeneralPayLog;
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
     * Lists all GeneralPay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GeneralPaySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
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

    public function actionOrderChannel($q = null, $id = null)
    {
        $channel = FinanceOrderChannel::get_order_channel_list();
        foreach( $channel as $k=>$v ){
            $channel[$k]['text'] = $v['finance_order_channel_name'];
        }
        return json_encode(['results'=>$channel]);
    }


    public function actionShow(){
        file_put_contents('/tmp/pay/zhidaohao_refund.php',$_REQUEST);
        echo 1;
    }

    public function actionTest()
    {

        $model = new \core\models\GeneralPay\GeneralPay;
        $model->balancePay(['order_id'=>1,'customer_id'=>1]);
        exit;
        $model = new \core\models\GeneralPay\GeneralPay;
        $model->getPayParams( 10,1,6,'1217983401',0,$ext_params=["return_url"	=> 'www.baidu.com', "show_url"	=> 'www.page.com'] );
        $param = [
            'out_trade_no'=>$model->create_out_trade_no(),
            'subject'=>$model->subject(),
            'body'=>$model->body(),
            'total_fee'=>50,
            'notify_url'=>$model->notify_url('alipay-web'),
            "return_url"	=> 'www.baidu.com',
            "show_url"	=> 'www.page.com',
        ];

        dump($param);
        exit;
        $obj = new \core\models\GeneralPay\GeneralPayRefund();
        $condition['order_id'] = 1;
        $condition['customer_id'] = 1;
        $s = $obj->call_pay_refund($condition['order_id'],$condition['customer_id']);
        var_dump($s);
        exit;
        //微信APP退款
        //商户订单号                  $param['out_trade_no'];
        //财付通订单号                $param['transaction_id'];
        //必须保证全局唯一，同个退款单号财付通认为是同笔请求
        //商户退款单号                $param['out_refund_no']
        //订单总金额,以分为单位        $param['total_fee'];
        //退款金额,以分为单位          $param['refund_fee']
        //操作员密码,MD5处理          $param['op_user_passwd']
        //---------------------------------------------
        //微信h5退款
        //退款交易流水号               $param['transaction_id']
        //退款订单号                  $param['out_trade_no']
        //退款总金额,以分为单位        $param["total_fee"]
        //退款金额,以分为单位          $param["refund_fee"];
        //退款订单ID                  $param["trade_no"];
        //---------------------------------------------
        //百度APP退款
        //服务器异步通知地址             $param['return_url'];
        //退款订单号                     $param['sp_refund_no'];
        //商户订单号                     $param['order_no'];
        //退款金额(单位/分),以分为单位     $param['cashback_amount'];
        //---------------------------------------------
        //百度闭环退款
        //服务器异步通知地址             $param['refund_url'];
        //闭环订单号                     $param['order_id']
        //商户订单号                     $param['order_no']

        /**
         * $order_id    订单ID
         * $refund_fee 退款金额
         * @return bool
         */

        //function requestRefund($order_id,$out_trade_no,$refund_fee);
        //function confirmRefund

        $param = [
            'return_url' => 'http://dev.boss.1jiajie.com/general-pay/show',//退款总金额
            'sp_refund_no' => '1510210261185',   //退款订单号
            'order_no' => '1510210133051',   //商户订单号
            'cashback_amount' => 1,
        ];

        $bfb = new \bfbrefund_class();
        $bfb->refund($param);

        exit;
        $zhidahao = new \zhidahao_refund_class();
        $re = $zhidahao->refund($param);
        dump($re);

        exit;
        $wx = new \wxjsrefund_class();
        $re = $wx->refund($param);
        dump($re);
        exit;
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
