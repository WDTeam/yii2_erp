<?php

namespace boss\controllers\operation;

use boss\components\UploadFile;
use boss\components\BaseAuthController;
use boss\models\operation\OperationArea;
use boss\models\operation\OperationCity;
use boss\models\operation\OperationCitySearch;
use boss\models\operation\OperationShopDistrict;
use boss\models\operation\OperationShopDistrictGoods;
use boss\models\operation\OperationCategory;
use boss\models\operation\OperationGoods;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationCityController implements the CRUD actions for OperationCity model.
 */
class OperationCityController extends BaseAuthController
{
    public $addCityGoods;
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
        $ShopDistrictModel = new OperationShopDistrict();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'ShopDistrictModel' => $ShopDistrictModel,
        ]);
    }
    
    /**
     * 上线城市列表
     */
    public function actionOpencity(){
        $citylist = OperationCity::getCityOnlineInfoList();

        foreach ((array)$citylist as $key => $value) {
            $city_id = $value['city_id'];
            $citygoodsList = OperationShopDistrictGoods::getCityShopDistrictGoodsListArray($city_id);
            foreach((array)$citygoodsList as $k => $v){
                $citygoodsList[$k]['openshodistrictnum'] = OperationShopDistrictGoods::getCityGoodsOpenShopDistrictNum($city_id, $v['operation_goods_id']);
            }
            $citylist[$key]['citygoodsList'] = $citygoodsList;
        }
        return $this->render('opencity', [
            'citylist' => $citylist,
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
        
        $p = Yii::$app->request->post();
        if(!empty($p) && isset($p['OperationCity']['city_id'])){
            $province = OperationArea::getOneFromId($p['OperationCity']['province_id']);
            $city = OperationArea::getOneFromId($p['OperationCity']['city_id']);
            if(empty($p['OperationCity']['city_id'])){
                \Yii::$app->getSession()->setFlash('default','请选择城市！');
                return $this->redirect(['create']);
            }
            $p['OperationCity']['province_name'] = $province->area_name;
            $p['OperationCity']['city_name'] = $city->area_name;
        }
        if ($model->load($p)) {
            if(OperationCity::getCityInfo($model->city_id)){
                \Yii::$app->getSession()->setFlash('default','该城市已开通过！');
                return $this->redirect(['create']);
            }

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
     *
     * @return type
     */
    public function actionRelease(){
        $post = Yii::$app->request->post();
        if(empty($post)){
            $model = new OperationCity;
            //$citylist = OperationCity::getOnlineCityList(2); //未开通的城市列表
            //城市列表
            $citylist = OperationCity::getCityList(); 
            return $this->render('Release', [
                'model' => $model,
                'citylist' => $citylist,
            ]);
        }else{

            if(empty($post['OperationCity']['city_name'])){
                return $this->redirect(['release']);
            }
            $cityinfo = explode('-', $post['OperationCity']['city_name']);
            $city_id = $cityinfo[0];
            $city_name = $cityinfo[1];
            return $this->redirect(['addgoods', 'city_id' => $city_id, 'action' => 'create']);
        }
    }

    
    /**
     * 添加和更新服务项目
     *
     * @param $city_id
     */
    public function actionAddgoods($city_id, $goods_id = '', $action = '')
    {

        $post = Yii::$app->request->post();

        //获取服务类型数据
        $categoryinfo = OperationCategory::getCategoryList();
        $categorylist = array();
        $goods = array();

        //获取服务项目数据
        foreach ((array)$categoryinfo as $key => $value) {
            $categorylist[$value['id']] = $value['operation_category_name'];
            $goods[$value['id']] = OperationGoods::find()
                ->where(['operation_category_id' => $value['id']])
                ->asArray()
                ->all();
        }

        //获取对应城市商圈数据
        $shopdistrictinfo = [];
        $shopdistrict = OperationShopDistrict::getCityShopDistrictList($city_id);
        foreach ((array)$shopdistrict as $key => $value) {
            $shopdistrictinfo[$value['id']] = $value['operation_shop_district_name'];
        }

        $city_name = OperationCity::getCityName($city_id);

        //添加服务项目
        if(empty($post) && isset($action) && $action == 'create') {

            return $this->render('addgoods', [
                'action' => $action,
                'city_id' => $city_id,
                'city_name' => $city_name,
                'categorylist' => $categorylist,
                'goods' => $goods,
                'shopdistrictinfo' => $shopdistrictinfo,
            ]);

        //更新服务项目
        } elseif (isset($goods_id) && $goods_id != 0 && isset($action) && $action = 'editGoods') {

            //编辑的商品具体信息
            $districtgoodsinfo = [];
            $districtgoodslist = OperationShopDistrictGoods::getDistrictGoodsInfo($goods_id, $city_id);

            //处理为前端页面容易使用的格式
            foreach ($districtgoodslist as $keys => $values) {
                $districtgoodsinfo['operation_goods_id'] = $values['operation_goods_id'];
                $districtgoodsinfo['operation_category_id'] = $values['operation_category_id'];
                $districtgoodsinfo['operation_shop_district_goods_price'] = $values['operation_shop_district_goods_price'];
                $districtgoodsinfo['operation_shop_district_goods_price'] = $values['operation_shop_district_goods_price'];
                $districtgoodsinfo['operation_shop_district_goods_market_price'] = $values['operation_shop_district_goods_market_price'] ? $values['operation_shop_district_goods_market_price'] : '';
                $districtgoodsinfo['operation_shop_district_goods_lowest_consume_num'] = $values['operation_shop_district_goods_lowest_consume_num'] ? $values['operation_shop_district_goods_lowest_consume_num'] : '';
                $districtgoodsinfo['operation_shop_district_id'][] = $values['operation_shop_district_id'];
            }

            return $this->render('settinggoodsinfo', [
                'action' => $action,
                'city_id' => $city_id,
                'city_name' => $city_name,
                'categorylist' => $categorylist,
                'goods' => $goods,
                'shopdistrictinfo' => $shopdistrictinfo,
                'districtgoodsinfo' => $districtgoodsinfo,
            ]);
        } else {
            return $this->redirect([
                'operation/operation-shop-district-goods/index',
            ]);
        }
    }

    /**
     * 设置服务项目并开通该城市和编辑服务项目共用
     *
     * @param  integer   $city_id     上线的城市编号
     * @param  integer   $goods_id    服务项目编号
     * @param  string    $action      表明动作
     * @return view
     */
    public function actionSettinggoodsinfo($city_id = '', $goods_id = '', $action = ''){

        $post = Yii::$app->request->post();
        $goodsInfo = OperationGoods::getGoodsInfo($goods_id);

        if (!isset($post) || empty($post)) {
            return $this->redirect(['operation/operation-shop-district-goods/index']);
        } else {
            if ($city_id == '') {
                $city_id = $post['city_id'];
            }

            $user_action = ['online', 'edit'];

            //用户操作只能是上线或是编辑
            if (in_array($action, $user_action)) {
                OperationShopDistrictGoods::saveOnlineCity($post, $action);

                //设置开通城市状态
                OperationCity::setoperation_city_is_online($city_id);
            }
            return $this->redirect(['operation/operation-shop-district-goods/index']);
        }
    }
    
    /**
     * 下线城市下的服务项目
     */
    public function actionOfflineCity($city_id, $goods_id)
    {
        if (!isset($city_id) || $city_id == '' || !isset($goods_id) || $goods_id == '') {
            return $this->redirect(['operation/operation-shop-district-goods/index']);
        }

        $operation_shop_district_goods_status = 2;
        OperationShopDistrictGoods::updateShopDistrictGoodsStatus($goods_id, $city_id, $operation_shop_district_goods_status);
 
        //如果城市下没有上线的服务项目，城市状态设置为下线
        $district_nums = OperationShopDistrictGoods::getCityGoodsOnlineNum($city_id);
        if ($district_nums == 0) {
            OperationCity::setOperationCityIsOffline($city_id);
        }
        return $this->redirect(['operation/operation-shop-district-goods/index']);
    }

    private function delSettingCityCaches(){
        $cache = Yii::$app->cache;
        /** 删除缓存中的城市相关信息 **/
            $cache->delete('releasecity_info');
            $cache->delete('shopdistrict');
            $cache->delete('categorylist');
            $cache->delete('settinggoodsinfo');
            $cache->delete('settinghopdistrictgoods');
            $cache->delete('addCityGoods');
            $cache->delete('editCityGoods');
            $cache->delete('city_id');
            $cache->delete('city_name');
        /** 删除缓存中的城市相关信息 **/
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
