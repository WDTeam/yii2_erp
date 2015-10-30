<?php
namespace core\behaviors;

use yii\base\Behavior;
use dbbase\components\Ivr;
use dbbase\models\worker\WorkerIvrlog;
class IvrlogBehavior extends Behavior
{

    public function events()
    {
        return [
            Ivr::EVENT_SEND_AFTER=> 'sendAfter',
            Ivr::EVENT_CALLBACK=> 'callback',
        ];
    }
    
    public function sendAfter($event)
    {
        $data = $this->owner->request_data;
        $model = new WorkerIvrlog();
        $model->ivrlog_req_tel = $data['tel'];
        $model->ivrlog_req_app_id = $data['appId'];
        $model->ivrlog_req_sign = $data['sign'];
        $model->ivrlog_req_timestamp = $data['timestamp'];
        $model->ivrlog_req_order_message = $data['orderMessage'];
        $model->ivrlog_req_order_id = $data['orderId'];
        $model->ivrlog_res_result = $data['result'];
        if(isset($data['uniqueId']) && isset($data['clid'])){
            $model->ivrlog_res_unique_id = $data['uniqueId'];
            $model->ivrlog_res_clid = $data['clid'];
        }
        $model->ivrlog_res_description = $data['description'];
        $model->save(true);
        $this->_model = $model;
    }
    
    protected $_model;
    public function callback($event)
    {
//         $text = json_encode($data);
//         $sendres = \Yii::$app->mailer->compose()
//         ->setFrom('service@corp.1jiajie.com')
//         ->setTo('lidenggao@1jiajie.com')
//         ->setSubject('ivr callback ')
//         ->setTextBody($text)
//         ->send();
        
        $cbdata = (object)$this->owner->cb_data;
        $model = WorkerIvrlog::find()->where([
            'ivrlog_req_tel'=>$cbdata->telephone,
            'ivrlog_req_order_id'=>$cbdata->orderId,
        ])->one();
        if(isset($model)){
            $model->ivrlog_cb_telephone = $cbdata->telephone;
            $model->ivrlog_cb_order_id = $cbdata->orderId;
            $model->ivrlog_cb_press = $cbdata->press;
            $model->ivrlog_cb_result = $cbdata->result;
            $model->ivrlog_cb_post_type = $cbdata->postType;
            if(isset($cbdata->webcall_request_unique_id)){
                $model->ivrlog_cb_webcall_request_unique_id = $cbdata->webcall_request_unique_id;
            }
            $model->ivrlog_cb_time = time();
            $model->save(true);
        }
        $this->_model = $model;
    }
    public function getModel()
    {
        return $this->_model;
    }
    
}