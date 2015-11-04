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
        
                }
            }
        );
    }
}