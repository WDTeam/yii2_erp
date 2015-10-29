<?php

namespace core\components;

use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\CompositeAuth;

class Controller extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //just provide the json format
        unset($behaviors['contentNegotiator']);
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::className(),
//            'auth' => '\restapi\models\User::findByUsernameAndPassword'
//        ];
        return $behaviors;
    }

}