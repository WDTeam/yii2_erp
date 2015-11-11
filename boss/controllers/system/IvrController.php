<?php
namespace boss\controllers\system;

use core\models\order\Order;
use core\models\order\OrderPush;
use yii\web\Controller;
use yii\log\Logger;

class IvrController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * IVR回调,POST
     */
    public function actionCallback()
    {
        $data = \Yii::$app->ivr->cb_data;
        \Yii::getLogger()->log("ivr 回调日志", Logger::LEVEL_INFO);
        $data['orderId'];
        $data['press'];
        $data['telephone'];
        \Yii::getLogger()->log("ivr 回调日志,orderId=".$data['orderId'], Logger::LEVEL_INFO);
        if(isset($data['press'])){
            \Yii::getLogger()->log("ivr 回调日志,press=".$data['press'], Logger::LEVEL_INFO);
        }
        \Yii::getLogger()->log("ivr 回调日志,telephone=".$data['telephone'], Logger::LEVEL_INFO);
        if(isset($data['postType'])){
            \Yii::getLogger()->log("ivr 回调日志,postType=".$data['postType'], Logger::LEVEL_INFO);
        }
        $order_id = intval(str_replace('pushToWorker_','',$data['orderId']));
        if(isset($data['postType']) && $data['postType']==1 && isset($data['press']) && $data['press']==1){
            // code=1表示接单成功
            $result = Order::ivrAssignDone($order_id, $data['telephone']);
            if($result['status']){
                return json_encode(['code'=>1]);
            }else{
                OrderPush::ivrPushToWorker($order_id); //继续推送该订单的ivr
                return json_encode(['code'=>0]);
            }
        }elseif(isset($data['postType']) && $data['postType']==1 && isset($data['press']) && $data['press']!=2){
            OrderPush::ivrPushToWorker($order_id); //继续推送该订单的ivr
            return json_encode(['code'=>0]);
        }
    }
}