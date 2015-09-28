<?php
/**
* 表头解析记录数据控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* --------------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-9-25
* @author: peak pan 
* @version:1.0
*/
namespace boss\controllers;

use Yii;
use common\models\FinanceHeader;
use boss\models\FinanceHeaderSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\FinanceOrderChannel;
use common\models\FinancePayChannel;
use boss\models\FinancePayChannelSearch;
use boss\models\FinanceOrderChannelSearch;


/**
 * FinanceHeaderController implements the CRUD actions for FinanceHeader model.
 */
class FinanceHeaderController extends BaseAuthController
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
        $ordewhere['finance_order_channel_is_lock']=1;
        $payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
        foreach ($payatainfo as $errt){
        	$tyd[]=$errt['id'];
        	$tydtui[]=$errt['finance_order_channel_name'];
        }
       $tyu= array_combine($tyd,$tydtui);
        //订单渠道数据
        $paydata= new FinancePayChannel;
        $payewhere['finance_pay_channel_is_lock']=1;
        $payewhere['is_del']=0;
        $ordedatainfo=$paydata::find()->where($payewhere)->asArray()->all();
        foreach ($ordedatainfo as $ordein){
        	$tydoed[]=$ordein['id'];
        	$tydoedname[]=$ordein['finance_pay_channel_name'];
        }
        $ordesite= array_combine($tydoed,$tydoedname);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        	'payatainfo' => $tyu,
        	'ordedatainfo' => $ordesite,	
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
       	$model->finance_uplod_url = UploadedFile::getInstance($model, 'finance_uplod_url');
       	if ($model->finance_uplod_url && $model->validate()) {
       		//if(!file_exists('data/upload/'.$uid))mkdir('data/upload/'.$uid);
       		$path='upload/';
       		if(!file_exists($path))mkdir($path);
       		$filename=time().'.'.$model->finance_uplod_url->extension;
       		if(!$model->finance_uplod_url->saveAs($path.$filename)){
       			return ["result"=>"Fail"];
       		}
       	}
       	
       	$filePath = $path.$filename;
       //	$filePath = './uploads/14430836465880.xls'; // 要读取的文件的路径
       	$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
       	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
       	header("Content-Type: text/html; charset=utf-8");
       	$model = new FinanceHeader;
       	$post = Yii::$app->request->post();
   
        $modelPay = new FinancePayChannelSearch;
        $modelesr= new FinanceOrderChannelSearch;
       	$nameorder=$post['FinanceHeader']['finance_order_channel_name'];
       	$ordername=$modelesr->searchfind(array('id'=>$nameorder),'finance_order_channel_name');
       	
       	 	$payname=$post['FinanceHeader']['finance_pay_channel_name'];
       		$paynameinfo=$modelPay->searchfind(array('id'=>$payname),'finance_pay_channel_name');
       	
       	$post['FinanceHeader']['finance_header_title'] = $post['FinanceHeader']['finance_header_title']?$post['FinanceHeader']['finance_header_title']:'美团的'; 	
       	
        $post['FinanceHeader']['finance_order_channel_id'] =$post['FinanceHeader']['finance_order_channel_name']?$post['FinanceHeader']['finance_order_channel_name']:'0';
        
       	 $post['FinanceHeader']['finance_order_channel_name'] =$ordername;
       	 
       	$post['FinanceHeader']['finance_pay_channel_id'] = $post['FinanceHeader']['finance_pay_channel_name']?$post['FinanceHeader']['finance_pay_channel_name']:'1';
       	$post['FinanceHeader']['finance_pay_channel_name'] = $paynameinfo;
       	$post['FinanceHeader']['create_time'] = time();
       	$post['FinanceHeader']['is_del'] =0;
       	$i=0;
       	foreach ($sheetData[1] as $value){
       		$post['FinanceHeader']['finance_header_key'] =$i;
       		$post['FinanceHeader']['finance_header_name'] = $value;
       		$_model = clone $model;
       		$_model->setAttributes($post['FinanceHeader']);
       		$_model->save();
       		$i++;
       		unset($post['FinanceHeader']['finance_header_name']);
       	}
       	return $this->redirect(['index']);
       }else{
       	//支付渠道数据
       	$ordedata= new FinanceOrderChannel;
       	$ordewhere['is_del']=0;
       	$payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
       	foreach ($payatainfo as $errt){
       		$tyd[]=$errt['id'];
       		$tydtui[]=$errt['finance_order_channel_name'];
       	}
       	$tyu= array_combine($tyd,$tydtui);
       	//订单渠道数据
       	$paydata= new FinancePayChannel;
       	$payewhere['is_del']=0;
       	$ordedatainfo=$paydata::find()->where($payewhere)->asArray()->all();
       	foreach ($ordedatainfo as $ordein){
       		$tydoed[]=$ordein['id'];
       		$tydoedname[]=$ordein['finance_pay_channel_name'];
       	}
       	$ordesite= array_combine($tydoed,$tydoedname);
       	return $this->render('create', [
       			'model' => $model,'ordeinfo' => $ordesite,'payinfo' => $tyu,
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
