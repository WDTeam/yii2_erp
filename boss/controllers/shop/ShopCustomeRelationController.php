<?php

namespace boss\controllers\shop;

use Yii;
use core\models\shop\ShopCustomeRelation;
use boss\models\shop\ShopCustomeRelationSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use core\models\shop\Shop;
use core\models\shop\ShopManager;
use yii\web\ForbiddenHttpException;
/**
 * ShopCustomeRelationController implements the CRUD actions for ShopCustomeRelation model.
 */
class ShopCustomeRelationController extends BaseAuthController
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
     * Lists all ShopCustomeRelation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopCustomeRelationSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single ShopCustomeRelation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$dateinfo=Yii::$app->request->post();
        	
        	
        	if(empty($dateinfo['ShopCustomeRelation']['shopid'])){
        		//父级id
        		$model->baseid= 0;
        		$stype= ShopCustomeRelation::TYPE_STYPE_SHOPMANAGER;
        	}else{
        		//门店选择家政公司  会根据家政公司选择父id
        		if(!isset($dateinfo['ShopCustomeRelation']['shop_manager_id'])){
        			\Yii::$app->getSession()->setFlash('default','请选择家政公司！');
        			return $this->redirect(['index']);
        		}else{
        			$stype = ShopCustomeRelationSearch::TYPE_STYPE_SHOP;
        			$resinfo=ShopCustomeRelation::find()->select('id')->where(['shop_manager_id'=>$dateinfo['ShopCustomeRelation']['shop_manager_id']])->asArray()->one();
        		}
        		$model->baseid= $resinfo['id'];
        	}
        	
        	$model->stype= $stype;
        	$model->save();
        return $this->redirect(['index']);
        
        
        
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new ShopCustomeRelation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!\Yii::$app->user->can($this->id.'/index')){
            throw new ForbiddenHttpException("没有访问权限！");
        }
        
        $model = new ShopCustomeRelation;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    
   /**
   * 添加
   * @date: 2015-11-2
   * @author: peak pan
   * @return:
   **/ 
    public function actionAddCreate()
    {
        if(!\Yii::$app->user->can($this->id.'/index')){
            throw new ForbiddenHttpException("没有访问权限！");
        }
        
    	$model = new ShopCustomeRelation;
    
    	if ($model->load(Yii::$app->request->post())) {
    		
    		$dateinfo=Yii::$app->request->post();
    	
    		$model->system_user_id= $dateinfo['ShopCustomeRelation']['system_user_id'];
    		
    		if(empty($dateinfo['ShopCustomeRelation']['shopid'])){
    		//父级id	
    			$model->baseid= 0;
    			$stype= ShopCustomeRelation::TYPE_STYPE_SHOPMANAGER;
    		}else{
    		//门店选择家政公司  会根据家政公司选择父id
    		if(!isset($dateinfo['ShopCustomeRelation']['shop_manager_id'])){
    			\Yii::$app->getSession()->setFlash('default','请选择家政公司！');
    			return $this->redirect(['index']);
    		}else{
        		$stype= ShopCustomeRelation::TYPE_STYPE_SHOP;
        		$resinfo=ShopCustomeRelation::find()->select('id')->where(['shop_manager_id'=>$dateinfo['ShopCustomeRelation']['shop_manager_id']])->asArray()->one();
        		}
        			$model->baseid= $resinfo['id'];
        		}
        		$model->shopid= $dateinfo['ShopCustomeRelation']['shopid'];
        		$model->shop_manager_id= $dateinfo['ShopCustomeRelation']['shop_manager_id'];
        		$model->stype= $stype;
        		$model->is_del= 0;
        		 $model->save();
        		return $this->redirect(['index']);
    
        	} else {
        		return $this->render('create', [
    				'model' => $model,
    				]);
    	}
    }
    
    
    
    
    
    
    
    /**
     * Updates an existing ShopCustomeRelation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!\Yii::$app->user->can($this->id.'/index')){
            throw new ForbiddenHttpException("没有访问权限！");
        }
        
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
     * Deletes an existing ShopCustomeRelation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!\Yii::$app->user->can($this->id.'/index')){
            throw new ForbiddenHttpException("没有访问权限！");
        }
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ShopCustomeRelation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopCustomeRelation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopCustomeRelation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 通过搜索关键字获取门店信息  联想搜索通过ajax返回
     * @date: 2015-11-2
     * @param q string 关键字
     * @return result array 门店信息
     * @author: peak pan
     * @return:
     **/
    
    public function actionShowShop($q = null)
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$out = ['results' => ['id' => '', 'text' => '']];
    	$condition = '';
    	if($q!=null){
    		$condition = 'name LIKE "%' . $q .'%"';
    	}
    	$shopResult = Shop::find()->where($condition)->select('id, name AS text')->asArray()->all();
    	$out['results'] = array_values($shopResult);
    	//$out['results'] = [['id' => '1', 'text' => '门店'], ['id' => '2', 'text' => '门店2'], ['id' => '2', 'text' => '门店3']];
    	return $out;
    }
    
   
    /**
     * 通过搜索关键字获取家政公司信息  联想搜索通过ajax返回
     * @date: 2015-11-2
     * @param q string 关键字
     * @return result array 门店信息
     * @author: peak pan
     * @return:
     **/
    
    public function actionShopManager($q = null)
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$out = ['results' => ['id' => '', 'text' => '']];
    	$condition = '';
    	if($q!=null){
    		$condition = 'name LIKE "%' . $q .'%"';
    	}
    	$shopResult = ShopManager::find()->where($condition)->select('id, name AS text')->asArray()->all();
    	 $out['results'] = array_values($shopResult);
    	 
    	/*var_dump($out['results']);
    	exit; */
    	
    	return $out;
    }
    
    
    
    
}
