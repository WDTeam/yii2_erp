<?php
namespace boss\controllers\finance;

use Yii;
use dbbase\models\finance\FinanceOrderChannel;
use boss\models\finance\FinanceOrderChannelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FinanceOrderChannelController implements the CRUD actions for FinanceOrderChannel model.
 */
class FinanceOrderChannelController extends Controller
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
     * Lists all FinanceOrderChannel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceOrderChannelSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
        
        
    }

    
    public function actionInfochannle()
    {
    	$searchModel = new FinanceOrderChannelSearch;
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    
    	return $this->render('infochannle', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);
    }
    
    
    
    
    
    /**
     * Displays a single FinanceOrderChannel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_time=time();
        	$model->save();
        return $this->redirect(['index']);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new FinanceOrderChannel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceOrderChannel;

        if ($model->load(Yii::$app->request->post()) ) {
        	$date=Yii::$app->request->post();
        	$dateinfo=FinanceOrderChannel::find()->where(['finance_order_channel_name'=>$date['FinanceOrderChannel']['finance_order_channel_name']])->count();

        	 if($dateinfo>0){
        		\Yii::$app->getSession()->setFlash('default','支付渠道名称不能重复！');
        		return $this->redirect(['index']);
        	} 
        	$model->create_time=time();
        	$model->save();
        	
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceOrderChannel model.
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
     * Deletes an existing FinanceOrderChannel model.
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
     * Finds the FinanceOrderChannel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceOrderChannel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceOrderChannel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
