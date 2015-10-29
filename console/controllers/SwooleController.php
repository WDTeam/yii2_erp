<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use console\models\WorkerTask;
class SwooleController extends Controller{
    public function actionStart(){
        WorkerTask::createWorker('worker');
    }
    
    public function actionStop(){
         WorkerTask::stop();
    }
    
    public function actionRestart(){
         WorkerTask::restart();
    }
    
    public function actionset(){
         WorkerTask::restart();
    }
    
    public function actionget(){
         WorkerTask::restart();
    }
}
