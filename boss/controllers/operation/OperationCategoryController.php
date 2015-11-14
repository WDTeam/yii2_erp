<?php

namespace boss\controllers\operation;

use boss\components\BaseAuthController;
use boss\models\operation\OperationCategory;
use boss\models\operation\OperationCategorySearch;
use boss\models\operation\OperationShopDistrictGoods;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * OperationCategoryController implements the CRUD actions for OperationCategory model.
 */
class OperationCategoryController extends BaseAuthController
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
     * Lists all OperationCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OperationCategorySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single OperationCategory model.
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
     * Creates a new OperationCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationCategory;
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post())) {

            $model->uploadImgToQiniu('operation_category_icon');

            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();

        if ($model->load($post)) {

            unset($model->operation_category_icon);

            $model->uploadImgToQiniu('operation_category_icon');

            $model->updated_at = time();
            if ($model->save()) {

                //关联修改上线商品表冗余的服务品类名称
                $goodsInfo = OperationShopDistrictGoods::updateCategoryName($id, $post['OperationCategory']['operation_category_name']);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OperationCategory model.
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
     * Finds the OperationCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
