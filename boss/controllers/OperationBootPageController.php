<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationBootPage;
use boss\models\Operation\OperationCity;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationBootPageController implements the CRUD actions for OperationBootPage model.
 */
class OperationBootPageController extends Controller
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
     * Lists all OperationBootPage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationBootPage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationBootPage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OperationBootPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationBootPage();
        $post = Yii::$app->request->post();
        if($post){
            $post['OperationBootPage']['operation_boot_page_online_time'] = strtotime($post['OperationBootPage']['operation_boot_page_online_time']);
            $post['OperationBootPage']['operation_boot_page_offline_time'] = strtotime($post['OperationBootPage']['operation_boot_page_offline_time']);
            $post['OperationBootPage']['created_at'] = time();
            $post['OperationBootPage']['updated_at'] = time();
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'citylist' => OperationCity::find()->all(),
            ]);
        }
    }

    /**
     * Updates an existing OperationBootPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($post){
            $post['OperationBootPage']['operation_boot_page_online_time'] = strtotime($post['OperationBootPage']['operation_boot_page_online_time']);
            $post['OperationBootPage']['operation_boot_page_offline_time'] = strtotime($post['OperationBootPage']['operation_boot_page_offline_time']);
            $post['OperationBootPage']['updated_at'] = time();
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    }

    /**
     * Deletes an existing OperationBootPage model.
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
     * Finds the OperationBootPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationBootPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationBootPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
