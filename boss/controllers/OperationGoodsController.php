<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationGoods;
//use boss\models\Operation\OperationPriceStrategy;
use boss\models\Operation\OperationTag;
use boss\models\Operation\OperationCategory;
use boss\models\Operation\OperationSpec;
use boss\models\Operation\OperationSpecGoods;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;

/**
 * OperationGoodsController implements the CRUD actions for OperationGoods model.
 */
class OperationGoodsController extends Controller
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
//        $priceStrategies = OperationPriceStrategy::getAllStrategy();
        $OperationSpec = OperationSpec::getSpecList();

        $model = new OperationGoods;
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->operation_category_id = end($post['OperationGoods']['operation_category_ids']);
            $model->operation_category_name = OperationCategory::getCategoryName($model->operation_category_id);
            $model->operation_category_ids = implode(',', $post['OperationGoods']['operation_category_ids']);
//            $model->operation_price_strategy_id = $post['OperationGoods']['operation_price_strategy_id'];
            
            /** 添加app图片和pc图片 **/
            $appFiles = array(
                'operation_goods_app_homepage_max_ico',
                'operation_goods_app_homepage_min_ico',
                'operation_goods_app_type_min_ico',
                'operation_goods_app_order_min_ico',
            );
            $model->operation_goods_app_ico = serialize($this->handleGoodsImgs($model, $appFiles));
            $pcFiles = array(
                'operation_goods_pc_homepage_max_ico',
                'operation_goods_pc_more_max_ico',
                'operation_goods_pc_submit_order_min_ico',
            );
            $model->operation_goods_pc_ico = serialize($this->handleGoodsImgs($model, $pcFiles));
            /** 添加app图片和pc图片 **/
            /** 添加个性标签 **/
            $tags = array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationGoods']['operation_tags']))));
            OperationTag::setTagInfo($tags);
            $model->operation_tags = serialize($tags);
            /** 添加个性标签 **/
            
            $model->created_at = time();
            $model->updated_at = time();
            
            if($model->save()){
                /** 插入规格 **/
                $specdata = array();
                $specdata = $post['OperationGoods']['specinfo'];
                $specdata['operation_goods_id'] = $model->id;  //商品编号
                $specdata['operation_goods_name'] = $post['OperationGoods']['operation_goods_name']; //商品名称
                
                $specdata['operation_spec_id'] = $post['OperationGoods']['operation_spec_info']; //规格编号
                $specdata['operation_spec_name'] = $post['OperationGoods']['operation_spec_name']; //规格名称
                OperationSpecGoods::setSpecGoods($specdata);
                /** 插入规格 **/
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
//                'priceStrategies' => $priceStrategies,
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
//        $priceStrategies = OperationPriceStrategy::getAllStrategy();
        $OperationSpec = OperationSpec::getSpecList();
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if(!empty($model->operation_tags)){
            $model->operation_tags = implode(';', unserialize($model->operation_tags));
        }
        if ($model->load($post)) {
            $model->operation_category_id = end($post['OperationGoods']['operation_category_ids']);
            $model->operation_category_name = OperationCategory::getCategoryName($model->operation_category_id);
            $model->operation_category_ids = implode(',', $post['OperationGoods']['operation_category_ids']);
//            $model->operation_price_strategy_id = $post['OperationGoods']['operation_price_strategy_id'];
            
            /** 添加app图片和pc图片 **/
            $appFiles = array(
                'operation_goods_app_homepage_max_ico',
                'operation_goods_app_homepage_min_ico',
                'operation_goods_app_type_min_ico',
                'operation_goods_app_order_min_ico',
            );
            $model->operation_goods_app_ico = serialize($this->handleGoodsImgs($model, $appFiles));
            $pcFiles = array(
                'operation_goods_pc_homepage_max_ico',
                'operation_goods_pc_more_max_ico',
                'operation_goods_pc_submit_order_min_ico',
            );
            $model->operation_goods_pc_ico = serialize($this->handleGoodsImgs($model, $pcFiles));
            /** 添加app图片和pc图片 **/
            
            /** 添加个性标签 **/
            $tags = array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationGoods']['operation_tags']))));
            OperationTag::setTagInfo($tags);
            $model->operation_tags = serialize($tags);
            /** 添加个性标签 **/
            
            $model->updated_at = time();
            
            if($model->save()){
                /** 插入规格 **/
                    $specdata = array();
                    $specdata = $post['OperationGoods']['specinfo'];
                    $specdata['operation_goods_id'] = $model->id;  //商品编号
                    $specdata['operation_goods_name'] = $post['OperationGoods']['operation_goods_name']; //商品名称

                    $specdata['operation_spec_id'] = $post['OperationGoods']['operation_spec_info']; //规格编号
                    $specdata['operation_spec_name'] = $post['OperationGoods']['operation_spec_name']; //规格名称
                    OperationSpecGoods::setSpecGoods($specdata);
                /** 插入规格 **/
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            if(!empty($model->operation_spec_info)){
                $d = unserialize($model->operation_spec_info);
                $model->operation_spec_info = $d['id'];
            }

            $specvalues = $this->actionHandlespec($id);
            return $this->render('update', [
                'model' => $model,
                'specvalues' => $specvalues,
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
