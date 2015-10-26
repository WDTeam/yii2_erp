<?php

namespace boss\controllers\shop;

use Yii;
use core\models\shop\Shop;
use core\models\shop\ShopSearch;
use boss\components\BaseAuthController;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopController implements the CRUD actions for Shop model.
 */
class ShopController extends BaseAuthController
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
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopSearch;
        $searchModel->audit_status = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Shop model.
     * @param string $id
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
     * Creates a new Shop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shop;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->is_blacklist = 0;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Shop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Shop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 通过名称获取列表
     */
    public function actionSearchByName($name='', $shop_manager_id=null)
    {
        $query = Shop::find()
        ->select(['id', 'name'])
        ->andFilterWhere(['like', 'name', $name]);
        if(isset($shop_manager_id)){
            $query->andFilterWhere(['=', 'shop_manager_id', $shop_manager_id]);
        }
        $models = $query->limit(50)->all();
        echo Json::encode(['results'=>$models]);
    }
    /**
     * 加入黑名单
     */
    public function actionJoinBlacklist($id)
    {
        $model = $this->findModel($id);
        if(\Yii::$app->request->isPost){
            $cause = Yii::$app->request->post('cause','');
            $model->joinBlacklist($cause);
            \Yii::$app->session->setFlash('default', '添加成功');
            return $this->redirect(['index']);
        }
        return $this->renderPartial('join_blacklist',[
            'model'=>$model
        ]);
    
    }
    /**
     * 解除黑名单
     */
    public function actionRemoveBlacklist($id)
    {
        $cause = Yii::$app->request->get('cause','');
        $this->findModel($id)->removeBlacklist($cause);
        \Yii::$app->session->setFlash('default', '取消成功');
    
        return $this->redirect(['index']);
    }
}
