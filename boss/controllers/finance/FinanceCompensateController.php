<?php

namespace boss\controllers\finance;

use Yii;
use common\models\FinanceCompensate;
use core\models\search\FinanceCompensate as FinanceCompensateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FinanceCompensateController implements the CRUD actions for FinanceCompensate model.
 */
class FinanceCompensateController extends Controller
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
     * Lists all FinanceCompensate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceCompensateSearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->load($requestParams);
        $dataProvider = $searchModel->search($requestParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    /**
     * Lists all FinanceCompensate models.
     * @return mixed
     */
    public function actionFinanceConfirmIndex()
    {
        $searchModel = new FinanceCompensateSearch;
        $requestParams = Yii::$app->request->getQueryParams();
        if(isset($requestParams['worker_tel'])){
            $searchModel->worker_id = $requestParams['worker_tel'];
        }
        $searchModel->finance_compensate_status = FinanceCompensateSearch::FINANCE_COMPENSATE_REVIEW_INIT;
        $searchModel->load($requestParams);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('financeConfirmIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
     public function actionReviewFailedReason(){
        $searchModel = new FinanceCompensateSearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->id = $requestParams['id'];
        return $this->renderAjax('reviewFailedReason', [
            'model' => $searchModel,
        ]);
    }

    /**
     * Displays a single FinanceCompensate model.
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
     * 记录审核结果
     * @param type $id
     * @return type
     */
    public function actionReview($id,$is_ok){
        $model = $this->findModel($id);
        $requestParams = Yii::$app->request->getQueryParams();
        if(isset($requestParams['comment'])){
            $model->comment = $requestParams['comment'];
        }
        if($is_ok == 1){
            $model->finance_compensate_status = FinanceCompensateSearch::FINANCE_COMPENSATE_REVIEW_PASSED;
        }else{
            $model->finance_compensate_status = FinanceCompensateSearch::FINANCE_COMPENSATE_REVIEW_FAILED;
        }
        $model->save();
        return $this->redirect(['finance-confirm-index']);
    }

    /**
     * Creates a new FinanceCompensate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceCompensate;
        if ($model->load(Yii::$app->request->post())) {
            $model->finance_complaint_id = 1111;
            $model->worker_tel = '13810998888';
            $model->worker_id = 33;
            $model->worker_name = '丁阿姨';
            $model->customer_id = 55;
            $model->customer_name = '肖先生';
            $model->finance_compensate_proposer = '客服A';
            $model->created_at = time();
            $model->save();
            return $this->redirect(['finance-confirm-index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceCompensate model.
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
     * Deletes an existing FinanceCompensate model.
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
     * Finds the FinanceCompensate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceCompensate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceCompensate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
