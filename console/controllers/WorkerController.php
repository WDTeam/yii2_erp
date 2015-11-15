<?php
namespace console\controllers;

use core\models\worker\Worker;
use core\models\worker\WorkerForRedis;

use Yii;
use yii\console\Controller;
use core\components\ConsoleHelper;


class WorkerController extends Controller{


    /**
     * 初始化Redis中所有阿姨 及 商圈阿姨关联信息
     */
    public function actionInitAllWorkerForRedis()
    {
        WorkerForRedis::initAllWorkerToRedis();
        ConsoleHelper::log('init all worker in Redis success~');
    }

}
