<?php

namespace boss\controllers;
use Yii;
//use common\models\Customer;
use boss\models\CustomerSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\CustomerAddress;
use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use common\models\OperationCity;
use common\models\GeneralRegion;
use common\models\CustomerExtBalance;
use common\models\CustomerExtScore;

use common\models\OrderExtCustomer;
use core\models\Customer;


/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends BaseAuthController
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch;

        $params = Yii::$app->request->getQueryParams();
        // $params['is_del'] = 0;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionBlock()
    {
        $searchModel = new CustomerSearch;
        $params = Yii::$app->request->getQueryParams();
        // $params['is_del'] = 1;
        $dataProvider = $searchModel->search($params);
        return $this->render('block', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 加入黑名单
     */
    public function actionAddToBlock($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 1;
        $model->validate();
        $model->save();
        return $this->redirect(['/customer/block', 'CustomerSearch'=>['is_del'=>1]]);
    }

    /**
     * 从黑名单中取消
     */
    public function actionRemoveFromBlock($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 0;
        $model->validate();
        $model->save();
        return $this->redirect(['/customer/index', 'CustomerSearch'=>['is_del'=>0]]);
    }

    public function actionSwitchBlock(){
        $id = Yii::$app->request->get('id');

        $customer = Customer::find()->where(['id'=>$id])->one();
        // echo $id.'|'.$customer->is_del;exit;
        // var_dump($customer);

        $is_del = $customer->is_del;
        // var_dump($is_del);
        $is_del = $is_del ? 0 : 1;
        // var_dump($is_del);
        // exit();
        $customer->is_del = $is_del;
        $customer->validate();
        if ($customer->hasErrors()) {
            var_dump($customer->getErrors());
            exit();
        }
        $customer->save();
        // var_dump($is_del);
        // exit();
        if ($customer->is_del == 1) {
            return $this->redirect(['/customer/block', 
                'CustomerSearch'=>['is_del'=>1]]);
        }
        if ($customer->is_del == 0){
            return $this->redirect(['/customer/index', 
                'CustomerSearch'=>['is_del'=>0]]);
        }
        // $connection = Yii::$app->db;
        // echo "1";
        // exit();
        // $model->getErrors();

        // $customer = $connection->createCommand('SELECT * FROM {{%customer}} WHERE id='.$id)->queryOne();
        // var_dump($id);
        // var_dump($connection);
        // var_dump($customer);
        // exit();
        // if ($customer['is_del'] == 1) {
        //     $command = $connection->createCommand('UPDATE {{%customer}} SET is_del=0 WHERE id='.$id);
        //     $command->execute();
        //     return $this->redirect(['/customer/index', 
        //         'CustomerSearch'=>['is_del'=>0]]);
        // }else{
        //     $command = $connection->createCommand('UPDATE {{%customer}} SET is_del=1 WHERE id='.$id);
        //     $command->execute();
        //     return $this->redirect(['/customer/index?CustomerSearch[is_del]=1']);
        // }
    }

    

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new CustomerSearch;
        $model = $this->findModel($id);

        //组装model
        $operationCity = OperationCity::find()->where([
            'id'=>$model->operation_city_id
            ])->one();

        $customerPlatform = CustomerPlatform::find()->where([
            'id'=>$model->platform_id
            ])->one();
        $platform_name = $customerPlatform ? $customerPlatform->platform_name : '_';
        
        $platforms = [];
        $customerPlatforms = CustomerPlatform::find()->asArray()->all();
        foreach ($customerPlatforms as $k => $customerPlatform) {
            $platforms[$customerPlatform['id']] = $customerPlatform['platform_name'];
        }

        $customerChannal = CustomerChannal::find()->where([
            'id'=>$model->channal_id
            ])->one();
        $channal_name = $customerChannal ? $customerChannal->channal_name : '_';
        $channals = [];
        $customerChannals = CustomerChannal::find()->asArray()->all();
        foreach ($customerChannals as $k => $customerChannal) {
            $channals[$customerChannal['id']] = $customerChannal['channal_name'];
        }

        $generalRegion = GeneralRegion::find()->where([
            'id'=>$model->general_region_id
            ])->one();

        //订单地址
        $addressStr = '';
        $default = [];
        $address_count = CustomerAddress::find()->where([
            'customer_id'=>$model->id,
            ])->count();
        $customerDefaultAddress = CustomerAddress::find()->where([
            'customer_id'=>$model->id,
            'customer_address_status'=>1])->one();
        if ($customerDefaultAddress) {
            $general_region_id = $customerDefaultAddress->general_region_id;
            $general_region = GeneralRegion::find()->where([
                'id'=>$general_region_id,
                ])->one();

            // $default = [
            //     'province'=>$general_region->general_region_province_name,
            //     'city'=>$general_region->general_region_city_name,
            //     'area'=>$general_region->general_region_area_name,
            //     'detail'=>$customerDefaultAddress->customer_address_detail,
            //     'phone'=>$customerDefaultAddress->customer_address_phone,
            //     ];

            $default['province'] = $general_region->general_region_province_name;
            $default['city'] = $general_region->general_region_city_name;
            $default['area'] = $general_region->general_region_area_name;
            $default['detail'] = $customerDefaultAddress->customer_address_detail;
            $default['phone'] = $customerDefaultAddress->customer_address_phone;
        }
        

        $customerAddresses = CustomerAddress::find()->where([
            'customer_id'=>$model->id,
            'customer_address_status'=>0
            ])->asArray()->all();

        $others = [];
        if ($customerAddresses !== null) {
            foreach ($customerAddresses as $k => $customerAddress) {
                if ($customerAddress && $customerAddress['general_region_id']) {
                    $general_region_id = $customerAddress['general_region_id'];
                    $general_region = GeneralRegion::find()->where([
                        'id'=>$general_region_id
                        ])->one();
                    // var_dump($customerAddress);
                    // exit();
                    if ($general_region !== null) {
                        $others[$k]['province'] = $general_region->general_region_province_name;
                        $others[$k]['city'] = $general_region->general_region_city_name;
                        $others[$k]['area'] = $general_region->general_region_area_name;
                        $others[$k]['detail'] = $customerAddress['customer_address_detail'];
                        $others[$k]['phone'] = $customerAddress['customer_address_phone'];
                    }
                }
            }
        }

        if (is_array($default) && $default != null) {
            $addressStr = $default['province'].$default['city'].$default['area'].$default['detail']."(".$default['phone'].")<br/>";
        }
        foreach ($others as $k => $other) {
            $addressStr .= $other['province'].$other['city'].$other['area'].$other['detail']."(".$other['phone'].")<br/>";
        }
        
        // if ($address_count <= 0) {
        //     $order_addresses = '-';
        // }
        // if ($address_count == 1) {
        //     $order_addresses =  $general_region->general_region_province_name 
        //     . $general_region->general_region_city_name 
        //     . $general_region->general_region_area_name
        //     . $customerAddress->customer_address_detail;
        // }
        // if ($address_count > 1) {
        //     $order_addresses = $general_region->general_region_province_name 
        //     . $general_region->general_region_city_name 
        //     . $general_region->general_region_area_name
        //     . $customerAddress->customer_address_detail
        //     . '...';
        // }

        //订单数量
        $order_count = OrderExtCustomer::find()->where([
            'customer_id'=>$model->id
            ])->count();
        //客户余额
        $customerBalance = CustomerExtBalance::find()->where([
            'customer_id'=>$model->id
            ])->one();
        if ($customerBalance == NULL) {
            $customerBalance = new CustomerExtBalance;
        }
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', [
                'model' => $model, 
                'searchModel'=>$searchModel,
                'operationCity'=>$operationCity, 
                'customerPlatform'=>$customerPlatform, 
                'platform_name'=>$platform_name,
                'platforms'=>$platforms,
                'customerChannal'=>$customerChannal,
                'channal_name'=>$channal_name,
                'channals'=>$channals,
                'generalRegion'=>$generalRegion,
                // 'order_addresses'=>$order_addresses,
                // 'default'=>$default,
                // 'others'=>$others,
                'addressStr'=>$addressStr,
                'order_count'=>$order_count,
                'customerBalance'=>$customerBalance,
                ]);
        }
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $customerBalance = CustomerExtBalance::find()->where([
            'customer_id'=>$id,
            ])->one();
        if ($customerBalance == NULL) {
            $customerBalance = new CustomerExtBalance;
        }

        $customerScore = CustomerExtScore::find()->where([
            'customer_id'=>$id,
            ])->one();
        if ($customerScore == NULL) {
            $customerScore = new CustomerExtScore;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'customerBalance'=>$customerBalance,
                'customerScore'=>$customerScore,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionData(){

        $connectionNew =  \Yii::$app->db;
        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=sq_ejiajie_v2',
            'username' => 'sq_ejiajie',
            'password' => 'test_sq_ejiajie',
        ]);
        $connection->open();
        $command = $connection->createCommand("SELECT count(*) FROM user_info");
        $count = $command->queryScalar();
        echo "<br/>顾客总记录数为" . $count;
        $numPerPage = 20;
        $curPageNo = 1;
        $totalPage = $count <= 0 ? 0 : floor($count / $numPerPage) + 1;
        if ($totalPage > 0) {
            echo "<br/>正在导入数据。。。";
            while ($curPageNo <= 30) {
                $start = $numPerPage * ($curPageNo - 1);
                $command = $connection->createCommand("SELECT * FROM user_info order by charge_money desc limit ".$start.", ".$numPerPage);
                $userInfo = $command->queryAll();

                foreach($userInfo as $val){
                    $customer = new Customer;
                    // $customer->id = $val['id'];
                    $customer->customer_name = $val['name'];
                    $customer->customer_sex = $val['gender'];
                    $customer->customer_birth = intval(strtotime($val['birthday']));
                    $customer->customer_photo = '';
                    $customer->customer_phone = $val['telphone'];
                    $customer->customer_email = $val['email'];

                    $customer->operation_area_id = 0;
                    $customer->operation_city_id = 0;
                    $customer->general_region_id = 1;
                    $customer->customer_live_address_detail = $val['street'];
                    
                    $customer->customer_balance = $val['charge_money'];
                    $customer->customer_score = 0;
                    $customer->customer_level = $val['level'];
                    $customer->customer_complaint_times = 0;
                    
                    $customer->customer_src = intval($val['user_src']);
                    $customer->channal_id = 0;
                    $customer->platform_id = 0;
                    $customer->customer_login_ip = '';
                    $customer->customer_login_time = 0;
                    $customer->customer_is_vip = $val['user_type'];
                    $customer->created_at = intval(strtotime($val['create_time']));
                    $customer->updated_at = intval(strtotime($val['update_time']));
                    $customer->is_del = $val['is_block'];
                    $customer->customer_del_reason = '辱骂阿姨';
                    $customer->validate();
                    if ($customer->hasErrors()) {
                        var_dump($customer->getErrors());
                        die();
                    }
                    $customer->save();

                    // $customer_id = $customer->id;
                    // $command = $connection->createCommand("SELECT * FROM user_address where user_id=".$val['id']." order by id asc");
                    // $userAddress = $command->queryAll();

                    // foreach ($userAddress as $value) {
                    //     $customerAddress = new CustomerAddress;
                    //     $customerAddress->customer_id = $customer_id;
                    //     $customerAddress->general_region_id = $customer->general_region_id;
                    //     $customerAddress->customer_address_detail = $value['place_detail'];
                    //     $customerAddress->customer_address_status = $value['is_hidden'];
                    //     $customerAddress->customer_address_longitude = $value['lng'];
                    //     $customerAddress->customer_address_latitude = $value['lat'];
                    //     $customerAddress->customer_address_nickname = $customer->customer_name;
                    //     $customerAddress->customer_address_phone = $customer->customer_phone;
                    //     $customerAddress->created_at = intval(strtotime($value['create_time']));
                    //     $customerAddress->updated_at = 0;
                    //     $customerAddress->is_del = 0;
                    //     if ($customerAddress->hasErrors()) {
                    //         var_dump($customer->getErrors());
                    //         die();
                    //     }
                    //     $customerAddress->save();
                    // }
                }
                $curPageNo ++;
            }
        }
        // $connectionNew->createCommand()->batchInsert('ejj_customer_channal', ['channal_name', 'pid', 'created_at', 'updated_at', 'is_del'], [
        //     ['美团', 0, time(), 0, 0],
        //     ['大众', 0, time(), 0, 0],
        //     ['支付宝', 0, time(), 0, 0],
        // ])->execute();
        // echo "customer_channal数据导入成功";

        // $connectionNew->createCommand()->batchInsert('ejj_customer_platform', ['platform_name', 'pid', 'created_at', 'updated_at', 'is_del'], [
        //     ['Android', 0, time(), 0, 0],
        //     ['IOS', 0, time(), 0, 0],
        // ])->execute();
        // echo "customer_platform数据导入成功";
        echo "<br/>customer数据导入成功";
    }

    public function actionTest(){
        // $customer = new Customer;
        // $res = $customer->decBalance(1, 0.01);
        // var_dump($res);

        $test = new Customer;
        $info = $test->getCustomerInfo('1391032906');
        var_dump($info);

    }
}
