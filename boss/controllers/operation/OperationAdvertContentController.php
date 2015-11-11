<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\components\UploadFile;
use boss\models\operation\OperationAdvertContent;
use boss\models\operation\OperationAdvertContentSearch;
use boss\models\operation\OperationPlatform;
use boss\models\operation\OperationPlatformVersion;
use boss\models\operation\OperationCity;
use boss\models\operation\OperationAdvertPosition;
use boss\models\operation\OperationAdvertRelease;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

        $searchModel = new OperationAdvertContentSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'platforms' => $platforms
        ]);
    }
    
    public function actionAjaxList(){
        $post = Yii::$app->request->post();
        $platform_id = isset($post['platform_id']) ? $post['platform_id'] : 'all';
        if($platform_id == 'all'){
            $query = OperationAdvertContent::find();
        }else{
            if(isset($post['version_id'])){
                $where = ['platform_id'=> $platform_id, 'platform_version_id' => $post['version_id']];
            }else{
                $where = ['platform_id'=> $platform_id];
            }
            $query = OperationAdvertContent::find()->where($where);
        }
        if(!empty($post['fields'])){
            $query->andWhere(['like', $post['fields'], $post['keyword'] ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->renderAjax('ajaxlist', [
            'dataProvider' => $dataProvider,
            'post' => $post,
        ]);
    }
    
    public function actionSaveOrders(){
        $data = Yii::$app->request->post();
        $keys = array_keys($data);
        $model = new OperationAdvertContent();
        foreach($data as $id => $orders){
            $model = $this->findModel($id);
            $model->id = $id;
            $model->operation_advert_content_orders = $orders;
            $model->save();
        }
        return '保存排序成功！';
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
        if($post){
            if(!empty($post['old_operation_advert_picture_text'])){
                $old_operation_advert_picture_text = $post['old_operation_advert_picture_text'];
                unset($post['old_operation_advert_picture_text']);
            }
            if(!empty($_FILES['operation_advert_picture_text']['tmp_name'])){
                $path = UploadFile::widget(['fileInputName' => 'operation_advert_picture_text']);
            }else{
                $path = $old_operation_advert_picture_text;
            }
            $post['OperationAdvertContent']['operation_advert_picture_text'] = $path;
            $model->load($post);
            $position = OperationAdvertPosition::find()->asArray()->where(['id' => $post['OperationAdvertContent']['position_id']])->one();
            $model->position_name = $position['operation_advert_position_name'];
            $model->position_id = $position['id'];
            $model->platform_id = $position['operation_platform_id'];
            $model->platform_name = $position['operation_platform_name'];
            $model->platform_version_id = $position['operation_platform_version_id'];
            $model->platform_version_name = $position['operation_platform_version_name'];

            $model->created_at = time();
            $model->updated_at = time();
            $model->save();

            return $this->redirect(['index']);
        } else {
            $ps = OperationAdvertPosition::find()->all();
            $positions = ['选择广告位置'];
            foreach($ps as $position){
                $positions[$position['id']] = $position['operation_advert_position_name'];
            }
            return $this->render('create', [
                'model' => $model,
                'positions' => $positions
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

        if ($model->load($post)) {
            if (!empty($post['old_operation_advert_picture_text'])) {
                $old_operation_advert_picture_text = $post['old_operation_advert_picture_text'];
                unset($post['old_operation_advert_picture_text']);
            }
            if (!empty($_FILES['operation_advert_picture_text']['tmp_name'])) {
                $path = UploadFile::widget(['fileInputName' => 'operation_advert_picture_text']);
            } else {
                $path = $old_operation_advert_picture_text;
            }
            $post['OperationAdvertContent']['operation_advert_picture_text'] = $path;

            $model->load($post);

            $position = OperationAdvertPosition::find()->asArray()->where(['id' => $post['OperationAdvertContent']['position_id']])->one();

            $model->position_name = $position['operation_advert_position_name'];
            $model->position_id = $position['id'];
            $model->platform_id = $position['operation_platform_id'];
            $model->platform_name = $position['operation_platform_name'];
            $model->platform_version_id = $position['operation_platform_version_id'];
            $model->platform_version_name = $position['operation_platform_version_name'];
            $model->updated_at = time();
            $model->save();

            return $this->redirect(['index']);
        } else {
            $ps = OperationAdvertPosition::find()->all();
            $positions = ['选择广告位置'];
            foreach($ps as $position){
                $positions[$position['id']] = $position['operation_advert_position_name'];
            }
            return $this->render('update', [
                'model' => $model,
                'positions' => $positions
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

        //联动删除
        OperationAdvertRelease::updateAdvertReleaseStatus($id);
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
    
    public function actionGetList(){
        $data = Yii::$app->request->get('data');
        $d = !empty($data) ? explode(',', $data) : [];
        $infos = OperationAdvertContent::find()->asArray()->all();
        foreach($infos as $key => $value){
            if(in_array($value['id'], $d)){unset($infos[$key]);}
        }
        return $this->renderPartial('get-list', [
            'infos' => $infos,
            'data' => $d
        ]);
    }
    
    public function actionAdverts(){
        $platform_id = Yii::$app->request->post('platform_id');
        $version_id = Yii::$app->request->post('version_id');
        if($platform_id){
            $where = ['platform_id' => $platform_id];
            $platform = OperationPlatform::find()->where(['id' => $platform_id])->one();
            if($version_id){
                $where = ['AND' , $where, ['platform_version_id' => $version_id]];
                $version = OperationPlatformVersion::find()->where(['id' => $version_id])->one();
            }
        }else{
            $where = null;
        }
        
        $infos = OperationAdvertContent::find()->asArray()->where($where)->all();
        if(isset($version)){
            return $this->renderAjax('adverts', ['infos' => $infos, 'platform' => $platform, 'version' => $version]);
        }else{
            return $this->renderAjax('adverts', ['infos' => $infos, 'platform' => $platform]);
        }
    }
}
