<?php
/**
* 对账控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* --------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-9-23
* @author: peak pan 
* @version:1.0
*/

namespace boss\controllers;

use Yii;
use common\models\FinancePopOrder;
use boss\models\FinancePopOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FinancePopOrderController implements the CRUD actions for FinancePopOrder model.
 */
class FinancePopOrderController extends Controller
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
    * 对账方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionIndex()
    {
        $searchModel = new FinancePopOrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    
    
    /**
    * 查看和修改公用方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
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
    * 公用添加方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionCreate()
    {
        $model = new FinancePopOrder;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    
    /**
    * 修改方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
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
   * 公用删除方法
   * @date: 2015-9-23
   * @author: peak pan
   * @return:
   **/
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
    * 私有单一查询的Model，使用本类
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    protected function findModel($id)
    {
        if (($model = FinancePopOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * 对账统计方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionBillcount(){
    	$searchModel = new FinancePopOrderSearch;
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    	return $this->render('index', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);
    	
    }
    
    
    /**
    * 对账详情
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionBillinfo(){
    	 
    	$searchModel = new FinancePopOrderSearch;
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    	return $this->render('billinfo', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);
    	 
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
