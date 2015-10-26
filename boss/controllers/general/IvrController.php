<?php
namespace boss\controllers;

use core\models\order\Order;
use core\models\order\OrderPush;
use yii\web\Controller;
class IvrController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * IVR回调,POST
     */
    public function actionCallback()
    {
        $data = \Yii::$app->ivr->cb_data;
        $data['orderId'];
        $data['press'];
        $data['telephone'];
        $order_id = intval(str_replace('pushToWorker_','',$data['orderId']));
        if(isset($data['postType']) && $data['postType']==1 && isset($data['press']) && $data['press']==1){
            // code=1表示接单成功
            $result = Order::ivrAssignDone($order_id, $data['telephone']);
            if($result['status']){
                return json_encode(['code'=>1]);
            }else{
                return json_encode(['code'=>0]);
            }
        }
        OrderPush::ivrPushToWorker($order_id); //继续推送该订单的ivr

    }
    
    public function actionTest()
    {
        $res = \Yii::$app->ivr->send('15110249233', 'A1444808735', '洗衣');
        var_dump($res);
    }
    
    public function actionJpush()
    {
        $res = \Yii::$app->jpush->push(['Dhkk', 'worker_ldg_test', '15110249233'], 'test', ['test'=>'自定义数据']);
        var_dump($res);
        if(isset($res->msg_id)){
            echo '<br/>'.'<br/>'.'<br/>';
            var_dump(\Yii::$app->jpush->getReport($res->msg_id));
        }
    }
    public function actionSms()
    {
        $res = \Yii::$app->sms->send('15110249233', 'A1444808735');
        var_dump($res);
    }
}