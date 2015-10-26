<?php

namespace boss\controllers\operation;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;


use boss\models\operation\OperationSelectedService;

/**
 * OperationSelectedServiceController implements the CRUD actions for OperationGoods model.
 */
class OperationSelectedServiceController extends Controller
{
    static $jsondata = [
        'msg' => '',    // 提示消息 失败提示信息
        'status' => 0, //状态 0: 失败 1：成功 'data' => '',  //数据 
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
     * Lists all OperationSelectedService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationSelectedService::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
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
     * Creates a new opreationselectedservice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationSelectedService;
        $post = Yii::$app->request->post();

        if ($model->load($post)) {

            $model->created_at = time();

            if($model->save()){

                return $this->redirect(['/operation/operation-selected-service']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
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
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            
            $model->updated_at = time();
            
            if($model->save()){
                return $this->redirect(['/operation/operation-selected-service']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
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
     * Finds the OperationSelectedService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationSelectedService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationSelectedService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
