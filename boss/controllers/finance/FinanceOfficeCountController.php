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
use dbbase\models\finance\FinancePopOrder;
use boss\models\finance\FinancePopOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use dbbase\models\finance\FinanceOrderChannel;
use dbbase\models\finance\FinanceHeader;
use boss\models\finance\FinanceRecordLogSearch;
use boss\models\finance\FinanceOrderChannelSearch;
use core\models\order\OrderSearch;
use core\models\payment\GeneralPaySearch;
use dbbase\models\finance\FinanceRecordLog;

use dbbase\models\finance\FinancePayChannel;


use core\models\order\Order;


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
