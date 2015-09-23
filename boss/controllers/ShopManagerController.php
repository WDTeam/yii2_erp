<?php

namespace boss\controllers;

use Yii;
use boss\models\ShopManager;
use boss\models\search\ShopManagerSearch;
use boss\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use crazyfd\qiniu\Qiniu;

/**
 * ShopManagerController implements the CRUD actions for ShopManager model.
 */
class ShopManagerController extends Controller
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
     * Lists all ShopManager models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopManagerSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
//             $key = time();
//             $qiniu->uploadFile($_FILES['tmp_name'],$key);
//             $url = $qiniu->getLink($key);
            return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing ShopManager model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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
}
