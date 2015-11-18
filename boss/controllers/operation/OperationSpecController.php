<?php

namespace boss\controllers\operation;

use boss\models\operation\OperationSpec;
use boss\models\operation\OperationGoods;
use boss\models\operation\OperationSpecSearch;
use boss\models\operation\OperationShopDistrictGoods;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationSpecController implements the CRUD actions for OperationSpec model.
 */
class OperationSpecController extends Controller
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
     * Lists all OperationSpec models.
     * @return mixed
     */
    public function actionIndex()
    {
        $OperationSpecModel = new OperationSpec();
        $searchModel = new OperationSpecSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'OperationSpecModel' => $OperationSpecModel,
        ]);
    }

    /**
     * Displays a single OperationSpec model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $operation_spec_values = serialize(array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationSpec']['operation_spec_values'])))));
            $model->operation_spec_values = $operation_spec_values;
            $model->created_at = time();
            $model->updated_at = time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->operation_spec_values = OperationSpec::hanldeSpecValues($model->operation_spec_values);
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new OperationSpec model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OperationSpec;
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $operation_spec_values = serialize(array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationSpec']['operation_spec_values'])))));
            $model->operation_spec_values = $operation_spec_values;
            $model->created_at = time();
            $model->updated_at = time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OperationSpec model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $operation_spec_values = serialize(array_filter(explode(';', str_replace(' ', '', str_replace('；', ';', $post['OperationSpec']['operation_spec_values'])))));
            $model->operation_spec_values = $operation_spec_values;
            $model->updated_at = time();
            if ($model->save()) {

                //关联修改服务项目表冗余的计量单位
                OperationGoods::updateGoodsSpec($id, $post['OperationSpec']['operation_spec_strategy_unit']);
                OperationShopDistrictGoods::updateGoodsSpec($id, $post['OperationSpec']['operation_spec_strategy_unit']);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->operation_spec_values = implode(';', unserialize($model->operation_spec_values));
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OperationSpec model.
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
     * ajax验证规格名称是否重复
     */
    public function actionAjaxValidateSpecInfo()
    {
        $spec_id = Yii::$app->request->get('id');
        $action = Yii::$app->request->get('action');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        //修改规格
        if ($action == 'update' && isset($spec_id) && $spec_id > 0) {
            $specModel = OperationSpec::find()->where(['id' => $spec_id])->one();
            $specModel->load(Yii::$app->request->post());
            return \yii\bootstrap\ActiveForm::validate($specModel,['operation_spec_name']);

        //添加规格
        }else{
            $specModel = new OperationSpec(['is_softdel' => 0]);
            $specModel->load(Yii::$app->request->post());
            return \yii\bootstrap\ActiveForm::validate($specModel,['operation_spec_name']);
        }
    }

    /**
     * Finds the OperationSpec model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OperationSpec the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OperationSpec::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
