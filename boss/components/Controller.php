<?php
namespace boss\components;

use yii;
use yii\web\ForbiddenHttpException;
class Controller extends \yii\web\Controller
{
    /**
     * 判断有没有授权项目，并已授权，如果有则运行权限管理
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        $action = Yii::$app->controller->action->id;
        $controller = Yii::$app->controller->id;
        $name = $controller.$action;
        $auth = Yii::$app->authManager;
        if($auth->getPermission($name) && \Yii::$app->user->can($name)){
            return true;
        }else{
           throw new ForbiddenHttpException("没有访问权限！");
        }
    }

}