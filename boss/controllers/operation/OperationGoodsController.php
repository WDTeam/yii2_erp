<?php

namespace boss\controllers\operation;

use boss\models\operation\OperationGoods;
use boss\models\operation\OperationTag;
use boss\models\operation\OperationCategory;
use boss\models\operation\OperationSpec;
use boss\models\operation\OperationSpecGoods;
use boss\models\operation\OperationShopDistrictGoods;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

use crazyfd\qiniu\Qiniu;

/**
 * OperationGoodsController implements the CRUD actions for OperationGoods model.
 */
class OperationGoodsController extends Controller
{
    static $jsondata = [
        'msg' => '',    // 提示消息 失败提示信息
        'status' => 0, //状态 0: 失败 1：成功
        'data' => '',  //数据 
        ];
    
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
     * 获取商品详情
     * @param type $city_id 城市id
     * @param type $shop_district 商圈id
     * @param type $goods_id 商品id
     */
    public static function getGoodsInfo($city_id = '', $shop_district = '', $goods_id = ''){
        if(empty($city_id) || empty($shop_district) || empty($goods_id)){
            self::$jsondata['msg'] = '参数传递有误';
            self::$jsondata['status'] = 0;
        }else{
            $goodsInfo = OperationShopDistrictGoods::getShopDistrictGoodsInfo($city_id, $shop_district, $goods_id);
            if(empty($goodsInfo)){
                self::$jsondata['msg'] = '该商品不存在';
                self::$jsondata['status'] = 0;
            }else{
                self::$jsondata['status'] = 1;
                self::$jsondata['data'] = $goodsInfo;
            }
        }
        return self::$jsondata;
    }
    
    /**
     * 获取商品列表
     * @param type $city_id 城市id
     * @param type $shop_district 商圈id
     * @param type $goods_id 商品id
     */
    public static function getGoodsList($city_id = '', $shop_district = ''){
        if(empty($city_id) || empty($shop_district)){
            self::$jsondata['msg'] = '参数传递有误';
            self::$jsondata['status'] = 0;
        }else{
            $goodsListInfo = OperationShopDistrictGoods::getShopDistrictGoodsList($city_id, $shop_district);
            if(empty($goodsListInfo)){
                self::$jsondata['msg'] = '该商圈下面没商品';
                self::$jsondata['status'] = 0;
            }else{
                self::$jsondata['status'] = 1;
                self::$jsondata['data'] = $goodsListInfo;
            }
        }
        return self::$jsondata;
    }
    
    /**
     * Lists all OperationGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationGoods::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationGoods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if(!empty($model->operation_tags)) {
                $model->operation_tags = implode('      ', unserialize($model->operation_tags));
            }
            unset($model->operation_goods_app_ico);
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * ajax规格信息
     * @return type
     */
    public function actionGetspecinfo(){
        $specinfo = OperationSpec::getSpecInfo(Yii::$app->request->post('spec_id'));
        $specinfo['operation_spec_values'] = unserialize($specinfo['operation_spec_values']);
        $specvalues = array();
        $specvalues['operation_spec_name'] = $specinfo['operation_spec_name']; //规格名称

        //增加货号 （为解决  暂时以 时间戳＋一个索引）
        $time = time();
        foreach((array)$specinfo['operation_spec_values'] as $key => $value){
            $specvalues['info'][$key]['operation_spec_goods_no'] = $time.$key+1;  //货号
            $specvalues['info'][$key]['operation_spec_value'] = $value;   //规格属性
            
            $specvalues['info'][$key]['operation_spec_goods_market_price'] = '';   //市场价格
            $specvalues['info'][$key]['operation_spec_goods_sell_price'] = '';   //销售价格
            $specvalues['info'][$key]['operation_spec_goods_cost_price'] = '';   //成本价格
            $specvalues['info'][$key]['operation_spec_goods_settlement_price'] = '';   //结算价格
            $specvalues['info'][$key]['operation_spec_goods_lowest_consume_number'] = 1;   //最低消费数量
            $specvalues['info'][$key]['operation_spec_strategy_unit'] = $specinfo['operation_spec_strategy_unit']; //计量单位

            $specvalues['info'][$key]['operation_spec_goods_commission_mode'] = '2';   //收取佣金方式（1: 百分比 2: 金额） 默认：金额
            $specvalues['info'][$key]['operation_spec_goods_commission'] = 0;   //佣金值
        }
        return $this->renderAjax('specinfo', [
            'specvalues' => $specvalues,
        ]);
    }
    
