<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationAdvertContent;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\components\UploadFile;
use boss\models\Operation\OperationPlatform;
use boss\models\Operation\OperationPlatformVersion;
use boss\models\Operation\OperationCity;

/**
 * OperationAdvertContentController implements the CRUD actions for OperationAdvertContent model.
 */
class OperationAdvertContentController extends BaseAuthController
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
     * Lists all OperationAdvertContent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $platforms = OperationPlatform::find()->asArray()->all();
        foreach((array)$platforms as $key => $platform){
            $platforms[$key]['version_list'] = OperationPlatformVersion::find()->asArray()->where(['operation_platform_id' => $platform['id']])->all();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => OperationAdvertContent::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'platforms' => $platforms
        ]);
    }

    /**
     * Displays a single OperationAdvertContent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OperationAdvertContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationAdvertContent();
        $post = Yii::$app->request->post();
        if(!empty($post['old_operation_advert_picture'])){
            $old_operation_advert_picture = $post['old_operation_advert_picture'];
            unset($post['old_operation_advert_picture']);
        }
        if ($model->load($post)) {
            $model->operation_advert_start_time = strtotime($post['OperationAdvertContent']['operation_advert_start_time']);
            $model->operation_advert_end_time = strtotime($post['OperationAdvertContent']['operation_advert_end_time']);
            $model->operation_advert_online_time = strtotime($post['OperationAdvertContent']['operation_advert_online_time']);
            $model->operation_advert_offline_time = strtotime($post['OperationAdvertContent']['operation_advert_offline_time']);
            if(!empty($_FILES['operation_advert_picture']['tmp_name'])){
                $path = UploadFile::widget(['fileInputName' => 'operation_advert_picture']);
            }else{
                $path = '';
            }
            $model->operation_city_id = $post['OperationAdvertContent']['operation_city_id'];
            $city = OperationCity::find()->where(['city_id' => $model->operation_city_id])->one();
            $model->operation_city_name = $city->city_name;
            
            $model->operation_platform_id = $post['OperationAdvertContent']['operation_platform_id'];
            $platform = OperationPlatform::find()->where(['id' => $model->operation_platform_id])->one();
            $model->operation_platform_name = $platform->operation_platform_name;
            
            $model->operation_platform_version_id = $post['OperationAdvertContent']['operation_platform_version_id'];
            $version = OperationPlatformVersion::find()->where(['id' => $model->operation_platform_version_id])->one();
            $model->operation_platform_version_name = $version['operation_platform_version_name'];

            $model->operation_advert_picture = $path;
            $model->created_at = time();
            $model->updated_at = time();
            unset($model->old_operation_advert_picture);
            $model->save();
            return $this->redirect(['index']);
        } else {
            $citys = OperationCity::find()->where(['operation_city_is_online' => 1])->all();
            $cityList = ['选择城市'];
            foreach($citys as $city){
                $cityList[$city['city_id']] = $city['city_name'];
            }
            $platforms = OperationPlatform::find()->all();
            $platformList = ['选择平台'];
            foreach($platforms as $platform){
                $platformList[$platform['id']] = $platform['operation_platform_name'];
            }
            $versionList = ['选择版本'];
            return $this->render('create', [
                'model' => $model,
                'cityList' => $cityList,
                'platformList' => $platformList,
                'versionList' => $versionList,
            ]);
        }
    }

    /**
     * Updates an existing OperationAdvertContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if(!empty($post['old_operation_advert_picture'])){
            $old_operation_advert_picture = $post['old_operation_advert_picture'];
            unset($post['old_operation_advert_picture']);
        }
        if ($model->load($post)) {
            $model->operation_advert_start_time = strtotime($post['OperationAdvertContent']['operation_advert_start_time']);
            $model->operation_advert_end_time = strtotime($post['OperationAdvertContent']['operation_advert_end_time']);
            $model->operation_advert_online_time = strtotime($post['OperationAdvertContent']['operation_advert_online_time']);
            $model->operation_advert_offline_time = strtotime($post['OperationAdvertContent']['operation_advert_offline_time']);
            if(!empty($_FILES['operation_advert_picture']['tmp_name'])){
                $path = UploadFile::widget(['fileInputName' => 'operation_advert_picture']);
            }else{
                $path = $old_operation_advert_picture;
            }
            $model->operation_city_id = $post['OperationAdvertContent']['operation_city_id'];
            $city = OperationCity::find()->where(['city_id' => $model->operation_city_id])->one();
            $model->operation_city_name = $city->city_name;
            
            $model->operation_platform_id = $post['OperationAdvertContent']['operation_platform_id'];
            $platform = OperationPlatform::find()->where(['id' => $model->operation_platform_id])->one();
            $model->operation_platform_name = $platform->operation_platform_name;
            
            $model->operation_platform_version_id = $post['OperationAdvertContent']['operation_platform_version_id'];
            $version = OperationPlatformVersion::find()->where(['id' => $model->operation_platform_version_id])->one();
            $model->operation_platform_version_name = $version['operation_platform_version_name'];
            $model->operation_advert_picture = $path;
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            $citys = OperationCity::find()->where(['operation_city_is_online' => 1])->all();
            $cityList = ['选择城市'];
            foreach($citys as $city){
                $cityList[$city['city_id']] = $city['city_name'];
            }
            $platforms = OperationPlatform::find()->all();
            $platformList = ['选择平台'];
            foreach($platforms as $platform){
                $platformList[$platform['id']] = $platform['operation_platform_name'];
            }
            $versions = OperationPlatformVersion::find()->where(['operation_platform_id' => $model->operation_platform_id])->all();
            $versionList = ['选择版本'];
            foreach($versions as $version){
                $versionList[$version['id']] = $version['operation_platform_version_name'];
            }
            return $this->render('update', [
                'model' => $model,
                'cityList' => $cityList,
                'platformList' => $platformList,
                'versionList' => $versionList,
            ]);
        }
    }

    /**
     * Deletes an existing OperationAdvertContent model.
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
     * Finds the OperationAdvertContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationAdvertContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationAdvertContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
