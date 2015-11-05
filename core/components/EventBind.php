<?php
/**
 * @author CoLee
 * 注：事件绑定容易对别人功能造成阻塞，绑定需谨慎！！
 */
namespace core\components;

use core\models\worker\Worker;
use core\models\shop\Shop;
use core\models\shop\ShopManager;

use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use dbbase\components\JPush;
use dbbase\components\Sms;
use dbbase\components\Ivr;
class EventBind extends Component implements BootstrapInterface
{
    public function bootstrap($app)
    {
        /**
         * 添加阿姨时处理门店阿姨数量
         */
        Event::on(
            Worker::className(),
            Worker::EVENT_AFTER_INSERT,
            function ($event) {
                try{
                    $shop_id = $event->sender->shop_id;
                    Shop::runCalculateWorkerCount($shop_id);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\worker');
                }
            }
        );
        /**
         * 修改阿姨时处理门店阿姨数量
        */
        Event::on(
            Worker::className(),
            Worker::EVENT_AFTER_UPDATE,
            function ($event) {
                try{
                    $shop_id = $event->sender->shop_id;
                    Shop::runCalculateWorkerCount($shop_id);
                    $attributes = $event->sender->getOldAttributes();
                    $old_id = $attributes['shop_id'];
                    Shop::runCalculateWorkerCount($old_id);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\worker');
                }
            }
        );
        /**
         * 添加门店时处理家政门店数量
        */
        Event::on(
            Shop::className(),
            Shop::EVENT_AFTER_INSERT,
            function ($event) {
                try{
                    $shop_manager_id = $event->sender->shop_manager_id;
                    ShopManager::runCalculateWorkerCount($shop_manager_id);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\shop');
                }
            }
        );
        /**
         * 修改门店时处理家政门店数量
        */
        Event::on(
            Shop::className(),
            Shop::EVENT_AFTER_UPDATE,
            function ($event) {
                try{
                    $shop_manager_id = $event->sender->shop_manager_id;
                    ShopManager::runCalculateWorkerCount($shop_manager_id);
                    $attributes = $event->sender->getOldAttributes();
                    $old_id = $attributes['shop_manager_id'];
                    ShopManager::runCalculateWorkerCount($old_id);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\shop');
                }
            }
        );
        /**
         * JPUSH 事件记录到MONGODB
         */
        Event::on(
            JPush::className(),
            JPush::EVENT_PUSH_AFTER,
            function ($event) {
                try{
                    $data = $event->sender->data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('jpush_log');
                    $res = $collection->insert([
                        'tags' => $data['tags'], 
                        'msg' => $data['msg'],
                        'extras'=>$data['extras'],
                        'title'=>$data['title'],
                        'category'=>$data['category'],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'create_time'=>time(),
                    ]);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\jpush');
                }
            }
        );
        /**
         * SMS 事件记录到MONGODB
         */
        Event::on(
            Sms::className(),
            Sms::EVENT_SEND_AFTER,
            function ($event) {
                try{
                    $data = $event->sender->data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('sms_log');
                    $res = $collection->insert([
                        'general_smslog_mobiles'=>$data['pszMobis'],
                        'general_smslog_msg'=>$data['pszMsg'],
                        'general_smslog_res'=>$data['result'],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'create_time'=>time(),
                    ]);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\sms');
                }
            }
        );
        /**
         * IVR 发送事件记录到MONGODB
         */
        Event::on(
            Ivr::className(),
            Ivr::EVENT_SEND_AFTER,
            function ($event) {
//                 var_dump($event);
                try{
                    $data = $event->sender->request_data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('ivr_send_log');
                    $res = $collection->insert([
                        'ivrlog_req_tel' => $data['tel'],
                        'ivrlog_req_app_id' => $data['appId'],
                        'ivrlog_req_sign' => $data['sign'],
                        'ivrlog_req_timestamp' => $data['timestamp'],
                        'ivrlog_req_order_message' => $data['orderMessage'],
                        'ivrlog_req_order_id' => $data['orderId'],
                        'ivrlog_res_result' => $data['result'],
                        'ivrlog_res_unique_id'=>isset($data['uniqueId'])?$data['uniqueId']:'',
                        'ivrlog_res_clid'=>(isset($data['uniqueId']) && isset($data['clid']))?$data['clid']:'',
                        'ivrlog_res_description'=>$data['description'],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'create_time'=>time(),
                    ]);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\ivrsend');
                }
            }
        );
        /**
         * IVR 回调事件记录到MONGODB
         */
        Event::on(
            Ivr::className(),
            Ivr::EVENT_CALLBACK,
            function ($event) {
                try{
                    $cbdata = (object)$this->sender->cb_data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('ivr_callback_log');
                    $res = $collection->insert([
                        'ivrlog_cb_telephone' => $cbdata->telephone,
                        'ivrlog_cb_order_id' => $cbdata->orderId,
                        'ivrlog_cb_press' => $cbdata->press,
                        'ivrlog_cb_result' => $cbdata->result,
                        'ivrlog_cb_post_type' => $cbdata->postType,
                        'ivrlog_cb_webcall_request_unique_id'=>isset($cbdata->webcall_request_unique_id)?$cbdata->webcall_request_unique_id:'',
                        'created_at'=>date('Y-m-d H:i:s'),
                        'create_time'=>time(),
                    ]);
                    
//                     $text = json_encode($data);
//                     $sendres = \Yii::$app->mailer->compose()
//                     ->setFrom('service@corp.1jiajie.com')
//                     ->setTo([
//                         'lidenggao@1jiajie.com',
//                         //             'weibeinan@1jiajie.com',
//                     //             'guohongbo@1jiajie.com',
//                     //             'linhongyou@1jiajie.com'
                    
//                     ])
//                     ->setSubject('ivr callback ')
//                     ->setTextBody($text)
//                     ->send();
                }catch(\Exception $e){
                    \Yii::error($e, 'event\ivrcallback');
                }
            }
        );
    }
}