<?php

use core\models\worker\Worker;
use yii\base\Event;
use core\models\shop\Shop;
use core\models\shop\ShopManager;

Yii::setAlias('dbbase', dirname(__DIR__));
Yii::setAlias('boss', dirname(dirname(__DIR__)) . '/boss');
Yii::setAlias('autoassign', dirname(dirname(__DIR__)) . '/autoassign');
//Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('restapi', dirname(dirname(__DIR__)) . '/restapi');
Yii::setAlias('core', dirname(dirname(__DIR__)) . '/core');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

/**
 * 添加阿姨时处理门店阿姨数量
 */
Event::on(
    Worker::className(),
    Worker::EVENT_AFTER_INSERT,
    function ($event) {
        $shop_id = $event->sender->shop_id;
        Shop::runCalculateWorkerCount($shop_id);
    }
);
/**
 * 修改阿姨时处理门店阿姨数量
 */
Event::on(
    Worker::className(),
    Worker::EVENT_AFTER_UPDATE,
    function ($event) {
        $shop_id = $event->sender->shop_id;
        Shop::runCalculateWorkerCount($shop_id);
        $attributes = $event->sender->getOldAttributes();
        $old_id = $attributes['shop_id'];
        Shop::runCalculateWorkerCount($old_id);
    }
);
/**
 * 添加门店时处理家政门店数量
 */
Event::on(
    Shop::className(),
    Shop::EVENT_AFTER_INSERT,
    function ($event) {
        $shop_manager_id = $event->sender->shop_manager_id;
        ShopManager::runCalculateWorkerCount($shop_manager_id);
    }
);
/**
 * 修改门店时处理家政门店数量
 */
Event::on(
    Shop::className(),
    Shop::EVENT_AFTER_UPDATE,
    function ($event) {
        $shop_manager_id = $event->sender->shop_manager_id;
        ShopManager::runCalculateWorkerCount($shop_manager_id);
        $attributes = $event->sender->getOldAttributes();
        $old_id = $attributes['shop_manager_id'];
        ShopManager::runCalculateWorkerCount($old_id);
    }
);