<?php
namespace boss\components;

use yii;
use yii\web\ForbiddenHttpException;
class BaseAuthController extends \yii\web\Controller
{
    /**
     * 判断有没有授权项目，并已授权，如果有则运行权限管理
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if(\Yii::$app->user->isGuest && $action->id!='login'){
            $this->redirect(['system/site/login']);
        }
        $name = $this->id.'/'.$action->id;
        $auth = Yii::$app->authManager;
        $perm = $auth->getPermission($name);
        if(empty($perm) || \Yii::$app->user->can($name, \Yii::$app->request->getQueryParams())){
            return true;
        }else{
            throw new ForbiddenHttpException("没有访问权限！");
        }
    }

}