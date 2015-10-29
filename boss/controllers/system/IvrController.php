<?php
namespace boss\controllers\system;

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
}