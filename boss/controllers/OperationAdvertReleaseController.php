<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationAdvertRelease;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\models\Operation\OperationPlatform;
use boss\models\Operation\OperationPlatformVersion;
use boss\models\Operation\OperationAdvertPosition;
use boss\models\Operation\OperationAdvertContent;

/**
 * OperationAdvertReleaseController implements the CRUD actions for OperationAdvertRelease model.
 */
class OperationAdvertReleaseController extends BaseAuthController
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
     * Lists all OperationAdvertRelease models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OperationAdvertRelease::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OperationAdvertRelease model.
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
     * Creates a new OperationAdvertRelease model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationAdvertRelease();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $platform = OperationPlatform::find()->all();
            $platforms = ['选择平台'];
            foreach($platform as $v){
                $platforms[$v->id] = $v->operation_platform_name;
            }
            $position = OperationAdvertPosition::find()->all();
            $positions = ['选择广告位置'];
            foreach($position as $v){
                $positions[$v->id] = $v->operation_platform_name;
            }
            $content = OperationAdvertContent::find(['id', 'operation_advert_position_name'])->all();
            foreach($content as $k => $v){
                $contents[$v->id] = $v->operation_advert_position_name;
            }
            return $this->render('create', [
                'model' => $model,
                'platforms' => $platforms,
                'positions' => $positions,
                'contents' => $contents,
            ]);
        }
    }

    /**
     * Updates an existing OperationAdvertRelease model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OperationAdvertRelease model.
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
     * Finds the OperationAdvertRelease model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationAdvertRelease the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationAdvertRelease::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
