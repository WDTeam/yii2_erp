<?php

namespace boss\controllers\system;

use Yii;
use core\models\system\SystemUser;
use core\models\system\SystemUserSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\components\RbacHelper;
use yii\web\ForbiddenHttpException;
use core\models\shop\ShopManager;
use yii\helpers\ArrayHelper;
use core\models\shop\Shop;

/**
 * SystemUserController implements the CRUD actions for SystemUser model.
 */
class SystemUserController extends BaseAuthController
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
    
    public function beforeAction($action)
    {
        $name = $this->id.'/'.$action->id;
        $auth = Yii::$app->authManager;
        $perm = $auth->getPermission($name);
        if(\Yii::$app->user->can($name) || \Yii::$app->user->can($this->id.'/index')){
            return true;
        }else{
            throw new ForbiddenHttpException("没有访问权限！");
        }
    }

    /**
     * Lists all SystemUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemUserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single SystemUser model.
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
     * Creates a new SystemUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemUser;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SystemUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            RbacHelper::updateConfigVersion();
            return $this->redirect(['view', 'id' => $model->id]);
        }else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SystemUser model.
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
     * Finds the SystemUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 绑定小家政
     */
    public function actionBindShopManager($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->saveShopManagerIds()) {
            return $this->refresh();
        }
        $shop_managers = ShopManager::find()
        ->select(['id','name'])
        ->where('isdel=0 or isdel is null')
        ->asArray()->all();
        $shop_managers = ArrayHelper::map($shop_managers, 'id', 'name');
        
        return $this->render('bind_shop_manager',[
            'model'=>$model,
            'shop_managers'=>$shop_managers,
        ]);
    }
    /**
     * 绑定门店
     */
    public function actionBindShop($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->saveShopIds()) {
            return $this->refresh();
        }

        $shops = Shop::find()
        ->select(['id','name'])
        ->where('isdel=0 or isdel is null')
        ->asArray()->all();
        $shops = ArrayHelper::map($shops, 'id', 'name');
    
        return $this->render('bind_shop',[
            'model'=>$model,
            'shops'=>$shops,
        ]);
    }
}
