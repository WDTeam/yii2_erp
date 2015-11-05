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
        $params = Yii::$app->request->post();//getQueryParams();

        if (isset($params['fields']) && $params['fields'] == '0') {
            $params['fields'] = 'province_name';
            $params['keyword'] = '';
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'ShopDistrictModel' => $ShopDistrictModel,
            'params' => $params,
        ]);
    }
    
    public function actionOpencity(){
        $citylist = OperationCity::getCityOnlineInfoList();
        foreach((array)$citylist as $key => $value){
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
        if(empty($post)){
            $model = new OperationCity;
            //$citylist = OperationCity::getOnlineCityList(2); //未开通的城市列表
            $citylist = OperationCity::getCityList(); //城市列表
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
            return $this->redirect(['addgoods', 'city_id' => $city_id]);
        }
    }

    
    /**
     * 添加服务
     * @param $city_id
     */
    public function actionAddgoods($city_id, $cityAddGoods = ''){

        //echo '<pre>';
        //print_r($post);die;
        
        $post = Yii::$app->request->post();
        if(empty($post)) {
            $categoryinfo = OperationCategory::getCategoryList();
            $categorylist = array();
            foreach ((array)$categoryinfo as $key => $value) {
                $categorylist[$value['id'] . '-' . $value['operation_category_name']] = $value['operation_category_name'];
            }
            $city_name = OperationCity::getCityName($city_id);
            return $this->render('addgoods', [
                'cityAddGoods' => $cityAddGoods,
                'city_id' => $city_id,
                'city_name' => $city_name,
                'categorylist' => $categorylist,
            ]);
        }else{
            if(empty($post['categorygoods'])){
                return $this->redirect(['addgoods', 'city_id' => $city_id, 'cityAddGoods' => $cityAddGoods]);
            }
            $categorylist = $post['categorylist'];  //服务品类
            $categorygoods = explode('-', $post['categorygoods']); //服务
            $goods_id = $categorygoods[0];
            return $this->redirect(['settinggoodsinfo', 'city_id' => $city_id, 'goods_id' => $goods_id, 'cityAddGoods' => $cityAddGoods]);
        }
    }

    /**
     * 设置服务并开通该城市
     * @param $city_id
     * @param $goods_id
     * @return string
     */
    public function actionSettinggoodsinfo($city_id, $goods_id, $cityAddGoods = ''){
        $this->releaseGoods();die;
        $post = Yii::$app->request->post();

        //echo '<pre>';
        //print_r($post);die;
        $goodsInfo = OperationGoods::getGoodsInfo($goods_id);
        if(empty($post)) {
            $shopdistrict = OperationShopDistrict::getCityShopDistrictList($city_id);
            $shopdistrictinfo = [];
            foreach ((array)$shopdistrict as $key => $value) {
                $shopdistrictinfo[$value['id']] = $value['operation_shop_district_name'];
            }
            $city_name = OperationCity::getCityName($city_id);
            $shopdistrictinfoall = [];
            $goodsshopdistrictinfo = [];
            $citys = OperationShopDistrictGoods::getCityShopDistrictGoodsInfo($city_id, $goods_id);
            if($cityAddGoods == 'editGoods' || !empty($citys)){
                $cityshopdistrictgoodsinfo = OperationShopDistrictGoods::getCityShopDistrictGoodsInfo($city_id, $goods_id);

                foreach((array)$cityshopdistrictgoodsinfo as $key => $value){
                    /**查找该商品上线的数据**/
                    if($value['operation_shop_district_goods_status'] == 1){
                        $shopdistrictinfoall[] = $value['operation_shop_district_id'];
                        $goodsshopdistrictinfo[$value['operation_shop_district_id']] = $value;
                    }
                }
            }
            return $this->render('settinggoodsinfo', [
                'city_id' => $city_id,
                'city_name' => $city_name,
                'goodsInfo' => $goodsInfo,
                'cityAddGoods' => $cityAddGoods,
                'shopdistrictinfo' => $shopdistrictinfo,
                'shopdistrictinfoall' => $shopdistrictinfoall,
                'goodsshopdistrictinfo' => $goodsshopdistrictinfo,
            ]);
        }else{
            if(empty($post['shopdistrict'])){
                return $this->redirect(['settinggoodsinfo', 'city_id' => $city_id, 'goods_id' => $goods_id, 'cityAddGoods' => $cityAddGoods]);
            }
            $shopdistrict = $post['shopdistrict'];
            $shopdistrictGoods = $post['goodsinfo'];
            $citys = OperationShopDistrictGoods::getCityShopDistrictGoodsInfo($city_id, $goods_id);
            if($cityAddGoods == 'editGoods' || (!empty($citys))){
                OperationShopDistrictGoods::updateShopDistrictGoods($city_id, $goods_id, $shopdistrict, $goodsInfo, $shopdistrictGoods);
            }else {
                OperationShopDistrictGoods::insertShopDistrictGoods($city_id, $goods_id, $shopdistrict, $goodsInfo, $shopdistrictGoods);
                /** 开通城市 **/
                OperationCity::setoperation_city_is_online($city_id);
            }
            return $this->redirect(['opencity']);
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
            $addCityGoods = $cache->get('addCityGoods'); //查看当前操作是否为城市添加商品
            $editCityGoods = $cache->get('editCityGoods'); //查看当前操作是否为城市编辑商品
            $categorylistall = $cache->get("categorylist");  //服务品类
            $releasecity_info = $cache->get("releasecity_info");   //城市信息
            $city_id = $cache->get('city_id');
            $city_name = $cache->get('city_name');
            return $this->render('categoryshop', [
                    'categorylist' => $categorylist,
                    'categorylistall' => $categorylistall,
                    'addCityGoods' => $addCityGoods,
                    'releasecity_info' => $releasecity_info,
                    'editCityGoods' => $editCityGoods,
                    'city_id' => $city_id,
                    'city_name' => $city_name,
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
            return $this->redirect(['getcityshopdistrict']);
        }
    }
    
    /**
     * 添加城市下边的商品
     */
    public function actionCategoryshopadd($city_id){
        $cache = Yii::$app->cache;
        $city_name = OperationCity::getCityName($city_id);
        $cache->set('city_id', $city_id);
        $cache->set('city_name', $city_name);
        $cache->set("releasecity_info", $city_id.'-'.$city_name);
        $cache->set('addCityGoods', 'success');
        //如果是添加商品就将编辑商品的状态删除
        $cache->delete('editCityGoods');
        return $this->redirect(['categoryshop']);
    }
    
    /**
     * 编辑城市下边的商品
     */
    public function actionCategoryshopedit($city_id, $goods_id){
        $cache = Yii::$app->cache;
        $city_name = OperationCity::getCityName($city_id);
        $cache->set("releasecity_info", $city_id.'-'.$city_name);
        $cache->set('editCityGoods', 'success');
        //如果是编辑商品就将添加商品的状态删除
        $cache->delete('addCityGoods');
        /**服务品类**/
        $goodsinfo = OperationShopDistrictGoods::getCityShopDistrictGoodsInfo($city_id, $goods_id);
        $categorylist[] = $goodsinfo[0]['operation_category_id'].'-'.$goodsinfo[0]['operation_category_name'];
        $cache->set("categorylist", $categorylist);
        /**服务品类**/
        
        /**编辑服务详细 **/
        $categorygoods = $goodsinfo[0]['operation_goods_id'].'-'.$goodsinfo[0]['operation_shop_district_goods_name'];
        $cache->set("categorygoods", $categorygoods);
        /**编辑服务详细 **/
        
        /**服务详情**/
        $goodscontent = OperationGoods::getGoodsInfo($goods_id);
        $cache->set("goodsinfo".$goods_id, $goodscontent);
        /**服务详情**/
        
        /**选中商圈**/
        foreach((array)$goodsinfo as $key => $value){
            $shopdistrict[] = $value['operation_shop_district_id'].'-'.$value['operation_shop_district_name'];  //设置选中的商圈
            $goods_info['goodids'][] = $value['operation_goods_id'];
            $goods_info['goodnames'][] = $value['operation_shop_district_goods_name'];
            $goods_info['operation_goods_market_price'][] = $value['operation_shop_district_goods_market_price'];
            $goods_info['operation_goods_price'][] = $value['operation_shop_district_goods_price'];
            $goods_info['operation_goods_lowest_consume'][] = $value['operation_shop_district_goods_lowest_consume'];
            $goods_info['operation_goods_start_time'][] = $value['operation_shop_district_goods_start_time'];
            $goods_info['operation_goods_end_time'][] = $value['operation_shop_district_goods_end_time'];
            
            $shopdistrictgoods['shopdistrictid'][] = $value['operation_shop_district_id'];
            $shopdistrictgoods['operation_goods_market_price'][$value['operation_shop_district_id']] = $value['operation_shop_district_goods_market_price'];
            $shopdistrictgoods['operation_goods_price'][$value['operation_shop_district_id']] = $value['operation_shop_district_goods_price'];
            $shopdistrictgoods['operation_goods_lowest_consume'][$value['operation_shop_district_id']] = $value['operation_shop_district_goods_lowest_consume'];
            $shopdistrictgoods['operation_goods_start_time'][$value['operation_shop_district_id']] = $value['operation_shop_district_goods_start_time'];
            $shopdistrictgoods['operation_goods_end_time'][$value['operation_shop_district_id']] = $value['operation_shop_district_goods_end_time'];
        }
        $cache->set("shopdistrict", $shopdistrict);
        
        /**选中商圈服务详细**/
        
        $cache->set('settinggoodsinfo', $goods_info);
        $cache->set('settinghopdistrictgoods', $shopdistrictgoods);
        /**选中商圈服务详细**/
        
        return $this->redirect(['getcityshopdistrict']);
    }


    /**
     * ajax品类下边的商品
     * @return type
     */
    public function actionGetcategorygoods(){
        $categoryid = Yii::$app->request->post('categoryid');
        $city_id = Yii::$app->request->post('city_id');
        $categorygoods = OperationGoods::getCategoryGoodsInfo($categoryid, $city_id);
        $categorygoodsall = [];
        return $this->renderAjax('categorygoods', [
            'categoryid' => $categoryid,
            'categorygoods' => $categorygoods,
            'categorygoodsall' => $categorygoodsall,
        ]);
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
                
                $addCityGoods = $cache->get('addCityGoods'); //查看当前操作是否为城市添加商品
                $editCityGoods = $cache->get('editCityGoods'); //查看当前操作是否为城市编辑商品
                $releasecity_info = $cache->get("releasecity_info");   //城市信息
                $city_id = $cache->get('city_id');
                $city_name = $cache->get('city_name');
                return $this->render('shopdistrict', [
                    'shopdistrict' => $shopdistrictinfo,
                    'shopdistrictall' => $shopdistrictall,
                    'addCityGoods' => $addCityGoods,
                    'releasecity_info' => $releasecity_info,
                    'editCityGoods' => $editCityGoods,
                    'city_id' => $city_id,
                    'city_name' => $city_name,
                ]);
            }else{
                $shopdistrict = $post['shopdistrict'];
                $cache->set("shopdistrict", $shopdistrict);
                return $this->redirect(['settinggoods']);
            }
        }else{
            return $this->redirect(['release']);
        }
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
            $settinghopdistrictgoods = $cache->get('settinghopdistrictgoods');
            $shopdistrictall = $cache->get("shopdistrict");
            $addCityGoods = $cache->get('addCityGoods'); //查看当前操作是否为城市添加商品
            $editCityGoods = $cache->get('editCityGoods'); //查看当前操作是否为城市编辑商品
            $releasecity_info = $cache->get("releasecity_info");   //城市信息
            $city_id = $cache->get('city_id');
            $city_name = $cache->get('city_name');
            return $this->render('settinggoods', [
                'goodslist' => $goodslist,
                'settinggoodsinfo' => $settinggoodsinfo,
                'settinghopdistrictgoods' => $settinghopdistrictgoods,
                'shopdistrictall' => $shopdistrictall,
                'addCityGoods' => $addCityGoods,
                'releasecity_info' => $releasecity_info,
                'editCityGoods' => $editCityGoods,
                'city_id' => $city_id,
                'city_name' => $city_name,
            ]);
        }else{
            $cache->set('settinggoodsinfo', $post['goodinfo']);
            $cache->set('settinghopdistrictgoods', $post['shopdistrictgoods']);
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
        //商圈商品价格设置
        $settinghopdistrictgoods = $cache->get('settinghopdistrictgoods');

        if(empty($post)){
            $addCityGoods = $cache->get('addCityGoods'); //查看当前操作是否为城市添加商品
            $editCityGoods = $cache->get('editCityGoods'); //查看当前操作是否为城市编辑商品
            $releasecity_info = $cache->get("releasecity_info");   //城市信息
            $city_id = $cache->get('city_id');
            $city_name = $cache->get('city_name');
            return $this->render('releaseconfirm', [
                        'cityname' => $cityname,
                        'shopdistrictinfo' => $shopdistrictinfo,
                        'settinghopdistrictgoods' => $settinghopdistrictgoods,
                        'categorylist' => $categorylist,
                        'goodsinfo' => $goodsinfo,
                        'addCityGoods' => $addCityGoods,
                        'releasecity_info' => $releasecity_info,
                        'editCityGoods' => $editCityGoods,
                        'city_id' => $city_id,
                        'city_name' => $city_name,
                    ]);
        }else{
            /** 商品属性**/
            $goodsids = $goodsinfo['goodids'];
            foreach((array)$goodsids as $key => $value){
                $goodsinfo['goodscontent'][$value] = $cache->get('goodsinfo'.$value);
            }
            /** 商品属性**/
            /**  插入商圈商品信息 **/
            OperationShopDistrictGoods::handleReleaseCity($cityinfo, $shopdistrictinfo, $goodsinfo, $settinghopdistrictgoods);
            /**  插入商圈商品信息 **/
            
            $this->delSettingCityCaches();
            
            $addCityGoods = $cache->get('addCityGoods'); //查看当前操作是否为城市添加商品
            $editCityGoods = $cache->get('editCityGoods'); //查看当前操作是否为城市编辑商品
            if($addCityGoods != 'success' && $editCityGoods != 'success'){
                /**城市设为开通城市**/
                OperationCity::setoperation_city_is_online($cityid);
            }
            
            return $this->redirect(['index']);
        }
    }

    /**
     * 发布商品
     */
    public function releaseGoods()
    {
        $city_id = 110100;
        $city_name = OperationCity::getCityName($city_id);
        $arr = [
            //'operation_city_id' => 110100,
            'online' => [
                0 => [
                    'operation_category_id' => '1',
                    'operation_category_name' => 'one',
                    'items' => [
                        0 => [
                            'operation_goods_id' => 1,
                            'operation_shop_district_goods_price' => 12,
                            'operation_shop_district_goods_market_price' => 10,
                            'operation_shop_district_goods_lowest_consume_num' => 5,
                            'operation_spec_strategy_unit' => '个数',
                            'operation_shop_district_id' => 1,
                            'operation_shop_district_name' => '商圈1',
                        ],
                        1 => [
                            'operation_goods_id' => 2,
                            'operation_shop_district_goods_price' => 13,
                            'operation_shop_district_goods_market_price' => 10,
                            'operation_shop_district_goods_lowest_consume_num' => 5,
                            'operation_spec_strategy_unit' => '个数',
                            'operation_shop_district_id' => 2,
                            'operation_shop_district_name' => '商圈2',
                        ],
                        2 => [
                            'operation_goods_id' => 3,
                            'operation_shop_district_goods_price' => 15,
                            'operation_shop_district_goods_market_price' => 11,
                            'operation_shop_district_goods_lowest_consume_num' => 6,
                            'operation_spec_strategy_unit' => '个数',
                            'operation_shop_district_id' => 3,
                            'operation_shop_district_name' => '商圈3',
                        ]
                    ]
                ],
                1 => [
                    'operation_category_id' => '2',
                    'operation_category_name' => 'two',
                    'items' => [
                        0 => [
                            'operation_goods_id' => 4,
                            'operation_shop_district_goods_price' => 19,
                            'operation_shop_district_goods_market_price' => 12,
                            'operation_shop_district_goods_lowest_consume_num' => 7,
                            'operation_spec_strategy_unit' => '个数',
                            'operation_shop_district_id' => 4,
                            'operation_shop_district_name' => '商圈4',
                        ],
                        1 => [
                            'operation_goods_id' => 5,
                            'operation_shop_district_goods_price' => 20,
                            'operation_shop_district_goods_market_price' => 13,
                            'operation_shop_district_goods_lowest_consume_num' => 8,
                            'operation_spec_strategy_unit' => '个数',
                            'operation_shop_district_id' => 5,
                            'operation_shop_district_name' => '商圈5',
                        ],
                        2 => [
                            'operation_goods_id' => 6,
                            'operation_shop_district_goods_price' => 21,
                            'operation_shop_district_goods_market_price' => 14,
                            'operation_shop_district_goods_lowest_consume_num' => 9,
                            'operation_spec_strategy_unit' => '个数',
                            'operation_shop_district_id' => 6,
                            'operation_shop_district_name' => '商圈6',
                        ]
                    ]
                ]
            ]
        ];

        foreach ($arr['online'] as $key => $value) {
            foreach ($value['items'] as $k => $v) {
                $model = new OperationShopDistrictGoods();

                //城市数据
                $model->operation_city_id = $city_id;
                $model->operation_city_name = $city_name;

                //商圈数据
                $model->operation_shop_district_id = $v['operation_shop_district_id'];
                $model->operation_shop_district_name = $v['operation_shop_district_name'];

                //服务类型数据
                $model->operation_category_id = $value['operation_category_id'];
                $model->operation_category_name = $value['operation_category_name'];

                //服务项目数据
                $model->operation_goods_id = $v['operation_goods_id'];
                $model->operation_shop_district_goods_price = $v['operation_shop_district_goods_price'];
                $model->operation_shop_district_goods_market_price = $v['operation_shop_district_goods_market_price'];
                $model->operation_shop_district_goods_lowest_consume_num = $v['operation_shop_district_goods_lowest_consume_num'];

                //服务项目规格数据
                $model->operation_spec_strategy_unit = $v['operation_spec_strategy_unit'];

                $model->insert();
            }
        }
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
