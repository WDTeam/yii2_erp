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
namespace boss\controllers\finance;

use Yii;
use dbbase\models\finance\FinanceHeader;
use boss\models\finance\FinanceHeaderSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
<<<<<<< HEAD
use crazyfd\qiniu\Qiniu;
=======
use dbbase\models\finance\FinanceOrderChannel;
use dbbase\models\finance\FinancePayChannel;
use boss\models\finance\FinancePayChannelSearch;
use boss\models\finance\FinanceOrderChannelSearch;
>>>>>>> e78322a7599fb747470fe71762148e1a120f38fc

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
    	
    	//获取下单渠道
    	$tyu= \core\models\operation\OperationOrderChannel::getorderchannellist('all');
    	//获取支付渠道
    	$ordesite=\core\models\operation\OperationPayChannel::getpaychannellist('all');
    
        $searchModel = new FinanceHeaderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
        
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
    			return $this->redirect(['index', 'id' => $model->id]);
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
       	//Yii::$app->params['uploadpath']
       	if(true){
       		//开启七牛上传，文件存储在七牛
       		$data = \Yii::$app->request->post();
       		$model->load($data);
       		$file = UploadedFile::getInstance($model, 'finance_uplod_url');
       		 
       		if(!isset($file->baseName)){
       			\Yii::$app->getSession()->setFlash('default','请上传对表头！');
       			return $this->redirect(['index']);
       		}
       		$filenamesitename=$file->baseName;
       		if($file){
       			$path = \Yii::$app->imageHelper->uploadFile($file->tempName);
       			$model->finance_uplod_url = $path['key'];
       		}
       		$qiniuurl=\Yii::$app->imageHelper->getLink($path['key']);
       		$filePath=$file->tempName;
       	}else{
       		//文件存储在本地
       		$model->finance_uplod_url = UploadedFile::getInstance($model, 'finance_uplod_url');
       		if ($model->finance_uplod_url && $model->validate()) {
       			$path='upload/';
       			if(!file_exists($path))mkdir($path);
       			$filenamesitename=$model->finance_uplod_url->baseName;
       			$filename=time().'.'.$model->finance_uplod_url->extension;
       			$qiniuurl='0';
       			if(!$model->finance_uplod_url->saveAs($path.$filename)){
       				return ["result"=>"Fail"];
       			}
       		}
       		$filePath = $path.$filename;
       	}
       	
       
       	$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
       	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
       	header("Content-Type: text/html; charset=utf-8");
       	$model = new FinanceHeader;
       	$post = Yii::$app->request->post();
       	$ordername=\core\models\operation\OperationOrderChannel::get_post_name($post['FinanceHeader']['finance_order_channel_name']);
       	$paynameinfo=\core\models\operation\OperationPayChannel::get_post_name($post['FinanceHeader']['finance_pay_channel_name']);
       	
       	$post['FinanceHeader']['finance_header_title'] = $post['FinanceHeader']['finance_header_title']?trim($post['FinanceHeader']['finance_header_title']):'美团的'; 	
       	
        $post['FinanceHeader']['finance_order_channel_id'] =$post['FinanceHeader']['finance_order_channel_name']?$post['FinanceHeader']['finance_order_channel_name']:'0';
        
       	 $post['FinanceHeader']['finance_order_channel_name'] =$ordername;
       	 
       	$post['FinanceHeader']['finance_pay_channel_id'] = $post['FinanceHeader']['finance_pay_channel_name']?$post['FinanceHeader']['finance_pay_channel_name']:'1';
       	$post['FinanceHeader']['finance_pay_channel_name'] = $paynameinfo;
       	$post['FinanceHeader']['create_time'] = time();
       	$post['FinanceHeader']['is_del'] =0;
       	$i=0;
       	foreach (array_filter($sheetData[1]) as $value){
       		//if(!$value){ return $this->redirect(['index']); }
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
       //获取下单渠道
    	$tyu= \core\models\operation\OperationOrderChannel::getorderchannellist('all');
    	//获取支付渠道
    	$ordesite= \core\models\operation\OperationPayChannel::getpaychannellist('all');
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
