<?php

namespace boss\controllers\shop;

use Yii;
use core\models\shop\Shop;
use core\models\shop\ShopSearch;
use boss\components\BaseAuthController;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\base\Object;
use core\models\shop\ShopCustomeRelation;
use core\models\auth\AuthItem;

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
     * 判断当前用户能不能操作
     * @param unknown $id
     * @throws BadRequestHttpException
     */
    public function can($id)
    {
        $ids = \Yii::$app->user->identity->getShopIds();
        if(\Yii::$app->user->identity->isNotAdmin() && !in_array($id, $ids)){
            throw new BadRequestHttpException('没有访问权限', 403);
        }
    }

    /**
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopSearch;
        
        $query = Yii::$app->request->getQueryParams();
        if(\Yii::$app->user->identity->isNotAdmin()){
            $query['ids'] = \Yii::$app->user->identity->getShopIds();
            $query['ids'] = empty($query['ids'])?[0]:$query['ids']; 
        }
        $dataProvider = $searchModel->search($query);

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
        $this->can($id);
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
            
            $ref = new ShopCustomeRelation();
            $ref->system_user_id = \Yii::$app->user->id;
            $ref->shopid = $model->id;
            $ref->shop_manager_id = $model->shop_manager_id;
            $ref->stype = ShopCustomeRelation::TYPE_STYPE_SHOP;
            $ref->save();
            
            return $this->redirect(['index', 'id' => $model->id]);
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
        $this->can($id);
        
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post())){
            if($model->changeAuditStatus(0,'修改内容')){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->can($id);
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
    public function actionSearchByName($name='', $shop_manager_id=null, $city_id=null)
    {
        $query = Shop::find()
        ->select(['id', 'name'])
        ->andFilterWhere(['like', 'name', $name]);
        if(isset($city_id)){
            $query->andFilterWhere(['=', 'city_id', $city_id]);
        }
        if(isset($shop_manager_id)){
            $query->andFilterWhere(['=', 'shop_manager_id', $shop_manager_id]);
        }
        if(\Yii::$app->user->identity->isMiNiBoss()){
            $query->andWhere(['in', 'id', \Yii::$app->user->identity->getShopIds()]);
        }
        $models = $query->all();
        echo Json::encode(['results'=>$models]);
    }
    /**
     * 加入黑名单
     */
    public function actionJoinBlacklist($id)
    {
        $this->can($id);
        $model = $this->findModel($id);
        if(\Yii::$app->request->isPost){
            exit;
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
        $this->can($id);
        $cause = Yii::$app->request->get('cause','');
        $this->findModel($id)->removeBlacklist($cause);
        \Yii::$app->session->setFlash('default', '取消成功');
    
        return $this->redirect(['index']);
    }
    /**
     * 设置审核状态
     */
    public function actionSetAuditStatus($id) 
    {
        $this->can($id);
        $model = Shop::findOne(['id'=>$id]);
        $model->load(\Yii::$app->request->post());
        if($model->save()){
            \Yii::$app->session->setFlash('defautl', '修改成功');
            return $this->redirect(['view', 'id'=>$model->id]);
        }
    }
}
