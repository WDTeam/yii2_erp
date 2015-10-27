<?php

namespace boss\controllers\customer;

use Yii;
use common\models\customer\CustomerTransRecord;
use common\models\customer\CustomerTransRecordLog;
use boss\models\customer\CustomerTransRecordSearch;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerTransRecordController implements the CRUD actions for CustomerTransRecord model.
 */
class CustomerTransRecordController extends Controller
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
     * Lists all CustomerTransRecord models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new CustomerTransRecordSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CustomerTransRecord model.
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
     * Creates a new CustomerTransRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed

    public function actionCreate()
    {
        $model = new CustomerTransRecord;
        $data = Yii::$app->request->post();
        $data['CustomerTransRecord']['scenario'] = 3;
        //var_dump(Yii::$app->request->post());exit;
        if ($model->load(Yii::$app->request->post())) {
            $model = $this->createRecord($data['CustomerTransRecord']);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
     */
    /**
     * Updates an existing CustomerTransRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed

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
     */
    /**
     * Deletes an existing CustomerTransRecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
*/
    /**
     * Finds the CustomerTransRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerTransRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found

    protected function findModel($id)
    {
        if (($model = CustomerTransRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
     */

}
