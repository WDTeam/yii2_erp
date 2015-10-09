<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationShopDistrict;
use boss\models\Operation\OperationShopDistrictCoordinate;
use boss\models\Operation\OperationCity;
use boss\models\Operation\OperationArea;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * OperationShopDistrictController implements the CRUD actions for OperationShopDistrict model.
 */
class OperationShopDistrictController extends BaseAuthController
{
    public $city_id; //城市id
    public function behaviors(){
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
    }
    

    /**
     * Lists all OperationShopDistrict models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationShopDistrict::find()->where(['operation_city_id' => $this->city_id]),
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
        $OperationShopDistrictCoordinate = new OperationShopDistrictCoordinate();
        $cityname = OperationCity::getCityName($this->city_id);
        $citymodel = OperationCity::getCityInfo($this->city_id);
        if(!empty($post)){
            $post['OperationShopDistrict']['operation_city_id'] = $this->city_id;
            $post['OperationShopDistrict']['operation_city_name'] = $cityname;
            $post['OperationShopDistrict']['created_at'] = time();
            $post['OperationShopDistrict']['updated_at'] = time();
            $areaInfo = explode('_', $post['OperationShopDistrict']['operation_area_id']);
            $area_id = $areaInfo[0];
            $area_name = $areaInfo[1];
            $post['OperationShopDistrict']['operation_area_id'] = $area_id;
            $post['OperationShopDistrict']['operation_area_name'] = $area_name;
        }
        if ($model->load($post) && $model->save()) {
            $coordinate = array();
            $coordinate['operation_shop_district_id'] = $model->id;
            $coordinate['operation_shop_district_name'] = $post['OperationShopDistrict']['operation_shop_district_name'];
            $coordinate['operation_city_id'] = $this->city_id;
            $coordinate['operation_city_name'] = $cityname;
            $coordinate['operation_shop_district_coordinate_start_longitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_start_longitude'];
            $coordinate['operation_shop_district_coordinate_start_latitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_start_latitude'];
            $coordinate['operation_shop_district_coordinate_end_longitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_end_longitude'];
            $coordinate['operation_shop_district_coordinate_end_latitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_end_latitude'];
            $coordinate['operation_area_id'] = $area_id;
            $coordinate['operation_area_name'] = $area_name;
            $coordinate['created_at'] = time();
            $coordinate['updated_at'] = time();
            OperationShopDistrictCoordinate::setShopDistrictCoordinate($coordinate);
//            return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            $areaList = OperationArea::getAreaList($this->city_id);
            $model->operation_area_id = $model->operation_area_id.'_'.$model->operation_area_name;
            return $this->render('create', [
                'model' => $model,
                'citymodel' => $citymodel,
                'areaList' => $areaList,
                'OperationShopDistrictCoordinate' => $OperationShopDistrictCoordinate,
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
        $post = Yii::$app->request->post();
        $OperationShopDistrictCoordinate = new OperationShopDistrictCoordinate();
        $cityname = OperationCity::getCityName($this->city_id);
        $citymodel = OperationCity::getCityInfo($this->city_id);
        if(!empty($post)){
            $post['OperationShopDistrict']['updated_at'] = time();
            $post['OperationShopDistrict']['operation_shop_district_status'] = 1;
            $areaInfo = explode('_', $post['OperationShopDistrict']['operation_area_id']);
            $area_id = $areaInfo[0];
            $area_name = $areaInfo[1];
            $post['OperationShopDistrict']['operation_area_id'] = $area_id;
            $post['OperationShopDistrict']['operation_area_name'] = $area_name;
        }
        if ($model->load($post) && $model->save()) {
            $coordinate = array();
            $coordinate['operation_city_id'] = $this->city_id;
            $coordinate['operation_city_name'] = $cityname;
            $coordinate['operation_shop_district_coordinate_start_longitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_start_longitude'];
            $coordinate['operation_shop_district_coordinate_start_latitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_start_latitude'];
            $coordinate['operation_shop_district_coordinate_end_longitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_end_longitude'];
            $coordinate['operation_shop_district_coordinate_end_latitude'] = $post['OperationShopDistrictCoordinate']['operation_shop_district_coordinate_end_latitude'];
            $coordinate['operation_area_id'] = $area_id;
            $coordinate['operation_area_name'] = $area_name;
            $coordinate['updated_at'] = time();
            OperationShopDistrictCoordinate::upShopDistrictCoordinate($coordinate, $model->id);
//            return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            $OperationShopDistrictCoordinateList = OperationShopDistrictCoordinate::getShopDistrictCoordinate($id);
            $OperationShopDistrictCoordinate->operation_shop_district_coordinate_start_longitude = $OperationShopDistrictCoordinateList['operation_shop_district_coordinate_start_longitude'];
            $OperationShopDistrictCoordinate->operation_shop_district_coordinate_start_latitude = $OperationShopDistrictCoordinateList['operation_shop_district_coordinate_start_latitude'];
            $OperationShopDistrictCoordinate->operation_shop_district_coordinate_end_longitude = $OperationShopDistrictCoordinateList['operation_shop_district_coordinate_end_longitude'];
            $OperationShopDistrictCoordinate->operation_shop_district_coordinate_end_latitude = $OperationShopDistrictCoordinateList['operation_shop_district_coordinate_end_latitude'];
            $areaList = OperationArea::getAreaList($this->city_id);
            $model->operation_area_id = $model->operation_area_id.'_'.$model->operation_area_name;
            return $this->render('update', [
                'model' => $model,
                'citymodel' => $citymodel,
                'areaList' => $areaList,
                'OperationShopDistrictCoordinate' => $OperationShopDistrictCoordinate,
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
