<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationGoods;
//use boss\models\Operation\OperationPriceStrategy;
use boss\models\Operation\OperationTag;
use boss\models\Operation\OperationCategory;
use boss\models\Operation\OperationSpec;
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
        return $this->render('view', ['model' => $model]);
}
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
            $model->operation_price_strategy_id = $post['OperationGoods']['operation_price_strategy_id'];
            
            
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

            $tags = array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationGoods']['operation_tags']))));
            OperationTag::setTagInfo($tags);
            
            $model->operation_tags = serialize($tags);
            
            $model->created_at = time();
            $model->updated_at = time();
            
            if($model->save()){
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
        $priceStrategies = OperationPriceStrategy::getAllStrategy();
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if(!empty($model->operation_tags)){
            $model->operation_tags = implode(';', unserialize($model->operation_tags));
        }
        if ($model->load($post)) {
            $model->operation_category_id = end($post['OperationGoods']['operation_category_ids']);
            $model->operation_category_name = OperationCategory::getCategoryName($model->operation_category_id);
            $model->operation_category_ids = implode(',', $post['OperationGoods']['operation_category_ids']);
            $model->operation_price_strategy_id = $post['OperationGoods']['operation_price_strategy_id'];
            
            
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

            $tags = array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationGoods']['operation_tags']))));
            OperationTag::setTagInfo($tags);
            $model->operation_tags = serialize($tags);
            
            $model->updated_at = time();
            
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'priceStrategies' => $priceStrategies,
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
