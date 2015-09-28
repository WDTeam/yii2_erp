<?php

namespace boss\controllers;

use Yii;
use boss\models\Operation\OperationCategoryType;
use boss\models\Operation\OperationCategory;
use boss\models\Operation\OperationPriceStrategy;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationCategoryTypeController implements the CRUD actions for OperationCategoryType model.
 */
class OperationCategoryTypeController extends BaseAuthController
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
     * Lists all OperationCategoryType models.
     * @return mixed
     */
    public function actionIndex($category_id)
    {
        $category = OperationCategory::find()->where(['id' => $category_id])->one();
        $dataProvider = new ActiveDataProvider([
            'query' => OperationCategoryType::find()->where(['operation_category_id' => $category_id]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category,
        ]);
    }

    /**
     * Displays a single OperationCategoryType model.
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
     * Creates a new OperationCategoryType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category_id)
    {
        $category = OperationCategory::find()->where(['id' => $category_id])->one();
        
        $priceStrategies = OperationPriceStrategy::getAllStrategy();
        
        $model = new OperationCategoryType();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
                'priceStrategies' => $priceStrategies,
            ]);
        }
    }

    /**
     * Updates an existing OperationCategoryType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $category_id)
    {   
        $category = OperationCategory::find()->where(['id' => $category_id])->one();
        $priceStrategies = OperationPriceStrategy::find()->all();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'category' => $category,
                'priceStrategies' => $priceStrategies,
            ]);
        }
    }

    /**
     * Deletes an existing OperationCategoryType model.
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
     * Finds the OperationCategoryType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationCategoryType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationCategoryType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
