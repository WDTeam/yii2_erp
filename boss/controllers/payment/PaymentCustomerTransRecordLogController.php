<?php

namespace boss\controllers\payment;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * PaymentCustomerTransRecordLogController implements the CRUD actions for PaymentCustomerTransRecordLog model.
 */
class PaymentCustomerTransRecordLogController extends BaseAuthController
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
     * Lists all PaymentCustomerTransRecordLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PaymentCustomerTransRecordLog::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaymentCustomerTransRecordLog model.
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
     * Creates a new PaymentCustomerTransRecordLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed

    public function actionCreate()
     * {
     * $model = new PaymentCustomerTransRecordLog;
     *
     * if ($model->load(Yii::$app->request->post()) && $model->save()) {
     * return $this->redirect(['view', 'id' => $model->id]);
     * } else {
     * return $this->render('create', [
     * 'model' => $model,
     * ]);
     * }
     * }
     */
    /**
     * Updates an existing PaymentCustomerTransRecordLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed

    public function actionUpdate($id)
     * {
     * $model = $this->findModel($id);
     *
     * if ($model->load(Yii::$app->request->post()) && $model->save()) {
     * return $this->redirect(['view', 'id' => $model->id]);
     * } else {
     * return $this->render('update', [
     * 'model' => $model,
     * ]);
     * }
     * }
     */
    /**
     * Deletes an existing PaymentCustomerTransRecordLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed

    public function actionDelete($id)
     * {
     * $this->findModel($id)->delete();
     *
     * return $this->redirect(['index']);
     * }
     */
    /**
     * Finds the PaymentCustomerTransRecordLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentCustomerTransRecordLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentCustomerTransRecordLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
