<?php

namespace boss\controllers\shopmanager;

use Yii;
use core\models\shop\ShopManager;
use core\models\shop\ShopManagerSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\web\BadRequestHttpException;
use core\models\shop\ShopCustomeRelation;

/**
 * ShopManagerController implements the CRUD actions for ShopManager model.
 */
class ShopManagerController extends BaseAuthController
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
     * 判断当前用户能不能操作
     * @param unknown $id
     * @throws BadRequestHttpException
     */
    public function can($id)
    {
        $ids = \Yii::$app->user->identity->getShopManagerIds();
        if(\Yii::$app->user->identity->isNotAdmin() && !in_array($id, $ids)){
            throw new BadRequestHttpException('没有访问权限', 403);
        }
    }

    /**
     * Lists all ShopManager models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopManagerSearch;
        $query = Yii::$app->request->getQueryParams();
        if(\Yii::$app->user->identity->isNotAdmin()){
            $query['ids'] = \Yii::$app->user->identity->getShopManagerIds();
            $query['ids'] = empty($query['ids'])?[0]:$query['ids'];
        }
        $dataProvider = $searchModel->search($query);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single ShopManager model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->can($id);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'bl_photo_url');
            if($file){
                $path = \Yii::$app->imageHelper->uploadFile($file->tempName);
                $model->bl_photo_url = $path['key'];
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new ShopManager model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopManager;
        if (\Yii::$app->request->post()) {
        	
            $data = \Yii::$app->request->post();
            $model->load($data);
            $file = UploadedFile::getInstance($model, 'bl_photo_url');
            if($file){
                $path = \Yii::$app->imageHelper->uploadFile($file->tempName);
                $model->bl_photo_url = $path['key'];
            }
            
            
            if($model->save()){
                $ref = new ShopCustomeRelation();
                $ref->system_user_id = \Yii::$app->user->id;
                $ref->shop_manager_id = $model->id;
                $ref->stype = ShopCustomeRelation::TYPE_STYPE_SHOPMANAGER;
                $ref->save();
                
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShopManager model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->can($id);
        $model = $this->findModel($id);

        if (\Yii::$app->request->post()) {
            $data = \Yii::$app->request->post();
            $model->load($data);
            $file = UploadedFile::getInstance($model, 'bl_photo_url');
            if($file){
                $path = \Yii::$app->imageHelper->uploadFile($file->tempName);
                $model->bl_photo_url = $path['key'];
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ShopManager model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->can($id);
        
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ShopManager model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopManager the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopManager::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 通过名称获取列表
     * @param string $name
     * @param int $city_id 城市ID
     */
    public function actionSearchByName($name='', $city_id=null)
    {
        $query = ShopManager::find()
        ->select(['id', 'name'])
        ->andFilterWhere(['like', 'name', $name]);
        if(isset($city_id)){
            $query->andFilterWhere(['=', 'city_id', $city_id]);
        }
        if(\Yii::$app->user->identity->isMiNiBoss()){
            $query->andFilterWhere(['in', 'id', \Yii::$app->user->identity->getShopManagerIds()]);
        }
        $models = $query->limit(50)->all();
        echo Json::encode(['results'=>$models]);
    }
    /**
     * 加入黑名单
     */
    public function actionJoinBlacklist($id)
    {
        $this->can($id);
        
        $model = $this->findModel($id);
        if(\Yii::$app->request->isPost){
            $cause = Yii::$app->request->post('cause','');
            $model->joinBlacklist($cause);
            \Yii::$app->session->setFlash('default', '添加成功');
            return $this->redirect(['index']);
        }
        return $this->renderAjax('join_blacklist',[
            'model'=>$model
        ]);
        
    }
    /**
     * 解除黑名单
     */
    public function actionRemoveBlacklist($id)
    {
        $this->can($id);
        
        $cause = Yii::$app->request->get('cause','');
        $this->findModel($id)->removeBlacklist($cause);
        \Yii::$app->session->setFlash('default', '取消成功');
        return $this->redirect(['index']);
    }
}
