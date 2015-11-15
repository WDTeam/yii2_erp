<?php
namespace console\controllers;

use core\models\operation\OperationAdvertRelease;

use Yii;
use yii\console\Controller;
use core\components\ConsoleHelper;


class AdvertController extends Controller
{

    /**
     * 根据当前时间修改已发布并设置了上下线时间的广告状态
     *
     * @author bobo
     */
    public function actionAdvertStatus()
    {
        OperationAdvertRelease::setAdvertStatus();
    }

    /**
     * 把已发布并设置了下线时间的广告状态设置为已下线
     *
     * @author bobo
     */
    //public function actionAdvertOffline()
    //{
    //}
}
