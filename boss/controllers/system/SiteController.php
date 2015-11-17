<?php
namespace boss\controllers\system;

use Yii;
use yii\filters\AccessControl;
use boss\models\system\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Controller;
use boss\components\RbacHelper;

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

        if(\Yii::$app->user->identity->isMiNiBoss()){
        	return $this->render('indexshop');
        }else {
        	return $this->render('index');
        }
        
    }

    public function actionIndexshop()
    {
    	if(\Yii::$app->user->isGuest){
    		$this->redirect(array('login'));
    	}
    	
    	$date['countorder']=345;//已经接单
    	$date['countworker']=28;//阿姨
    	$date['countgoodreputation']=443;//好评
    	$date['counttask']=231;// 任务
    	$date['userinfo']='datainfo';//基本资料
    	$date['dbbasemsg']='datainfo';//系统通知
    	
    	
    	return $this->render('indexshop', [
			'date' => $date,
		]);
	
	
		return $this->render('indexshop');
    		
    }
    
    
    
    
    public function actionLogin()
    {
    	
    	
        $this->layout = 'guest';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
        	
        	//echo  '11111';exit;
            return $this->goBack();
        } else {
//             $model->username = 'admin';
//             $model->password = 'qwe1234';
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
