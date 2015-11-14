<?php

namespace boss\controllers\system;

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
        $i=0;
        $_res = [];
        while ($i<5){
            $_res[] = $r = $res->push(['worker_15110249233'],'test: 服务时间是:15年11月12日，星期四，09点至12点半，时长0个半小时。服务地址是：北京,北京市,朝阳区,光华路soho,林,13141451414！');
            $i++;
            var_dump($r->isOk);
        }
//         var_dump($_res);
    }
    /**
     * 发短信 DEMO
     */
    public function actionSms()
    {
        $res = \Yii::$app->sms->send('15110249233', 'test msg', 1);
        return $res;
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
        $res = \Yii::$app->ivr->send('15110249233', 'A1444808735', '服务时间是:15年11月12日，星期四，09点至12点半，时长0个半小时。服务地址是：北京,北京市,朝阳区,光华路soho,林,13141451414！');
        var_dump($res);
    }
    
    public function actionEmail()
    {
        $text = 'test';
        $sendres = \Yii::$app->mailer->compose()
        ->setFrom('service@corp.1jiajie.com')
        ->setTo(['lidenggao@1jiajie.com'])
        ->setSubject('send email test')
        ->setTextBody($text)
        ->send();
    }
    
    public function actionMongodb()
    {
        $mongo = \Yii::$app->mongodb;
        $collection = $mongo->getCollection('test');
        $res = $collection->insert(['name' => 'John Smith', 'status' => 1]);
        var_dump($res);
    }
    
    public function actionLog()
    {
        \Yii::info(['a'=>111], 'customs\test');
    }
    
    public function actionQiniu()
    {
        echo \Yii::$app->imageHelper->getLink('Fl4NNh_n5xoYzAeBTY7RgIBcAQCZ');
    }
}
