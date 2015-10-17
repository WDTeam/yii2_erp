<?php
namespace console\controllers;

use core\models\order\Order;
use Yii;
use yii\console\Controller;

class OrderController extends Controller{
    public function actionUnlock(){
        Order::manualAssignUnlock();
        echo 'success';
    }
}