<?php

namespace boss\controllers;

use Yii;
use common\models\Pay;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use vendor\ejiajie\pay\alipay_web;
/**
 * PayController implements the CRUD actions for Pay model.
 */
class PayController extends Controller
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
     * 发送给支付接口的数据
     * @param integer $pay_money 支付金额
     * @param integer $customer_id 消费者ID
     * @param integer $channel_id 渠道ID
     * @param integer $order_id 订单ID
     * @param integer $partner 第三方合作号
     */
    public function actionGetPay()
    {
        //接收数据
        $request = yii::$app->request;
        $data = $request->get();

        //实例化模型
        $model = new Pay();

        //在线支付（online_pay），在线充值（pay）
        $scenario = empty($data['order_id']) ? 'pay' : 'online_pay';

        //支付来源
        $data['pay_source_name'] = $model->source($data['pay_source']);

        //使用场景
        $model->scenario = $scenario;
        $model->attributes = $data;

        //验证数据
        if( $model->validate() && $model->save(true,array('id')) ){

            //返回组装数据
            $model->getPay();
            echo "验证成功!";

        }else{
            var_dump($model->errors);
        }
    }

    /**
     * 接收第三方支付数据
     * @return mixed
     */
    public function actionReceive()
    {

    }

    /**
     * 查询支付第三方第三方支付数据
     * @return mixed
     */
    public function actionSearch()
    {

    }


    /**
     * 退款
     * @return mixed
     */
    public function actionRefund()
    {

    }

    /**
     * Lists all Pay models.
     * @return mixed
     */
    public function actionIndex()
    {
        var_dump(Yii::$app->params['adminEmail']);
        var_dump($alipay_config);

        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('{{%pay}}')
            ->limit(10)
            ->orderBy('id DESC')
            ->all();
        var_dump($rows);
        exit;
        $dataProvider = new ActiveDataProvider([
            'query' => Pay::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pay model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pay model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pay();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pay model.
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
     * Deletes an existing Pay model.
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
     * Finds the Pay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pay::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
