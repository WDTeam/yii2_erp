<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationAdvertPosition;
use yii\data\ActiveDataProvider;
use boss\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\models\Operation\OperationCity;

/**
 * OperationAdvertPositionController implements the CRUD actions for OperationAdvertPosition model.
 */
class OperationAdvertPositionController extends Controller
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
     * Lists all OperationAdvertPosition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationAdvertPosition::find(),
        ]);
        $citys = OperationCity::getOnlineCity();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'citys' => $citys,
        ]);
    }
    
    public function actionCityAdvertPosition(){
        $city_id = Yii::$app->request->post('city_id');
        if($city_id == 'all'){
            $dataProvider = new ActiveDataProvider([
                'query' => OperationAdvertPosition::find(),
            ]);
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => OperationAdvertPosition::find()->where(['operation_city_id' => $city_id]),
            ]);
        }
        return $this->renderPartial('city-advert-position', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationAdvertPosition model.
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
     * Creates a new OperationAdvertPosition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationAdvertPosition();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationAdvertPosition model.
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
     * Deletes an existing OperationAdvertPosition model.
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
     * Finds the OperationAdvertPosition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationAdvertPosition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationAdvertPosition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
