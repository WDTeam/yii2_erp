<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationCity;
use boss\models\Operation\OperationCitySearch;
use boss\models\Operation\OperationShopDistrict;
use boss\models\Operation\OperationShopDistrictGoods;
use boss\models\Operation\OperationCategory;
use boss\models\Operation\OperationGoods;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use boss\components\AreaCascade;
use boss\models\Operation\OperationArea;
use boss\components\UploadFile;

/**
 * OperationCityController implements the CRUD actions for OperationCity model.
 */
class OperationCityController extends BaseAuthController
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
     * Lists all OperationCity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OperationCitySearch();
        $params = Yii::$app->request->post();//getQueryParams();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'params' => $params,
        ]);
    }

    /**
     * Displays a single OperationCity model.
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
     * Creates a new OperationCity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationCity;
//        
        $p = Yii::$app->request->post();
        if(!empty($p)){
            $province = OperationArea::getOneFromId($p['OperationCity']['province_id']);
            $city = OperationArea::getOneFromId($p['OperationCity']['city_id']);
            if(empty($p['OperationCity']['city_id'])){
                throw new NotFoundHttpException('请选择城市');
            }
            $p['OperationCity']['province_name'] = $province->area_name;
            $p['OperationCity']['city_name'] = $city->area_name;
        }
        if ($model->load($p)) {
            if(OperationCity::getCityInfo($model->city_id)){
                throw new NotFoundHttpException('该城市已开通过');
            }
//            $path = UploadFile::widget(['fileInputName' => 'file']);
//            echo $path;exit;
//            print_r($_FILES);exit;
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationCity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OperationCity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    
    public function actionGoline($id){
        $model = $this->findModel($id);
        if($model->operation_city_is_online == '1'){
            $model->operation_city_is_online = '2';
        }else{
            $model->operation_city_is_online = '1';
        }
        $model->save();
        return $this->redirect(['index']);
    }
    
    public function actionGetArea(){
        $parent_id = Yii::$app->request->post('parent_id');
        if(!empty($parent_id)){
            $where = ['parent_id' => $parent_id];
            $data = OperationArea::getAllData($where);
            $areas = [];
            foreach ($data as $key => $area){
                $areas[$area->id] = $area->area_name;
            }
            $result = true;
        }else{
            $result = false;
            $areas = null;
        }
        return json_encode(['result' => $result, 'data' => $areas]);
    }
    
    /**
     * 城市列表
     * @return type
     */
    public function actionRelease(){
        $post = Yii::$app->request->post();
        $cache = Yii::$app->cache;
        if(empty($post)){
            $model = new OperationCity;
            $citylist = OperationCity::getOnlineCityList(2); //未开通的城市列表
            $model->city_name = $cache->get("releasecity_info");
            return $this->render('Release', [
                'model' => $model,
                'citylist' => $citylist,
            ]);
        }else{
            $city_name = $post['OperationCity']['city_name'];
            $cache->set("releasecity_info", $city_name);
            $city_info = explode('-', $city_name);
            return $this->redirect(['getcityshopdistrict']);
        }
    }
    
    /**
     * 商圈列表
     * @param type $city_id
     * @return type
     */
    public function actionGetcityshopdistrict(){
        $cache = Yii::$app->cache;
        $releasecity_info = $cache->get("releasecity_info");
        $city_id = explode('-', $releasecity_info);
        $city_id = $city_id[0];
        if($releasecity_info){
            $post = Yii::$app->request->post();
            if(empty($post)){
                $shopdistrict = OperationShopDistrict::getCityShopDistrictList($city_id);
                $shopdistrictinfo = [];
                foreach((array)$shopdistrict as $key => $value){
                    $shopdistrictinfo[$value['id'].'-'.$value['operation_shop_district_name']] = $value['operation_shop_district_name'];
                }
                $shopdistrictall = $cache->get("shopdistrict");
                return $this->render('shopdistrict', [
                    'shopdistrict' => $shopdistrictinfo,
                    'shopdistrictall' => $shopdistrictall,
                ]);
            }else{
                $shopdistrict = $post['shopdistrict'];
                $cache->set("shopdistrict", $shopdistrict);
                return $this->redirect(['categoryshop']);
            }
        }else{
            return $this->redirect(['release']);
        }
    }
    
    /**
     * 选择品类与品类下边商品
     * @return type
     */
    public function actionCategoryshop(){
        $cache = Yii::$app->cache;
        $post = Yii::$app->request->post();
        if(empty($post)){
            $categoryinfo = OperationCategory::getCategoryList();
            $categorylist = array();
            foreach((array)$categoryinfo as $key => $value){
                $categorylist[$value['id'].'-'.$value['operation_category_name']] = $value['operation_category_name'];
            }
            $categorylistall = $cache->get("categorylist");
            return $this->render('categoryshop', [
                    'categorylist' => $categorylist,
                    'categorylistall' => $categorylistall,
                ]);
        }else{
            $categorylist = $post['categorylist'];
            $categorygoods = $post['categorygoods'];
            foreach((array)$categorygoods as $key => $value){
                $goodid = explode('-', $value);
                $goodid = $goodid[0];
                $goodscontent = OperationGoods::getGoodsInfo($goodid);
                $cache->set("goodsinfo".$goodid, $goodscontent);
            }
            $cache->set("categorylist", $categorylist);
            $cache->set("categorygoods", $categorygoods);
            return $this->redirect(['settinggoods']);
        }
    }
    
    /**
     * ajax品类下边的商品
     * @return type
     */
    public function actionGetcategorygoods(){
        $cache = Yii::$app->cache;
        $categoryid = Yii::$app->request->post('categoryid');
        $categorygoods = OperationGoods::getCategoryGoodsInfo($categoryid);
        $categorygoodsall = $cache->get("categorygoods");
        return $this->renderAjax('categorygoods', [
            'categoryid' => $categoryid,
            'categorygoods' => $categorygoods,
            'categorygoodsall' => $categorygoodsall,
        ]);
    }
    
    /**
     * 设置商品
     * @return type
     */
    public function actionSettinggoods(){
        $cache = Yii::$app->cache;
        $post = Yii::$app->request->post();
        if(empty($post)){
            $cache = Yii::$app->cache;
            $goodslist = $cache->get("categorygoods");
            $goodslist = OperationGoods::getGoodsList($goodslist);
            $settinggoodsinfo = $cache->get('settinggoodsinfo');
            return $this->render('settinggoods', [
                'goodslist' => $goodslist,
                'settinggoodsinfo' => $settinggoodsinfo,
            ]);
        }else{
            $cache->set('settinggoodsinfo', $post['goodinfo']);
            return $this->redirect(['releaseconfirm']);
        }
    }
    
    //开通城市确认页面
    public function actionReleaseconfirm(){
        $post = Yii::$app->request->post();
        //开通城市
        $cache = Yii::$app->cache;
        $cityinfo = explode('-', $cache->get("releasecity_info"));
        $cityid = $cityinfo[0];
        $cityname = $cityinfo[1];

        //设置商圈
        $shopdistrictinfo = $cache->get("shopdistrict");
        
        //服务品类
        $categorylist = $cache->get("categorylist");
        //商品
        $goodsinfo = $cache->get('settinggoodsinfo');
        
        if(empty($post)){
            return $this->render('releaseconfirm', [
                        'cityname' => $cityname,
                        'shopdistrictinfo' => $shopdistrictinfo,
                        'categorylist' => $categorylist,
                        'goodsinfo' => $goodsinfo,
                    ]);
        }else{
            /** 商品属性**/
            $goodsids = $goodsinfo['goodids'];
            foreach((array)$goodsids as $key => $value){
                $goodsinfo['goodscontent'][$value] = $cache->get('goodsinfo'.$value);
            }
            /** 商品属性**/
            /**  插入商圈商品信息 **/
            OperationShopDistrictGoods::handleReleaseCity($cityinfo, $shopdistrictinfo, $goodsinfo);
            /**  插入商圈商品信息 **/
            
            /** 删除缓存中的城市相关信息 **/
            $cache->delete('releasecity_info');
            $cache->delete('shopdistrict');
            $cache->delete('categorylist');
            $cache->delete('settinggoodsinfo');
            /** 删除缓存中的城市相关信息 **/
            
            /**城市设为开通城市**/
            OperationCity::setoperation_city_is_online($cityid);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the OperationCity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationCity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationCity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
