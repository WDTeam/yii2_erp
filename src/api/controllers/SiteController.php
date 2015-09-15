<?php
namespace api\controllers;

use Yii;
use api\components\Controller;

use core\models\search\UserInfoSearch;

/**
 * Site controller
 */
class SiteController extends Controller
{


    public function actionIndex()
    {
        echo 'hello world';die;
        $search = new UserInfoSearch();
        return $search->search(\Yii::$app->request->queryParams);
    }



}
