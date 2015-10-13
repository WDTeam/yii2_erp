<?php
namespace boss\controllers;

use Yii;
use yii\filters\AccessControl;
use boss\models\LoginForm;
use yii\filters\VerbFilter;
use yii\base\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            $this->redirect(array('login'));
        }
        return $this->render('index');
        
        
    }

    public function actionLogin()
    {
    	
    	
        $this->layout = 'guest';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->username = 'admin';
            $model->password = 'qwe1234';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
