<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\models\operation\OperationPlatformVersion;
use boss\models\operation\OperationPlatform;
use boss\models\operation\OperationAdvertContent;
use boss\models\operation\OperationAdvertPosition;
use boss\models\operation\OperationAdvertRelease;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationPlatformVersionController implements the CRUD actions for OperationPlatformVersion model.
 */
class OperationPlatformVersionController extends BaseAuthController
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
     * Lists all OperationPlatformVersion models.
     * @return mixed
     */
    public function actionIndex($platform_id = '')
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationPlatformVersion::find()->where(['operation_platform_id' => $platform_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'platform_id' => $platform_id,
        ]);
    }

    /**
     * Displays a single OperationPlatformVersion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $platform_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'platform_id' => $platform_id
        ]);
    }

    /**
     * Creates a new OperationPlatformVersion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($platform_id)
    {
        $model = new OperationPlatformVersion();
        
        if ($model->load(Yii::$app->request->post())) {
            $platform = OperationPlatform::find()->where(['id' => $platform_id])->one();
            $model->operation_platform_name = $platform->operation_platform_name;
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index', 'platform_id' => $platform_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'platform_id' => $platform_id
            ]);
        }
    }
    
    public function actionVersionList(){
        $platform_id = Yii::$app->request->post('platform_id');
        if(empty($platform_id)){
            $result = FALSE;
        }else{
            $data = OperationPlatformVersion::find()->where(['operation_platform_id' => $platform_id])->all();
            $d = [];
            foreach($data as $v){
                $d[$v['id']] = $v['operation_platform_version_name'];
            }
            if(empty($data)){
                $result = FALSE;
            }else{
                $result = TRUE;
            }
        }
        return json_encode(['result' => $result, 'data' => $d]);
    }

    /**
     * Updates an existing OperationPlatformVersion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $platform_id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $platform = OperationPlatform::find()->where(['id' => $platform_id])->one();
            $model->operation_platform_name = $platform->operation_platform_name;
            $model->updated_at = time();
            $model->save();

            //更新冗余的平台版本,平台版本冗余在两个表:内容,位置表
            OperationAdvertContent::updatePlatformVersion($id, $post['OperationPlatformVersion']['operation_platform_version_name']);
            OperationAdvertPosition::updatePlatformVersion($id, $post['OperationPlatformVersion']['operation_platform_version_name']);

            return $this->redirect(['index', 'platform_id' => $platform_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'platform_id' => $platform_id
            ]);
        }
    }

    /**
     * Deletes an existing OperationPlatformVersion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $platform_id)
    {
        $this->findModel($id)->delete();

        //联动删除
        OperationAdvertPosition::updateAdvertPositionStatusFromVersion($id);

        //获取所有的内容编号，根据内容编号删除已发布的广告
        $result = OperationAdvertContent::getAdvertContentFromVersion($id);
        if ($result != false) {
            foreach ($result as $key => $value) {
                OperationAdvertRelease::updateAdvertReleaseStatus($value['id']);
            }
        }
        OperationAdvertContent::updateAdvertContentStatusFromVersion($id);

        return $this->redirect(['index', 'platform_id' => $platform_id]);
    }

    /**
     * Finds the OperationPlatformVersion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationPlatformVersion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationPlatformVersion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPlatformVersions(){
        $platform_id = Yii::$app->request->post('platform_id');
        $platform = OperationPlatform::find()->asArray()->where(['id' => $platform_id])->one();
        $versions = OperationPlatformVersion::find()->asArray()->where(['operation_platform_id' => $platform_id])->all();
        $data = [];
        foreach($versions as $k => $v){
            $data[$v['id']] = $v['operation_platform_version_name'];
        }
        return $this->renderAjax('versions', ['versions' => $data, 'platform' => $platform]);
    }
}
