<?php
/**
* 日订单报表管理
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-3
* @author: peak pan 
* @version:1.0
*/

namespace boss\controllers\finance;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use core\models\order\OrderSearch;

class FinanceOfficeCountController extends Controller
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
    * 日派单查询
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionIndexoffice()
    {
    	$searchModel= new OrderSearch;
    	
    	$searchModel->load(Yii::$app->request->getQueryParams());
    	
    	$dataProvider = $searchModel->searchpoplist();
    	
    	
    	//var_dump($dataProvider);exit;
    	
    	
        //通过账期id查找渠道id
        return $this->render('indexoffice', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,	
        ]);
    }


    
    
    
    
    
    
}
