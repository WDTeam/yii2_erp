<?php
/**
* 账期管理
* @date: 2015-10-13
* @author: peak pan
* @return:
**/
namespace boss\controllers\finance;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use dbbase\models\finance\FinanceRecordLog;
use dbbase\models\finance\FinancePopOrder;
use boss\models\finance\FinanceRecordLogSearch;
use boss\components\BaseAuthController;

class FinanceRecordLogController extends BaseAuthController
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
     * Lists all FinanceRecordLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceRecordLogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //支付渠道数据
       $paychanne=\core\models\operation\OperationPayChannel::getpaychannellist('all');
       
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' =>  $searchModel,
        	'odrinfo' => $paychanne
        		
        ]);
    }

    /**
     * Displays a single FinanceRecordLog model.
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
     * Creates a new FinanceRecordLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceRecordLog;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceRecordLog model.
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
     * Deletes an existing FinanceRecordLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        //删除对账记录表
       $poporder= new FinancePopOrder;
       FinancePopOrder::deleteAll(['finance_record_log_id'=>$id]);
       return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceRecordLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceRecordLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceRecordLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
