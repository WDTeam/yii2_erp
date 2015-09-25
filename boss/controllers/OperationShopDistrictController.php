<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationShopDistrict;
use boss\models\Operation\OperationCity;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * OperationShopDistrictController implements the CRUD actions for OperationShopDistrict model.
 */
class OperationShopDistrictController extends Controller
{
    public $city_id; //城市id
    public function behaviors(){
        $this->city_id = Yii::$app->request->get('city_id');
        if(!empty($this->city_id)){
            setcookie('city_id', $this->city_id, time()+86400);
        }else{
            $this->city_id = $_COOKIE['city_id'];
            if(empty($this->city_id)){
                return $this->redirect(['operation-city/index']);
            }
        }
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
     * Lists all OperationShopDistrict models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationShopDistrict::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationShopDistrict model.
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
     * Creates a new OperationShopDistrict model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationShopDistrict;
        $post = Yii::$app->request->post();
        if(!empty($post)){
            $post['OperationShopDistrict']['operation_city_id'] = $this->city_id;
            $post['OperationShopDistrict']['operation_city_name'] = OperationCity::getCityName($this->city_id);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationShopDistrict model.
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

    public function actionGoline($id){
        $model = $this->findModel($id);
        if($model->operation_shop_district_status == '1'){
            $model->operation_shop_district_status = '2';
        }else{
            $model->operation_shop_district_status = '1';
        }
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing OperationShopDistrict model.
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
     * Finds the OperationShopDistrict model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationShopDistrict the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationShopDistrict::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
