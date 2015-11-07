<?php

namespace boss\controllers\operation;

use boss\models\operation\OperationSelectedService;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

use crazyfd\qiniu\Qiniu;

/**
 * OperationSelectedServiceController implements the CRUD actions for OperationGoods model.
 */
class OperationSelectedServiceController extends Controller
{
    static $jsondata = [
        'msg' => '',    // 提示消息 失败提示信息
        'status' => 0, //状态 0: 失败 1：成功 'data' => '',  //数据 
        ];
    
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
     * Lists all OperationSelectedService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new  OperationSelectedService; 

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new opreationselectedservice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationSelectedService;
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $model->selected_service_area_standard = $post['OperationSelectedService']['selected_service_area_standard'];

            $model->created_at = time();
            $model->uploadImgToQiniu('selected_service_photo');

            if($model->save()){

                return $this->redirect(['/operation/operation-selected-service']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            
            $model->updated_at = time();
            
            if($model->save()){
                return $this->redirect(['/operation/operation-selected-service']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OperationGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (($model = OperationSelectedService::findOne($id)) !== null) {
            $this->findModel($id)->delete();
        } else {
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the OperationSelectedService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationSelectedService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationSelectedService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single OperationSelectService model.
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
}
