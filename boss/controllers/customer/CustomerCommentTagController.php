<?php
/**
* 控制器 评价标签
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/
namespace boss\controllers\customer;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use dbbase\models\customer\CustomerCommentTag;

use boss\models\customer\CustomerCommentTagSearch;

use core\models\order\OrderComplaint;
/**
 * CustomerCommentTagController implements the CRUD actions for CustomerCommentTag model.
 */
class CustomerCommentTagController extends Controller
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
     * Lists all CustomerCommentTag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerCommentTagSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CustomerCommentTag model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) &&  $model->save()) {
        return $this->redirect(['index', 'id' => $model->id]);
        } else {
       return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new CustomerCommentTag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerCommentTag;

        if ($model->load(Yii::$app->request->post()) ) {
        	$date=Yii::$app->request->post();
        	if($date['CustomerCommentTag']['customer_comment_level']==1 || $date['CustomerCommentTag']['customer_comment_level']==2){
        	if($date['CustomerCommentTag']['customer_tag_name']==''){
        		\Yii::$app->getSession()->setFlash('default','请填写评价标签名称！');
        		return $this->redirect(['index']);
        	}		
        	}
        	if($date['CustomerCommentTag']['customer_tag_name']){	
        	$dataname=explode('|',$date['CustomerCommentTag']['customer_tag_name']);
        	}else{
        	$tyu=$date['CustomerCommentTag']['customer_comment_level_es'];
        	$rty=OrderComplaint::ComplaintTypes();
        	foreach ($tyu as $tyuyyu){
        		$dataname[]=$rty['1'][$tyuyyu];
        	}	
        	}
        	
        	$model = new CustomerCommentTag;
        	foreach ($dataname as $datatagname){
        		$postdate['customer_comment_level']=$date['CustomerCommentTag']['customer_comment_level'];
        		$postdate['is_online']=$date['CustomerCommentTag']['is_online'];
        		$postdate['customer_tag_type']=$date['CustomerCommentTag']['customer_tag_type'];
        		$postdate['customer_tag_name']=$datatagname;
        		$postdate['created_at']=time();
        		$postdate['updated_at']=time();
        		$postdate['is_del']=0;
        		$_model = clone $model;
        		$_model->setAttributes($postdate);
        		$_model->save();
        		unset($postdate);
        	}
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomerCommentTag model.
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
     * Deletes an existing CustomerCommentTag model.
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
     * Finds the CustomerCommentTag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerCommentTag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerCommentTag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
