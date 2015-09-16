<?php

namespace core\modules\user\controllers;

use core\components\ActiveController;
use common\models\sq_ejiajie_v2\UserInfo;
use yii\data\ActiveDataProvider;
use core\models\search\UserInfoSearch;

class UserInfoController extends ActiveController
{
    public $modelClass = 'common\models\sq_ejiajie_v2\UserInfo';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actionViewByTelphone($telphone)
    {
        return UserInfo::findOne(['telphone'=>$telphone]);
    }

    public function actionList()
    {
        $search = new UserInfoSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }

}
