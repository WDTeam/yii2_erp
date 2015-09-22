<?php
namespace boss\components;

use yii;
use yii\web\ForbiddenHttpException;
class BaseController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        $action = Yii::$app->controller->action->id;
        $controller = Yii::$app->controller->id;
        $module = !empty($this->module)?$this->module->id.'@@':"";
//        if(Yii::$app->user->can($module.$controller.'@'.$action)){
            return true;
//        }else{
//            throw new ForbiddenHttpException("没有访问权限！");
//        }
    }

}