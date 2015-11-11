<?php

namespace boss\controllers\operation;

use Yii;
use dbbase\models\operation\OperationOrderChannel;
use boss\models\operation\OperationOrderChannelSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationOrderChannelController implements the CRUD actions for OperationOrderChannel model.
 */
class OperationOrderChannelController extends BaseAuthController
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
     * Lists all OperationOrderChannel models.
     * @return mixed
     */
    public function actionIndex()
    {
    	
    	/*  $ert=['access_token'=> '4c6b908a940b42696dadc9279bb66fd5',
    	'order_id' => 1,
    	'worker_id' => 1,
    	'worker_tel' =>'13256568989',
    	'operation_shop_district_id' => 1,
    	'province_id' => 1,
    	'city_id' => 1,
    	'county_id' => 1,
    	'customer_comment_phone' => '13689898989',
    	'customer_comment_content' => '评论内容',
    	'customer_comment_level'=> 3,
    	'customer_comment_level_name' =>1,
    	'customer_comment_tag_ids' => 1,
    	'customer_comment_tag_names' => 1,
    	'customer_comment_anonymous' => 1,
    	'customer_id' => 7,
    	'order_code' =>'123445',
		];
    	$rty=\core\models\customer\CustomerComment::addUserSuggest($ert);
    	var_dump($rty);exit;  */
    	 
        $searchModel = new OperationOrderChannelSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single OperationOrderChannel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['index']);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new OperationOrderChannel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationOrderChannel;

        if ($model->load(Yii::$app->request->post())) {
        	
        	$model->system_user_id=Yii::$app->user->identity->id;;
        	$model->system_user_name=Yii::$app->user->identity->username;;
        	$model->create_time=time();
        	$model->is_del=0;
        	 $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationOrderChannel model.
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
     * Deletes an existing OperationOrderChannel model.
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
     * Finds the OperationOrderChannel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationOrderChannel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationOrderChannel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
