<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\models\operation\OperationShopDistrict;
use boss\models\operation\OperationShopDistrictCoordinate;
use boss\models\operation\OperationShopDistrictGoods;
use boss\models\operation\OperationCity;
use boss\models\operation\OperationArea;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * OperationShopDistrictController implements the CRUD actions for OperationShopDistrict model.
 */
class OperationShopDistrictController extends BaseAuthController
{
    static $jsondata = [
        'msg' => '',    // 提示消息 失败提示信息
        'status' => 0, //状态 0: 失败 1：成功
        'data' => '',  //数据 
        ];
    public $city_id; //城市id
    public $city_name; //城市名称
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
        if(isset($this->city_id)){
            setcookie('city_id', $this->city_id, time()+86400);
        }else{
            $this->city_id = $_COOKIE['city_id'];
            if(empty($this->city_id)){
                return $this->redirect(['/operation/operation-city/index']);
            }
        }
        $this->city_name = OperationCity::getCityName($this->city_id);
    }
    

    /**
     * Lists all OperationShopDistrict models.
     * @return mixed
     */
    public function actionIndex()
    {
        $city_id = $this->city_id;
        $city_name = $this->city_name;

        $model = new OperationShopDistrict();
        $districtModel = new OperationShopDistrictGoods();

        $dataProvider = new ActiveDataProvider([
            'query' => OperationShopDistrict::find()->where([
                'operation_city_id' => $city_id
            ]),
        ]);

        //批量上传
    	if(Yii::$app->request->isPost) {
    		if (Yii::$app->params['uploadpath']) {
    			$data = \Yii::$app->request->post();
    			$model->load($data);
    			$file = UploadedFile::getInstance($model, 'district_upload_url');
    			
                if (!isset($file->baseName)) {
                    \Yii::$app->getSession()->setFlash('default','请上传商圈数据！');
                    return $this->redirect(['index']);
                }

    			$filePath = $file->tempName;
                $district_str = file_get_contents($filePath);
                $district_arr = json_decode($district_str, true);

                if (!isset($district_arr) && !is_array($district_arr)) {
                    \Yii::$app->getSession()->setFlash('default','上传文件数据格式不正确！');
                    return $this->redirect(['index']);
                }
                OperationShopDistrict::saveBatchDistrictData($city_id, $city_name, $district_arr);
    		}
    	}

        return $this->render('index', [
            'model' => $model,
            'city_name' => $city_name,
            'city_id' => $city_id,
            'dataProvider' => $dataProvider,
            'districtModel' => $districtModel,
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
            return $this->render('view', [
                'model' => $model,
                //'city_id' => $this->city_id,
                'city_name' => $this->city_name
            ]);
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
        
        if (!empty($post)) {
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
            $coordinate['operation_city_id'] = $this->city_id;
            $coordinate['operation_city_name'] = $cityname;
            $coordinate['operation_shop_district_name'] = $post['OperationShopDistrict']['operation_shop_district_name'];
            $coordinate['operation_shop_district_coordinate_start_longitude'] = $post['operation_shop_district_coordinate_start_longitude'];
            $coordinate['operation_shop_district_coordinate_start_latitude'] = $post['operation_shop_district_coordinate_start_latitude'];
            $coordinate['operation_shop_district_coordinate_end_longitude'] = $post['operation_shop_district_coordinate_end_longitude'];
            $coordinate['operation_shop_district_coordinate_end_latitude'] = $post['operation_shop_district_coordinate_end_latitude'];
            $coordinate['operation_area_id'] = $area_id;
            $coordinate['operation_area_name'] = $area_name;

            OperationShopDistrictCoordinate::settingShopDistrictCoordinate($coordinate);
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);

        } else {
            $areaList = OperationArea::getAreaList($this->city_id);
            $model->operation_area_id = $model->operation_area_id.'_'.$model->operation_area_name;
            return $this->render('create', [
                'model' => $model,
                //'city_id' => $this->city_id,
                'city_name' => $this->city_name,
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

        if (!empty($post)) {
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
            $coordinate['operation_shop_district_name'] = $post['OperationShopDistrict']['operation_shop_district_name'];
            $coordinate['operation_shop_district_id'] = $id;

            $coordinate['operation_shop_district_coordinate_start_longitude'] = 
                isset($post['operation_shop_district_coordinate_start_longitude']) ? 
                $post['operation_shop_district_coordinate_start_longitude'] : ['']; 

            $coordinate['operation_shop_district_coordinate_start_latitude'] = 
                isset($post['operation_shop_district_coordinate_start_latitude']) ? 
                $post['operation_shop_district_coordinate_start_latitude'] : [''];

            $coordinate['operation_shop_district_coordinate_end_longitude'] = 
                isset($post['operation_shop_district_coordinate_end_longitude']) ? 
                $post['operation_shop_district_coordinate_end_longitude'] : [''];
            $coordinate['operation_shop_district_coordinate_end_latitude'] = 
                isset($post['operation_shop_district_coordinate_end_latitude']) ?
                $post['operation_shop_district_coordinate_end_latitude'] : [''];
            
            $coordinate['operation_area_id'] = $area_id;
            $coordinate['operation_area_name'] = $area_name;
            OperationShopDistrictCoordinate::settingShopDistrictCoordinate($coordinate);

            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            $OperationShopDistrictCoordinateList = OperationShopDistrictCoordinate::getShopDistrictCoordinate($id);
            $areaList = OperationArea::getAreaList($this->city_id);
            $model->operation_area_id = $model->operation_area_id.'_'.$model->operation_area_name;
            return $this->render('update', [
                'model' => $model,
                //'city_id' => $this->city_id,
                'city_name' => $this->city_name,
                'citymodel' => $citymodel,
                'areaList' => $areaList,
                'OperationShopDistrictCoordinateList' => $OperationShopDistrictCoordinateList,
                'OperationShopDistrictCoordinate' => $OperationShopDistrictCoordinate,
            ]);
        }
    }
    
    public function actionGoodslist($id){
        return $this->redirect(['/operation/operation-goods/index']);
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
     * 根据经纬度查询所属商圈，返回商圈id
     * @param type $longitude 经度
     * @param type $latitude 纬度
     */
    public static function getCoordinateShopDistrict($longitude = '', $latitude = ''){
        if(empty($longitude) || empty($latitude)){
            self::$jsondata['msg'] = '参数传递有误';
            self::$jsondata['status'] = 0;
        }else{
            $ShopDistrictInfo = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
            if(empty($ShopDistrictInfo)){
                self::$jsondata['msg'] = '商圈不存在';
                self::$jsondata['status'] = 0;
            }else{
                self::$jsondata['status'] = 1;
                self::$jsondata['data'] = $ShopDistrictInfo;
            }
        }
        return self::$jsondata;
    }
    
    /**
     * 获取城市商圈列表
     * @param type $city_id
     * @return type
     */
    public static function getCityShopDistrictList($city_id = ''){
        $ShopDistrictlist = OperationShopDistrict::getCityShopDistrictList($city_id);
        self::$jsondata['status'] = 1;
        self::$jsondata['data'] = $ShopDistrictlist;
        return self::$jsondata;
    }

    /**
     * Deletes an existing OperationShopDistrict model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (($model = OperationShopDistrict::findOne($id)) !== null) {
            OperationShopDistrictCoordinate::delCoordinateInfo($id);
            OperationShopDistrictGoods::delShopDistrictGoods($id);
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            return $this->redirect(['index']);
        }
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

    /**
     * 下拉选择组件AJAX获取sh专用
     */
    public function actionListToSelect2($city_id=null, $name=null)
    {
        $data = OperationShopDistrict::getCityShopDistrictList($city_id);
        return json_encode([
            'results'=>$data
        ]);
    }

}