    /**
     * 设置商品规格数据
     */
    private function actionHandlespec($goods_id){
        $specGoods = OperationSpecGoods::getSpecGoods($goods_id);
        $specvalues = array();
        if(!empty($specGoods)){
            $specvalues['operation_spec_name'] = $specGoods[0]['operation_spec_name'];
            foreach((array)$specGoods as $key => $value){
                $specvalues['info'][$key]['operation_spec_goods_no'] = $value['operation_spec_goods_no'];  //货号
                $specvalues['info'][$key]['operation_spec_value'] = $value['operation_spec_value'];   //规格属性

                $specvalues['info'][$key]['operation_spec_goods_market_price'] = $value['operation_spec_goods_market_price'];   //市场价格
                $specvalues['info'][$key]['operation_spec_goods_sell_price'] = $value['operation_spec_goods_sell_price'];   //销售价格
                $specvalues['info'][$key]['operation_spec_goods_cost_price'] = $value['operation_spec_goods_cost_price'];   //成本价格
                $specvalues['info'][$key]['operation_spec_goods_settlement_price'] = $value['operation_spec_goods_settlement_price'];   //结算价格
                $specvalues['info'][$key]['operation_spec_goods_lowest_consume_number'] = $value['operation_spec_goods_lowest_consume_number'];   //最低消费数量
                $specvalues['info'][$key]['operation_spec_strategy_unit'] = $value['operation_spec_strategy_unit']; //计量单位

                $specvalues['info'][$key]['operation_spec_goods_commission_mode'] = $value['operation_spec_goods_commission_mode'];   //收取佣金方式（1: 百分比 2: 金额） 默认：金额
                $specvalues['info'][$key]['operation_spec_goods_commission'] = $value['operation_spec_goods_commission'];   //佣金值
            }
        }
        return $specvalues;
    }

    /**
     * Creates a new OperationGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $OperationSpec = OperationSpec::getSpecList();
        $model = new OperationGoods;
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $model->operation_category_ids = $model->operation_category_id;
            $model->operation_category_name = OperationCategory::getCategoryName($model->operation_category_id);
            
            /** 冗余计量单位 **/
            $model->operation_spec_info = $post['OperationGoods']['operation_spec_info'];
            $specinfo = OperationSpec::getSpecInfo($model->operation_spec_info);
            $model->operation_spec_strategy_unit = $specinfo['operation_spec_strategy_unit'];
            
            /** 添加商品图片 **/
            $model->uploadImgToQiniu('operation_goods_img');

            /** 添加个性标签 **/
            $tags = array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationGoods']['operation_tags']))));
            OperationTag::setTagInfo($tags);
            $model->operation_tags = serialize($tags);
            
            $model->created_at = time();
            $model->updated_at = time();
            
            if($model->save()){
                return $this->redirect(['/operation/operation-category']);
            }
        } else {
            $OperationCategory = [];

            $OperationCategorydata = OperationCategory::getCategoryList(0, '', ['id', 'operation_category_name']);
            foreach ((array)$OperationCategorydata as $key => $value) { 
                $OperationCategory[$value['id']] = $value['operation_category_name'];
            }

            if (empty($OperationCategory)) {
                \Yii::$app->getSession()->setFlash('default','还没有服务品类，请先创建服务品类！');
                return $this->redirect(['/operation/operation-category']);
            }

            return $this->render('create', [
                'model' => $model,
                'OperationCategory' => $OperationCategory,
                //'priceStrategies' => $priceStrategies,
                'OperationSpec' => $OperationSpec,
            ]);
        }
    }
    
    private function handleGoodsImgs($model, $files = array()){
        $qiniu = new Qiniu();
        $data = array();
        foreach((array)$files as $filekey => $filevalue){
            $fileinfo = UploadedFile::getInstance($model, $filevalue);
            if(!empty($fileinfo)){
                $key = time().mt_rand('1000', '9999').uniqid();
                $qiniu->uploadFile($fileinfo->tempName, $key);
                $data[$filevalue] = $qiniu->getLink($key);
            }
        }
        return $data;
    }

    /**
     * Updates an existing OperationGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $OperationSpec = OperationSpec::getSpecList();
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if(!empty($model->operation_tags)){
            $model->operation_tags = implode(';', unserialize($model->operation_tags));
        }
        if ($model->load($post)) {
            $model->operation_category_ids = $model->operation_category_id;
            $model->operation_category_name = OperationCategory::getCategoryName($model->operation_category_id);
            
            /** 冗余计量单位 **/
            $model->operation_spec_info = $post['OperationGoods']['operation_spec_info'];
            $specinfo = OperationSpec::getSpecInfo($model->operation_spec_info);
            $model->operation_spec_strategy_unit = $specinfo['operation_spec_strategy_unit'];
            
             /** 添加商品图片 **/
            $model->uploadImgToQiniu('operation_goods_img');

            /** 添加个性标签 **/
            $tags = array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationGoods']['operation_tags']))));
            OperationTag::setTagInfo($tags);
            $model->operation_tags = serialize($tags);
            
            $model->updated_at = time();
            
            if($model->save()){
                return $this->redirect(['/operation/operation-category']);
            }
        } else {
            $OperationCategorydata = OperationCategory::getCategoryList(0, '', ['id', 'operation_category_name']);
            foreach((array)$OperationCategorydata as $key => $value){ $OperationCategory[$value['id']] = $value['operation_category_name']; }
            
            return $this->render('update', [
                'model' => $model,
                'OperationCategory' => $OperationCategory,
//                'priceStrategies' => $priceStrategies,
                'OperationSpec' => $OperationSpec,
            ]);
        }
    }

    /**
     * Deletes an existing OperationGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = OperationShopDistrictGoods::getShopDistrictGoods($id);
        if ($result) {
            \Yii::$app->getSession()->setFlash('default','项目有商圈已经上线，不能删除！');
            return $this->redirect(['/operation/operation-category']);
        } else {

            //关联删除服务项目对应的商圈
            OperationShopDistrictGoods::delShopDistrict($id);
            $this->findModel($id)->delete();
        }

        //return $this->redirect(['index']);
        return $this->redirect(['/operation/operation-category']);
    }

    /**
     * Finds the OperationGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
