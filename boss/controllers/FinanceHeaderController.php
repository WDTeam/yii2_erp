<?php

namespace boss\controllers;

use Yii;
use common\models\FinanceHeader;
use boss\models\FinanceHeaderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\FinanceOrderChannel;
use common\models\FinancePayChannel;



/**
 * FinanceHeaderController implements the CRUD actions for FinanceHeader model.
 */
class FinanceHeaderController extends Controller
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
     * Lists all FinanceHeader models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceHeaderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		//支付渠道数据
        $ordedata= new FinanceOrderChannel;

        
        $ordewhere['is_del']=0;
        $ordedatainfo=$ordedata::find()->where($ordewhere)->all();
        
        //var_dump($ordedatainfo);exit;
        //订单渠道数据
        $paydata= new FinancePayChannel;
        $paydata=11;
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single FinanceHeader model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
        	
        	
        
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new FinanceHeader model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	
       $model = new FinanceHeader;
       if(Yii::$app->request->isPost) {
       	$filePath = './uploads/14430836465880.xls'; // 要读取的文件的路径
       	$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
       	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
       	header("Content-Type: text/html; charset=utf-8");
       	$model = new FinanceHeader;
       	$post = Yii::$app->request->post();
       	
        $post['FinanceHeader']['finance_order_channel_id'] =$post['FinanceHeader']['finance_order_channel_id']?$post['FinanceHeader']['finance_order_channel_id']:'1';
       	 $post['FinanceHeader']['finance_order_channel_name'] = $post['FinanceHeader']['finance_order_channel_name']?$post['FinanceHeader']['finance_order_channel_name']:'支付宝';
       	$post['FinanceHeader']['finance_pay_channel_id'] = $post['FinanceHeader']['finance_pay_channel_id']?$post['FinanceHeader']['finance_pay_channel_id']:'1';
       	$post['FinanceHeader']['finance_pay_channel_name'] = $post['FinanceHeader']['finance_pay_channel_name']?$post['FinanceHeader']['finance_pay_channel_name']:'美团';
       	$post['FinanceHeader']['create_time'] = time();
       	$post['FinanceHeader']['is_del'] =0;
       	 
       	foreach ($sheetData[1] as $value){
       		$post['FinanceHeader']['finance_header_name'] = $value;
       		$_model = clone $model;
       		$_model->setAttributes($post['FinanceHeader']);
       		$_model->save();
       		unset($post['FinanceHeader']['finance_header_name']);
       	}
       	
       	
       	//return $this->redirect(['view', 'id' => $model->id]);
       }else{
       	
       	return $this->render('create', [
       			'model' => $model,
       			]);
       	
       }
       
       
       
    }

    /**
     * Updates an existing FinanceHeader model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceHeader model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceHeader model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceHeader the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceHeader::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
