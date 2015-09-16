<?php

namespace frontend\controllers;

use Yii;
use frontend\models\User;
use frontend\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
   
    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInformation()
    {
        $user_id=\Yii::$app->user->identity->id;
        // 注意大小写，改
        $model= User::findOne($user_id);
        //$model = new User();
        $p = Yii::$app->request->post();
        if(!empty($p)){
            $p['User']['whatodo'] = serialize($p['User']['whatodo']);
        }
       if ($model->load($p) && $model->save()){
            return $this->redirect(['study/index', 'type'=>1]);
        } else {
            return $this->render('information', [
                'model' => $model,
            ]);
        }
    }
    
}
