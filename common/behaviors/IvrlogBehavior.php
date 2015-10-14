<?php
namespace common\behaviors;

use yii\base\Behavior;
use yii\base\InvalidValueException;
use yii\base\Object;
use boss\models\ShopStatus;
use common\models\ActiveRecord;
use common\components\Ivr;
use common\models\Ivrlog;
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
        $model = new Ivrlog();
        $model->ivrlog_req_tel = $data['tel'];
        $model->ivrlog_req_app_id = $data['appId'];
        $model->ivrlog_req_sign = $data['sign'];
        $model->ivrlog_req_timestamp = $data['timestamp'];
        $model->ivrlog_req_order_message = $data['orderMessage'];
        $model->ivrlog_req_order_id = $data['orderId'];
        $model->ivrlog_res_result = $data['result'];
        $model->ivrlog_res_unique_id = $data['unique_id'];
        $model->ivrlog_res_clid = $data['clid'];
        $model->ivrlog_res_description = $data['description'];
        $model->save(true);
    }
    
    protected $_model;
    public function callback($event)
    {
        $cbdata = (object)$this->owner->cb_data;
        $model = $this->getModel();
        if(isset($model)){
            $model->ivrlog_cb_telephone = $cbdata->telephone;
            $model->ivrlog_cb_order_id = $cbdata->orderId;
            $model->ivrlog_cb_press = $cbdata->press;
            $model->ivrlog_cb_result = $cbdata->result;
            $model->ivrlog_cb_post_type = $cbdata->postType;
            $model->ivrlog_cb_webcall_request_unique_id = $cbdata->webcall_request_unique_id;
            $model->ivrlog_cb_time = time();
            $model->save(true);
        }
    }
    public function getModel()
    {
        $this->_model = Ivrlog::find()->where([
            'ivrlog_req_tel'=>$cbdata->telephone,
            'ivrlog_req_order_id'=>$cbdata->orderId,
        ])->one();
        return $this->_model;
    }
    
}