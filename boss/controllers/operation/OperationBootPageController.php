<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\models\operation\OperationBootPage;
use boss\models\operation\OperationCity;
use boss\models\operation\OperationBootPageCity;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * OperationBootPageController implements the CRUD actions for OperationBootPage model.
 */
class OperationBootPageController extends BaseAuthController
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
     * Lists all OperationBootPage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationBootPage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationBootPage model.
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
     * Creates a new OperationBootPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationBootPage();
        $post = Yii::$app->request->post();
        if($post){
            $post['OperationBootPage']['operation_boot_page_online_time'] = strtotime($post['OperationBootPage']['operation_boot_page_online_time']);
            $post['OperationBootPage']['operation_boot_page_offline_time'] = strtotime($post['OperationBootPage']['operation_boot_page_offline_time']);
            $post['OperationBootPage']['created_at'] = time();
            $post['OperationBootPage']['updated_at'] = time();
            $operation_boot_page_ios_img_file = UploadedFile::getInstance($model, 'operation_boot_page_ios_img');
            $operation_boot_page_android_img_file = UploadedFile::getInstance($model, 'operation_boot_page_android_img');
            $ios_key = time().mt_rand('1000', '9999');
            $android_key = time().mt_rand('1000', '9999');
            $path = \Yii::$app->imageHelper->uploadFile($operation_boot_page_ios_img_file->tempName, $ios_key);
            $path = \Yii::$app->imageHelper->uploadFile($operation_boot_page_android_img_file->tempName, $android_key);
            $post['OperationBootPage']['operation_boot_page_ios_img'] = \Yii::$app->imageHelper->getLink($ios_key);
            $post['OperationBootPage']['operation_boot_page_android_img'] = \Yii::$app->imageHelper->getLink($android_key);
        }
        if ($model->load($post) && $model->save()) {
            if(isset($post['citylist'])){
                OperationBootPageCity::setBootPageCityList($post['citylist'], $model->id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'citylist' => OperationCity::getOnlineCityList(),
                'BootPageCityList' => '',
            ]);
        }
    }

    /**
     * Updates an existing OperationBootPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($post){
            $post['OperationBootPage']['operation_boot_page_online_time'] = strtotime($post['OperationBootPage']['operation_boot_page_online_time']);
            $post['OperationBootPage']['operation_boot_page_offline_time'] = strtotime($post['OperationBootPage']['operation_boot_page_offline_time']);
            $post['OperationBootPage']['updated_at'] = time();
            $operation_boot_page_ios_img_file = UploadedFile::getInstance($model, 'operation_boot_page_ios_img');
            $operation_boot_page_android_img_file = UploadedFile::getInstance($model, 'operation_boot_page_android_img');
            $ios_key = time().mt_rand('1000', '9999');
            $android_key = time().mt_rand('1000', '9999');
            $path = \Yii::$app->imageHelper->uploadFile($operation_boot_page_ios_img_file->tempName, $ios_key);
            $path = \Yii::$app->imageHelper->uploadFile($operation_boot_page_android_img_file->tempName, $android_key);
            $post['OperationBootPage']['operation_boot_page_ios_img'] = \Yii::$app->imageHelper->getLink($ios_key);
            $post['OperationBootPage']['operation_boot_page_android_img'] = \Yii::$app->imageHelper->getLink($android_key);
        }
        if ($model->load($post) && $model->save()) {
            if(isset($post['citylist'])){
                OperationBootPageCity::setBootPageCityList($post['citylist'], $model->id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'citylist' => OperationCity::getOnlineCityList(),
                'BootPageCityList' => OperationBootPageCity::getBootPageCityList($id),
            ]);
        }
    }

    /**
     * Deletes an existing OperationBootPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        OperationBootPageCity::delBootPageCityList($id);
        return $this->redirect(['index']);
    }

    /**
     * Finds the OperationBootPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationBootPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationBootPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
