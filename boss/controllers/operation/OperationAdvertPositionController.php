<?php

namespace boss\controllers\operation;

use Yii;
use boss\models\operation\OperationAdvertPosition;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\models\operation\OperationPlatform;
use boss\models\operation\OperationPlatformVersion;

/**
 * OperationAdvertPositionController implements the CRUD actions for OperationAdvertPosition model.
 */
class OperationAdvertPositionController extends BaseAuthController
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
     * Lists all OperationAdvertPosition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $post = Yii::$app->request->post();

        $dataProvider = new ActiveDataProvider([
            'query' => (!empty($post) && $post['fields'] != '0') ? OperationAdvertPosition::find()->where([$post['fields'] => $post['keyword']]): OperationAdvertPosition::find(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'post' => $post,
        ]);
    }
    
//    public function actionCityAdvertPosition(){
//        $p = Yii::$app->request->post();
//        $query = OperationAdvertPosition::find();
//        if(isset($p['operation_city_id'])){
//            if($p['operation_city_id'] != 'all'){
//                $query->andFilterWhere(['operation_city_id' => $p['operation_city_id']]);
//                if(isset($p['fields']) && isset($p['keyword'])){
//                    $query->andFilterWhere(['like', $p['fields'], $p['keyword']]);
//                }
//            }
//        }
//        if(isset($p['fields']) && isset($p['keyword'])){
//             $query->andFilterWhere(['like', $p['fields'], $p['keyword']]);
//        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//        return $this->renderPartial('city-advert-position', [
//            'dataProvider' => $dataProvider,
//            'p' => $p
//        ]);
//    }

    /**
     * Displays a single OperationAdvertPosition model.
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
     * Creates a new OperationAdvertPosition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationAdvertPosition();
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $platform = OperationPlatform::find()->where(['id'=>$post['OperationAdvertPosition']['operation_platform_id']])->one();
            $version = OperationPlatformVersion::find()->where(['id'=>$post['OperationAdvertPosition']['operation_platform_version_id']])->one();
            $model->operation_platform_name = $platform['operation_platform_name'];
            $model->operation_platform_version_name = $version['operation_platform_version_name'];
            $model->created_at = time();
            $model->updated_at = time();
            $model->load($post);
            $model->save();
            return $this->redirect(['index']);
        } else {
            $platform = OperationPlatform::find()->all();
            $platforms = ['选择平台'];
            foreach($platform as $v){
                $platforms[$v->id] = $v->operation_platform_name;
            }
            return $this->render('create', [
                'model' => $model,
                'platforms' => $platforms,
            ]);
        }
    }

    /**
     * Updates an existing OperationAdvertPosition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $platform = OperationPlatform::find()->all();
        $platforms = ['选择平台'];
        foreach($platform as $v){
            $platforms[$v->id] = $v->operation_platform_name;
        }
        $version = OperationPlatformVersion::find()->where(['operation_platform_id' => $model->operation_platform_id])->all();
        $versions = ['选择版本'];
        foreach((array)$version as $v){
            $versions[$v->id] = $v->operation_platform_version_name;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'platforms' => $platforms,
                'versions' => !isset($versions) ? ['选择版本'] : $versions,
            ]);
        }
    }

    /**
     * Deletes an existing OperationAdvertPosition model.
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
     * Finds the OperationAdvertPosition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationAdvertPosition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationAdvertPosition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
