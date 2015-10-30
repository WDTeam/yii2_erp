<?php

namespace boss\controllers\operation;

use Yii;
use boss\models\operation\OperationPlatformVersion;
use boss\models\operation\OperationPlatform;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
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

        if ($model->load(Yii::$app->request->post())) {
            $platform = OperationPlatform::find()->where(['id' => $platform_id])->one();
            $model->operation_platform_name = $platform->operation_platform_name;
            $model->updated_at = time();
            $model->save();
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
