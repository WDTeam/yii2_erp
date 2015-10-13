<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationShopDistrictGoods;
use boss\models\Operation\OperationCity;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationShopDistrictGoodsController implements the CRUD actions for OperationShopDistrictGoods model.
 */
class OperationShopDistrictGoodsController extends Controller
{
    public $city_id; //城市id
    public $city_name; //城市名称
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
    
    public function init(){
        $this->city_id = Yii::$app->request->get('city_id');
        if(!empty($this->city_id)){
            setcookie('city_id', $this->city_id, time()+86400);
        }else{
            $this->city_id = $_COOKIE['city_id'];
            if(empty($this->city_id)){
                return $this->redirect(['operation-city/index']);
            }
        }
        $this->city_name = OperationCity::getCityName($this->city_id);
    }

    /**
     * Lists all OperationShopDistrictGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationShopDistrictGoods::getCityShopDistrictGoodsList($this->city_id),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'city_id' => $this->city_id,
            'city_name' => $this->city_name,
        ]);
    }

    /**
     * Displays a single OperationShopDistrictGoods model.
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
     * Creates a new OperationShopDistrictGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationShopDistrictGoods;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationShopDistrictGoods model.
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
     * Deletes an existing OperationShopDistrictGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        OperationShopDistrictGoods::setCityShopDistrictGoodsStatus($id, $this->city_id);
        return $this->redirect(['index']);
    }

    /**
     * Finds the OperationShopDistrictGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationShopDistrictGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationShopDistrictGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
