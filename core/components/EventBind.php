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
                    $data = (array)$event->sender->data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('jpush_log');
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['create_time'] = time();
                    $data['_SERVER'] = $_SERVER;
                    $res = $collection->insert($data);
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
                    $data = (array)$event->sender->data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('sms_log');
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['create_time'] = time();
                    $data['_SERVER'] = $_SERVER;
                    $res = $collection->insert($data);
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
                    $data = (array)$event->sender->request_data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('ivr_send_log');
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['create_time'] = time();
                    $data['_SERVER'] = $_SERVER;
                    $res = $collection->insert($data);
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
                    $data = (array)$event->sender->cb_data;
                    $mongo = \Yii::$app->mongodb;
                    $collection = $mongo->getCollection('ivr_callback_log');
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['create_time'] = time();
                    $data['_SERVER'] = $_SERVER;
                    $res = $collection->insert($data);
                }catch(\Exception $e){
                    \Yii::error($e, 'event\ivrcallback');
                }
            }
        );
    }
}