<?php

/**
* 控制器  支付渠道管理
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/
namespace boss\controllers\operation;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use dbbase\models\operation\OperationPayChannel;
use boss\models\operation\OperationPayChannelSearch;
use boss\components\BaseAuthController;

/**
 * OperationPayChannelController implements the CRUD actions for OperationPayChannel model.
 */
class OperationPayChannelController extends BaseAuthController {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all OperationPayChannel models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new OperationPayChannelSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->getQueryParams () );
		
		return $this->render ( 'index', [ 
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel 
		] );
	}
	
	/**
	 * Displays a single OperationPayChannel model.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel ( $id );
		if ($model->load ( Yii::$app->request->post () ) ) {
			$model->system_user_id=Yii::$app->user->id;;
			$model->system_user_name=Yii::$app->user->identity->username;
			$model->create_time=time();
			$model->is_del=0;
			$model->save ();
			return $this->redirect ( [ 
					'index'
			] );
		} else {
			return $this->render ( 'view', [ 
					'model' => $model 
			] );
		}
	}
	
	
	
	/**
	 * Creates a new OperationPayChannel model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new OperationPayChannel;
		if ($model->load ( Yii::$app->request->post () ) ) {
			$isdata=OperationPayChannel::find()->where(['id'=>$_POST['OperationPayChannel']['id']])->asArray()->one();
			if(!empty($isdata)){
				\Yii::$app->getSession()->setFlash('default','ID已经使用！');
				return $this->redirect(['index']);
			}
			
			$model->system_user_id=Yii::$app->user->id;;
			$model->system_user_name=Yii::$app->user->identity->username;
			$model->create_time=time();
			$model->is_del=0;
			$model->save();
			return $this->redirect ( [ 
					'index'
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing OperationPayChannel model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel ( $id );
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model 
			] );
		}
	}
	
	
	/**
	 * ajax验证 优惠券是否唯一
	 * @return array
	 */
	public function actionAjaxInfo(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$PayChannelModel = new OperationPayChannel;
		$PayChannelModel->load(Yii::$app->request->post());
		return \yii\bootstrap\ActiveForm::validate($PayChannelModel,['id']);
		 
	}
	
	
	/**
	 * Deletes an existing OperationPayChannel model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel ( $id )->delete ();
		
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the OperationPayChannel model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return OperationPayChannel the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = OperationPayChannel::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
}
