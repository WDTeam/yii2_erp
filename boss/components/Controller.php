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
        $name = $this->id.'/'.$action->id;
        $auth = Yii::$app->authManager;
        $pre = $auth->getPermission($name);
        if(empty($pre) || \Yii::$app->user->can($name)){
            return true;
        }else{
            throw new ForbiddenHttpException("没有访问权限！");
        }
    }

}