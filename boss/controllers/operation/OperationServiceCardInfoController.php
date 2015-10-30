<?php

namespace boss\controllers\operation;

use boss\models\operation\OperationServiceCardInfo;
use boss\models\operation\OperationServiceCardInfoSearch;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationServiceCardInfoController implements the CRUD actions for OperationServiceCardInfo model.
 */
class OperationServiceCardInfoController extends Controller
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
     * Lists all OperationServiceCardInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OperationServiceCardInfoSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		$config = $searchModel->getServiceCardConfig();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'config' => $config,
        ]);
    }

    /**
     * Displays a single OperationServiceCardInfo model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
		$config = $model->getServiceCardConfig();

        if ($model->load(Yii::$app->request->post()) && $model->serviceCardInfoUpdate()) {
        return $this->redirect(['view', 'id' => $model->id,'config'=> $config]);
        } else {
        return $this->render('view', ['model' => $model,'config'=> $config]);
}
    }

    /**
     * Creates a new OperationServiceCardInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationServiceCardInfo;
		$config = $model->getServiceCardConfig();

        if ($model->load(Yii::$app->request->post()) && $model->serviceCardInfoCreate()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'config' => $config,
            ]);
        }
    }

    /**
     * Updates an existing OperationServiceCardInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$config = $model->getServiceCardConfig();
        if ($model->load(Yii::$app->request->post()) && $model->serviceCardInfoUpdate()) {
			
			file_put_contents('d:/demo/1.txt',var_export($model->load(Yii::$app->request->post()),true));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'config' => $config,
            ]);
        }
    }

    /**
     * Deletes an existing OperationServiceCardInfo model.
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
     * Finds the OperationServiceCardInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return OperationServiceCardInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationServiceCardInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
