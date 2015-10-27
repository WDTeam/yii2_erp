<?php

namespace boss\controllers\general;

use Yii;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use core\models\worker\WorkerTask;


/**
 * DemoController implements the CRUD actions for User model.
 */
class DemoController extends BaseAuthController
{
    /**
     * JPush DEMO
     */
    public function actionJpush()
    {
        $res = \Yii::$app->jpush;
        var_dump($res->push(), $res->getReport());
    }
    /**
     * 发短信 DEMO
     */
    public function actionSms()
    {
        return \Yii::$app->sms->send('15110249233', 'test msg', 1);
    }
    
    public function actionTask()
    {
        $start_time = time()-3600*24*7;
        $end_time = time();
        $worker_id = 12;
        $res = WorkerTask::getDoneTasksByWorkerId($start_time, $end_time, $worker_id);
        return $this->render('index', [
            'res'=>$res,
        ]);
    }

    public function actionIvr()
    {
        $res = \Yii::$app->ivr->send('15110249233', 'A1444808735', '洗衣');
        var_dump($res);
    }
}
