<?php

namespace boss\controllers;

use Yii;
use core\models\order\OrderSearch;
use boss\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use core\models\order\Order;
use yii\web\Response;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

    public function actionCustomer()
    {
//        $phone = Yii::$app->request->get('phone');
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return Customer::find()->where(['customer_phone'=>$phone])->one();
        return '{
                    "id": 1,
                    "customer_name": "刘道强",
                    "customer_sex": 1,
                    "customer_birth": 1442994170,
                    "customer_photo": "",
                    "customer_phone": "18519654001",
                    "customer_email": "liuzhiqiang@corp.1jiajie.com",
                    "region_id": 1,
                    "customer_live_address_detail": "SOHO一期2单元908",
                    "customer_balance": "1000.00",
                    "customer_score": 10000,
                    "customer_level": 1,
                    "customer_complaint_times": 11,
                    "customer_src": 1,
                    "channal_id": 1,
                    "platform_id": 1,
                    "customer_login_ip": "192.168.0.1",
                    "customer_login_time": 1442994170,
                    "customer_is_vip": 1,
                    "created_at": 1442994170,
                    "updated_at": 1442994170,
                    "is_del": 0,
                    "customer_del_reason": ""
                }';
    }

    public function actionCustomerAddress($id)
    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return CustomerAddress::findAll(['customer_id'=>$id]);
        return '[
            {
                "id": 1,
                "customer_id": 1,
                "region_id": 1,
                "customer_address_detail": "北京市朝阳区SOHO1",
                "customer_address_status": 1,
                "customer_address_longitude": 12.888,
                "customer_address_latitude": 888.334,
                "customer_address_nickname": "测试昵称",
                "customer_address_phone": "13554699534",
                "created_at": 1442994172,
                "updated_at": 1442994172,
                "is_del": 0
            },
            {
                "id": 2,
                "customer_id": 1,
                "region_id": 1,
                "customer_address_detail": "北京市朝阳区SOHO2",
                "customer_address_status": 0,
                "customer_address_longitude": 12.888,
                "customer_address_latitude": 888.334,
                "customer_address_nickname": "测试昵称",
                "customer_address_phone": "13554699534",
                "created_at": 1442994172,
                "updated_at": 1442994172,
                "is_del": 0
            },
            {
                "id": 3,
                "customer_id": 1,
                "region_id": 1,
                "customer_address_detail": "北京市朝阳区SOHO3",
                "customer_address_status": 0,
                "customer_address_longitude": 12.888,
                "customer_address_latitude": 888.334,
                "customer_address_nickname": "测试昵称",
                "customer_address_phone": "13554699534",
                "created_at": 1442994172,
                "updated_at": 1442994172,
                "is_del": 0
            }
        ]';
    }

    public function actionCustomerUsedWorkers($id)
    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $customerWorker = CustomerWorker::findAll(['customer_id'=>$id]);
//        $worker = [];
//        foreach($customerWorker as $k=>$v)
//        {
//            $worker[$k] = $v->attributes;
//            $worker[$k]['worker'] = Worker::findOne($v->woker_id);
//        }
//        return $worker;
        return '[
                {
                    "id": 1,
                    "customer_id": 1,
                    "worker_id": 1,
                    "worker_name": "王阿姨",
                    "customer_worker_status": 1
                },
                {
                    "id": 2,
                    "customer_id": 1,
                    "worker_id": 2,
                    "worker_name": "张阿姨",
                    "customer_worker_status": 0

                },
                {
                    "id": 3,
                    "customer_id": 1,
                    "worker_id": 3,
                    "worker_name": "李阿姨",
                    "customer_worker_status": 0
                }
            ]';
    }

    public function actionWorker()
    {
//        $phone = Yii::$app->request->get('phone');
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return Worker::find()->where(['worker_phone'=>$phone])->one();
        return '{
            "id": 1,
            "worker_name": "王阿姨"
        }';
    }

    public function actionCoupons()
    {
        $id = Yii::$app->request->get('id');
        $service_id = Yii::$app->request->get('service_id');
        return '[
                {
                    "id": 1,
                    "coupon_name": "优惠券30",
                    "coupon_money": 30
                },
                {
                    "id": 2,
                    "coupon_name": "40优惠券",
                    "coupon_money": 30
                },
                {
                    "id": 3,
                    "coupon_name": "50优惠券",
                    "coupon_money": 50
                }
            ]';
    }

    public function actionCards($id)
    {
        return '[
                {
                    "id": 1,
                    "card_code": "1234567890",
                    "card_money": 1000
                },
                {
                    "id": 2,
                    "card_code": "9876543245",
                    "card_money": 3000
                },
                {
                    "id": 3,
                    "card_code": "3840959205",
                    "card_money": 5000
                }
            ]';
    }
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($model->load($post)) {
            $post['Order']['admin_id'] = Yii::$app->user->id;
            $post['Order']['order_ip'] = ip2long(Yii::$app->request->userIP);

            if ($model->update($post)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('view', ['model' => $model]);

    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();
        $post = Yii::$app->request->post();
        if($model->load($post)){
            $post['Order']['admin_id'] = Yii::$app->user->id;
            $post['Order']['order_ip'] = ip2long(Yii::$app->request->userIP);
            $post['Order']['order_src_id'] = 1; //订单来源BOSS
            //预约时间处理
            $time = explode('-',$post['Order']['order_booked_time_range']);
            $post['Order']['order_booked_begin_time'] = $post['Order']['order_booked_date'].' '.$time[0].':00';
            $post['Order']['order_booked_end_time'] = ($time[1]=='24:00')?date('Y-m-d H:i:s',strtotime($post['Order']['order_booked_date'].'00:00:00 +1 days')):$post['Order']['order_booked_date'].' '.$time[1].':00';
            if ($model->createNew($post)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }else{//init
            $model->order_service_type_id = 1; //服务类型默认值
            $model->order_booked_count = 120; //服务市场初始值120分钟
            $model->order_booked_worker_id=0; //不指定阿姨
            $model->order_booked_time_range = '08:00-10:00';//预约时间段初始值
            $model->order_pay_type = 1;//支付方式 初始值
            $model->channel_id = 1;//默认渠道
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findById($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
