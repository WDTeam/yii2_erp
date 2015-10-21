<?php

namespace boss\controllers;

use Yii;
use common\models\FinanceRefund;
use boss\models\FinanceRefundSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\FinanceOrderChannel;

/**
 * FinanceRefundController implements the CRUD actions for FinanceRefund model.
 */
class FinanceRefundController extends BaseAuthController
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
     * Lists all FinanceRefund models.
     * @return mixed
     */
    public function actionIndex()
    {
    	
    	//输出部分
    	$ordedata= new FinanceOrderChannel;
    	$ordewhere['is_del']='0';
    	$ordewhere['finance_order_channel_is_lock']=1;
    	$payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
    	foreach ($payatainfo as $errt){
    		$tyd[]=$errt['id'];
    		$tydtui[]=$errt['finance_order_channel_name'];
    	}
    	$tyu= array_combine($tyd,$tydtui);
    	
    	
        $searchModel = new FinanceRefundSearch;
        $searchModel->load(Yii::$app->request->getQueryParams());
        $searchModel->isstatus='4';
        $dataProvider = $searchModel->search();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        	'ordedat' => $tyu,
        		
        		
        		
        ]);
    }

    /**
     * Displays a single FinanceRefund model.
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
     * Creates a new FinanceRefund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceRefund;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    
    /**
    * 开始退款
    * @date: 2015-10-21
    * @author: peak pan
    * @return:
    **/
    
    public function actionRefund()
    {
    	    $requestModel = Yii::$app->request->get();
    		$model=FinanceRefund::findOne($requestModel['id']);
    		if($requestModel['edit']=='baksite'){
    	    //退款
    		$model->finance_pop_order_pay_status='4';
    		}else{	
    		$model->finance_pop_order_pay_status='2';
    		}
    		$model->save();
    		return $this->redirect(['index', 'id' =>$requestModel['oid']]);
    }
    
    
    
    
    
    
    
    
    /**
     * Updates an existing FinanceRefund model.
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
     * Deletes an existing FinanceRefund model.
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
     * Finds the FinanceRefund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceRefund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceRefund::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
