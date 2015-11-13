<?php

namespace boss\controllers\customer;
use Yii;
//use dbbase\models\Customer;
use boss\models\customer\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use core\models\customer\Customer;
use core\models\customer\CustomerAddress;
use core\models\customer\CustomerWorker;
use core\models\customer\CustomerExtSrc;
use core\models\customer\CustomerExtBalance;
use core\models\customer\CustomerExtScore;
use core\models\customer\CustomerBlockLog;
use core\models\customer\CustomerComment;
/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
        
//        if(!empty($_REQUEST['CustomerSort'])){
//            print_r($_REQUEST['CustomerSort']);
//            exit();
//        }
        $params = Yii::$app->request->getQueryParams();
//        $params['CustomerSort'] = [
//            ['field'=>$field],
//            ['order'=>$order],
//        ];
//        $params['CustomerSort'] = $_REQUEST['CustomerSort'];
            
        $dataProvider = $searchModel->search($params);
		//customer count on search
		$count_on_search = $dataProvider->query->count();
		//count of customer is blocked on search 
		//$block_count_on_search = $dataProvider->query->addFilterWhere(['is_del'=>1])->count();
		//count of all customer
		$all_count = Customer::countAllCustomer();
		//count of block-customer
		$block_count = Customer::countBlockCustomer();
        $is_del = isset($_GET['CustomerSearch']['is_del']) ? $_GET['CustomerSearch']['is_del'] : 0;
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'is_del'=>$is_del,
			'count_on_search'=>$count_on_search,
			//'block_count_on_search'=>$block_count_on_search,
			'all_count'=>$all_count,
			'block_count'=>$block_count,
        ]);
    }

    public function actionCommentList(){
        
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionBlock()
    {
        $searchModel = new CustomerSearch;
        $params = Yii::$app->request->getQueryParams();
        $dataProvider = $searchModel->search($params);
        return $this->render('block', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSwitchBlock(){
        $id = Yii::$app->request->get('id');

        $customer = Customer::find()->where(['id'=>$id])->one();
        $is_del = $customer->is_del;
        $is_del = $is_del ? 0 : 1;
        $customer->is_del = $is_del;
        $customer->validate();
        if ($customer->hasErrors()) {
            var_dump($customer->getErrors());
            exit();
        }
        $customer->save();
        if ($customer->is_del == 1) {
            return $this->redirect(['/customer/block', 
                'CustomerSearch'=>['is_del'=>1]]);
        }
        if ($customer->is_del == 0){
            return $this->redirect(['/customer/index', 
                'CustomerSearch'=>['is_del'=>0]]);
        }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', [
                'model' => $model, 
                'searchModel'=>$searchModel,
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

    /*
     * 创建客户封号信息
     * @param customerId 客户Id
     * @return empty
     */
    public function actionAddToBlock($id){

        $model = $this->findModel($id);
		$customerBlockLogModel = new CustomerBlockLog;
        return $this->renderAjax('add-to-block',['model'=>$model]);
    }
    
    /**
     * add customer to black-block
     */
    public function actionDoAddToBlock(){
        $id = \Yii::$app->request->get('id');
        $block_reason = \Yii::$app->request->get('block_reason');
        $is_added = CustomerBlockLog::addToBlock($id, $block_reason);
        echo $is_added;
    }

    /*
     * 客户从黑名单中删除
     * @param customer_id 客户Id
     * @return empty
     */
    public function actionRemoveFromBlock($id){
        $is_removed = CustomerBlockLog::removeFromBlock($id);
		if($is_removed){
			\Yii::$app->session->setFlash('default', '解除封号成功');
		}else{
			\Yii::$app->session->setFlash('default', '解除封号失败');
		}
        return $this->redirect(['index']);
    }

    /**
     * 批量加入黑名单
     */
    public function actionMultiAddToBlock(){
        // $ids_str = \Yii::$app->request->get('ids_str', '');
        // $ids_arr = explode(',', trim($ids_str, ','));
        $ids_arr = \Yii::$app->request->post('ids', '');
        // var_dump($ids);
        // exit();
        if(\Yii::$app->request->post()){
            $block_reason =\Yii::$app->request->post('customer_del_reason','');
            if (!empty($ids_arr)) {
                foreach ($ids_arr as $id) {
                    $is_added = CustomerBlockLog::addToBlock($id, $block_reason);
                }
            }
            return $this->redirect(['index']);
        }
        return $this->renderAjax('multi-add-to-block',['ids_str'=>$ids_str]);
    }

    /**
     * 批量从黑名单中删除
     */
    public function actionMultiRemoveFromBlock(){
        $ids_str = \Yii::$app->request->get('ids_str', '');
        $ids_arr = explode(',', trim($ids_str, ','));
        if (!empty($ids_arr)) {
            foreach ($ids_arr as $id) {
                $is_removed = CustomerBlockLog::RemoveFromBlock($id, $block_reason);
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * 修改客户订单服务地址
     */
    public function actionUpdateCustomerAddresses($customer_id){
        $customerAddressModel = CustomerAddress::findAll(['customer_id'=>$customer_id]);
        // var_dump($customer_id);
        // var_dump($customerAddressModel);
        // exit();
        if(\Yii::$app->request->post()){
            // $customerModel->is_del = 0;
            // $customerModel->customer_del_reason = '';
            // $customerModel->updated_at = time();
            // $customerModel->validate();
            // if ($customerModel->hasErrors()) {
            //     return $this->renderAjax('remove_from_block',['customerModel'=>$customerModel,]);
            // }
            // $customerModel->save();

            foreach ($customerAddressModel as $customerAddress) {
                // $customerAddress->customer_id 
                // $customerAddress->general_region_id
                // $customerAddress->customer_address_detail
                // $customerAddress->customer_address_status
                // $customerAddress->customer_address_longitude
                // $customerAddress->customer_address_latitude
                // $customerAddress->customer_address_phone
                // $customerAddress->customer_address_nickname
                // $customerAddress->is_del
                // $customerAddress->created_at
                // $customerAddress->updated_at
            }
            return $this->redirect(['index', 'CustomerSearch'=>['is_del'=>0]]);
        }else{
            return $this->render('update-customer-addresses',['customerAddressModel'=>$customerAddressModel]);
        }
    }


    public $global_cur_page_no = 1;
    public function actionData(){
        // set_time_limit(30 * 1000); 
        ini_set("max_execution_time", 30000);
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
        $totalPage = $count <= 0 ? 0 : floor($count / $numPerPage) + 1;
        $curPageNo = $this->global_cur_page_no;
        $start_page_no = $this->global_cur_page_no;
        $end_page_no = $this->global_cur_page_no + 50;
        $success_count = 0;
        while ($curPageNo <= $totalPage && $curPageNo < $end_page_no) {
            $start = $numPerPage * ($curPageNo - 1);
            $command = $connection->createCommand("SELECT * FROM user_info order by id asc limit ".$start.", ".$numPerPage);
            $userInfo = $command->queryAll();

            foreach($userInfo as $val){
                $customer = new Customer;
                // $customer->id = $val['id'];
                $customer->customer_name = $val['name'];
                $customer->customer_sex = $val['gender'];
                //$customer->customer_birth = intval(strtotime($val['birthday']));
                $customer->customer_photo = '';
                $customer->customer_phone = $val['telphone'];
                $customer->customer_email = $val['email'];

                $customer->operation_area_id = 0;
				$customer->operation_area_name = '';
				$customer->operation_city_id = 0;
				$customer->operation_city_name = '';
                $customer->customer_level = $val['level'];
                $customer->customer_complaint_times = 0;
                $customer->customer_login_ip = '';
                $customer->customer_login_time = 0;
                $customer->customer_is_vip = $val['user_type'];
                $customer->created_at = strtotime($val['create_time']);
                $customer->updated_at = 0;
                $customer->is_del = $val['is_block'];
                $customer->validate();
                if ($customer->hasErrors()) {
                    // var_dump($customer->getErrors());
                    // die();
                    echo "<br/>数据有误略过";
                    continue;
                }
                $customer->save();

				$customerAddress = new CustomerAddress;
                $customerAddress->customer_id = $customer->id;
                $customerAddress->operation_province_id = 110000;
                $customerAddress->operation_city_id = 110100;
                $customerAddress->operation_area_id = 110105;

                $customerAddress->operation_province_name = '北京';
                $customerAddress->operation_city_name = '北京市';
                $customerAddress->operation_area_name = '朝阳区';

                $customerAddress->operation_province_short_name = '北京';
                $customerAddress->operation_city_short_name = '北京';
                $customerAddress->operation_area_short_name = '朝阳';

                $customerAddress->customer_address_detail = 'SOHO一期2单元908';
                $customerAddress->customer_address_status = 1;
                $customerAddress->customer_address_longitude = '116.48641';
                $customerAddress->customer_address_latitude = '39.92149';
                $customerAddress->customer_address_nickname = '测试昵称';
                $customerAddress->customer_address_phone = '18519654001';
                $customerAddress->created_at = time();
                $customerAddress->updated_at = 0;
                $customerAddress->is_del = 0;
                if ($customerAddress->hasErrors()) {
                    var_dump($customer->getErrors());
                    die();
                }
                $customerAddress->save();
				
				

                $customerBalance = new CustomerExtBalance;
                $customerBalance->customer_id = $customer->id;
				$customerBalance->customer_phone = $customer->customer_phone;
                $customerBalance->customer_balance = $val['charge_money'];
                $customerBalance->created_at = time();
                $customerBalance->updated_at = 0;
                $customerBalance->is_del = 0;
                $customerBalance->validate();
                if ($customerBalance->hasErrors()) {
                    // var_dump($customerBalance->getErrors());
                    // die();
                    echo "<br/>数据有误略过";
                    continue;
                }
                $customerBalance->save();

                $customerScore = new CustomerExtScore;
                $customerScore->customer_id = $customer->id;
				$customerScore->customer_phone = $customer->customer_phone;
                $customerScore->customer_score = 0;
                $customerScore->created_at = time();
                $customerScore->updated_at = 0;
                $customerScore->is_del = 0;
                $customerScore->validate();
                if ($customerScore->hasErrors()) {
                    echo "<br/>数据有误略过";
                    continue;
                }
                $customerScore->save();

                //$command = $connection->createCommand("SELECT * FROM user_comment where id=".$val['id']);
                //$userComment = $command->queryAll();
                //if ($userComment) {
                 //   $customerComment = new CustomerComment;
                  //  $customerComment->customer_id = $customer->id;
                    //$customerComment->order_id = $userComment[0]['order_id'];
                   // $customerComment->customer_comment_phone = $userComment[0]['user_telephone'];
                    //$customerComment->customer_comment_content = $userComment[0]['comment'];
                   // $customerComment->customer_comment_level = $userComment[0]['star_rate'];
                    
                   // switch ($userComment[0]['is_anonymous']) {
                    //    case 1:
                           // $customer_comment_anonymous = 0;
                     //       break;
                     //   case 0:
                            //$customer_comment_anonymous = 1;
                     //    //   break;
                     //   
                    //    default:
                            # code...
                   //         break;
                   // }
                   // $customerComment->customer_comment_anonymous = $customer_comment_anonymous;
                    
                   // $customerComment->created_at = strtotime($userComment[0]['create_time']);
                    //$customerComment->is_del = $userComment[0]['is_hide'];
                   // $customerComment->validate();
                    //if ($customerComment->hasErrors()) {
                     //   echo "<br/>数据有误略过";
                    //    continue;
                   // }
                   // $customerComment->save();
                //}
                
                
                $customerExtSrc = new CustomerExtSrc;
                $customerExtSrc->customer_id = $customer->id;
				$customerExtSrc->finance_order_channal_id = 0;
                $customerExtSrc->platform_name = 'Android';
                $customerExtSrc->channal_name = '美团';
                $customerExtSrc->platform_ename = 'android';
                $customerExtSrc->channal_ename = 'meituan';
                $customerExtSrc->device_name = '';
                $customerExtSrc->device_no = '';
                $customerExtSrc->created_at = time();
                $customerExtSrc->updated_at = 0;
                $customerExtSrc->is_del = 0;
                $customerExtSrc->validate();
                if ($customerExtSrc->hasErrors()) {
                    var_dump($customerExtSrc->getErrors());
                    die();
                }
                $customerExtSrc->save();

                $success_count ++;
                echo "<br/>成功导入".$success_count."条数据，原id=".$val['id']."现在id=".$customer->id;
            }
            echo "<br/>成功导入" . $numPerPage * $curPageNo;
            $curPageNo ++;
            $this->global_cur_page_no = $curPageNo;
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

    public function actionData2(){
        $customer = new Customer;
        $customer->customer_name = 'ceshi';
        $customer->customer_sex = 1;
        //$customer->customer_birth = intval(strtotime($val['birthday']));
        $customer->customer_photo = '';
        $customer->customer_phone = '15623564859';
        $customer->customer_email = '1483674610@qq.com';

        $customer->operation_area_id = 0;
		$customer->operation_area_name = '';
		$customer->operation_city_id = 0;
		$customer->operation_city_name = '';
        $customer->customer_level = 1;
        $customer->customer_complaint_times = 0;
        $customer->customer_login_ip = '';
        $customer->customer_login_time = 0;
        $customer->customer_is_vip = 1;
        $customer->created_at = time();
        $customer->updated_at = 0;
        $customer->is_del = 0;
        $customer->validate();
        $customer->save();

		$customerAddress = new CustomerAddress;
        $customerAddress->customer_id = $customer->id;
        $customerAddress->operation_province_id = 110000;
        $customerAddress->operation_city_id = 110100;
        $customerAddress->operation_area_id = 110105;

        $customerAddress->operation_province_name = '北京';
        $customerAddress->operation_city_name = '北京市';
        $customerAddress->operation_area_name = '朝阳区';

        $customerAddress->operation_province_short_name = '北京';
        $customerAddress->operation_city_short_name = '北京';
        $customerAddress->operation_area_short_name = '朝阳';

        $customerAddress->customer_address_detail = 'SOHO一期2单元908';
        $customerAddress->customer_address_status = 1;
        $customerAddress->customer_address_longitude = '116.48641';
        $customerAddress->customer_address_latitude = '39.92149';
        $customerAddress->customer_address_nickname = '测试昵称';
        $customerAddress->customer_address_phone = '18519654001';
        $customerAddress->created_at = time();
        $customerAddress->updated_at = 0;
        $customerAddress->is_del = 0;
        $customerAddress->save();
		
		

        $customerBalance = new CustomerExtBalance;
        $customerBalance->customer_id = $customer->id;
		$customerBalance->customer_phone = $customer->customer_phone;
        $customerBalance->customer_balance = 100.20;
        $customerBalance->created_at = time();
        $customerBalance->updated_at = 0;
        $customerBalance->is_del = 0;
        $customerBalance->validate();
        $customerBalance->save();

        $customerScore = new CustomerExtScore;
        $customerScore->customer_id = $customer->id;
		$customerScore->customer_phone = $customer->customer_phone;
        $customerScore->customer_score = 0;
        $customerScore->created_at = time();
        $customerScore->updated_at = 0;
        $customerScore->is_del = 0;
        $customerScore->validate();
        $customerScore->save();

        //$command = $connection->createCommand("SELECT * FROM user_comment where id=".$val['id']);
        //$userComment = $command->queryAll();
        //if ($userComment) {
         //   $customerComment = new CustomerComment;
          //  $customerComment->customer_id = $customer->id;
            //$customerComment->order_id = $userComment[0]['order_id'];
           // $customerComment->customer_comment_phone = $userComment[0]['user_telephone'];
            //$customerComment->customer_comment_content = $userComment[0]['comment'];
           // $customerComment->customer_comment_level = $userComment[0]['star_rate'];
            
           // switch ($userComment[0]['is_anonymous']) {
            //    case 1:
                   // $customer_comment_anonymous = 0;
             //       break;
             //   case 0:
                    //$customer_comment_anonymous = 1;
             //    //   break;
             //   
            //    default:
                    # code...
           //         break;
           // }
           // $customerComment->customer_comment_anonymous = $customer_comment_anonymous;
            
           // $customerComment->created_at = strtotime($userComment[0]['create_time']);
            //$customerComment->is_del = $userComment[0]['is_hide'];
           // $customerComment->validate();
            //if ($customerComment->hasErrors()) {
             //   echo "<br/>数据有误略过";
            //    continue;
           // }
           // $customerComment->save();
        //}
        
        
        $customerExtSrc = new CustomerExtSrc;
        $customerExtSrc->customer_id = $customer->id;
		$customerExtSrc->finance_order_channal_id = 0;
        $customerExtSrc->platform_name = 'Android';
        $customerExtSrc->channal_name = '美团';
        $customerExtSrc->platform_ename = 'android';
        $customerExtSrc->channal_ename = 'meituan';
        $customerExtSrc->device_name = '';
        $customerExtSrc->device_no = '';
        $customerExtSrc->created_at = time();
        $customerExtSrc->updated_at = 0;
        $customerExtSrc->is_del = 0;
        $customerExtSrc->validate();
        $customerExtSrc->save();
    }

    public function actionData3(){
        $customer = Customer::find()->orderBy('id asc')->one();
        $customer_id = $customer->id;
        $res = CustomerAddress::addAddress($customer_id, 191, 'SOHO一期2单元908', '测试昵称', '18519999999');
        $res = CustomerAddress::addAddress($customer_id, 191, 'SOHO一期2单元719', '测试昵称', '18519999999');
        
    }

    

    public function actionData5(){
        //创建客户
        $customer = new \core\models\customer\Customer;

        $customer->customer_name = '刘道强';
        $customer->customer_sex = 1;
        $customer->customer_phone = '18519654001';
        $customer->customer_is_vip = 1;
        $customer->created_at = time();
        $customer->updated_at = 0;
        $customer->is_del = 0;
        $customer->customer_del_reason = '';
        $customer->validate();
        if ($customer->hasErrors()) {
            var_dump($customer->getErrors());
        }
        $customer->save();
        // exit();

 

        //创建客户余额
        $customerExtBalance = new \core\models\customer\CustomerExtBalance;
        $customerExtBalance->customer_id = $customer->id;
        $customerExtBalance->customer_balance = 100;
        $customerExtBalance->created_at = time();
        $customerExtBalance->updated_at = 0;
        $customerExtBalance->is_del = 0;
        $customerExtBalance->validate();
        if ($customerExtBalance->hasErrors()) {
            var_dump($customerExtBalance->getErrors());
        }
        $customerExtBalance->save();

        //创建客户积分
        $customerExtScore = new \core\models\customer\CustomerExtScore;
        $customerExtScore->customer_id = $customer->id;
        $customerExtScore->customer_score = 10000;
        $customerExtScore->created_at = time();
        $customerExtScore->updated_at = 0;
        $customerExtScore->is_del = 0;
        $customerExtScore->validate();
        if ($customerExtScore->hasErrors()) {
            var_dump($customerExtScore->getErrors());
        }
        $customerExtScore->save();

        //创建客户服务地址
        $customerAddress = new \core\models\customer\CustomerAddress;
        $customerAddress->customer_id = $customer->id;

        //根据区名查询省市区
        $operationArea = \dbbase\models\Operation\CommonOperationArea::find()->where([
            'level'=>3,
            ])->asArray()->one();
        
        $operation_area_id = $operationArea['id'];
        $operation_area_name = $operationArea['area_name'];
        $operation_area_short_name = $operationArea['short_name'];
        $operation_city_id = $operationArea['parent_id'];

        $operation_longitude = $operationArea['longitude'];
        $operation_latitude = $operationArea['latitude'];

        $operationCity = \dbbase\models\Operation\CommonOperationArea::find()->where([
            'id'=>$operation_city_id,
            'level'=>2,
            ])->asArray()->one();
        $operation_city_id = $operationCity['id'];
        $operation_city_name = $operationCity['area_name'];
        $operation_city_short_name = $operationCity['short_name'];
        $operation_province_id = $operationCity['parent_id'];

        $operationProvince = \dbbase\models\Operation\CommonOperationArea::find()->where([
            'id'=>$operation_province_id,
            'level'=>1,
            ])->asArray()->one();

        $operation_province_name = $operationProvince['area_name'];
        $operation_province_short_name = $operationProvince['short_name'];

        $customerAddress->operation_province_id = $operation_province_id;
        $customerAddress->operation_city_id = $operation_city_id;
        $customerAddress->operation_area_id = $operation_area_id;

        $customerAddress->operation_province_name = $operation_province_name;
        $customerAddress->operation_city_name = $operation_city_name;
        $customerAddress->operation_area_name = $operation_area_name;

        $customerAddress->operation_province_short_name = $operation_province_short_name;
        $customerAddress->operation_city_short_name = $operation_city_short_name;
        $customerAddress->operation_area_short_name = $operation_area_short_name;

        $customerAddress->customer_address_status = 1;
        $customerAddress->customer_address_detail = '测试详情地址';
        $customerAddress->customer_address_longitude = $operation_longitude;
        $customerAddress->customer_address_latitude = $operation_latitude;
        $customerAddress->customer_address_nickname = '刘道强';
        $customerAddress->customer_address_phone = '18519654001';
        $customerAddress->created_at = time();
        $customerAddress->updated_at = 0;
        $customerAddress->is_del = 0;
        $customerAddress->validate();
        if ($customerAddress->hasErrors()) {
            var_dump($customerAddress->getErrors());
        }
        $customerAddress->save();

    }

    public function actionTest1(){
        $res = \core\models\customer\CustomerAccessToken::generateAccessTokenForPop('18519654001', md5('18519654001pop_to_boss'), 'meituan');
        var_dump($res);
    }

    public function actionTest2(){
        $city = (new \yii\db\Query())->select('id, city_id, city_name')->from('ejj_operation_city')->where(['like', 'city_name', '北京'])->one();
        var_dump($city);

    }

	public function actionTest3(){
		$a = Customer::addCustomer('18519654005');
		var_dump($a);
	}

    public function actionTest(){
        // $customer = new Customer;
        // $res = $customer->decBalance(1, 0.01);
        // var_dump($res);

        // $test = new Customer;
        // $info = $test->incBalance(202, 10);
        // var_dump($info);

        // $res = \dbbase\models\CustomerBlockLog::addToBlock(17782, '测试');
        // var_dump($res);
        //$res = \core\models\customer\CustomerCode::generateAndSend('15623564859');
        //var_dump($res);

        // $res = \core\models\customer\CustomerCode::checkCode('18519654001', '9906');
        // var_dump($res);

        // $access_token = \core\models\customer\CustomerAccessToken::generateAccessToken('18519654001', '7441');
        // var_dump($access_token);

        // $res = \core\models\customer\CustomerAccessToken::checkAccessToken($access_token);
        // var_dump($res);

        // $res = \core\models\customer\CustomerAccessToken::getCustomer($access_token);
        // var_dump($res);

        // $res =  \core\models\customer\CustomerAddress::addAddress(1, '东城区', '详细地址', '刘道强', '18519654001');
        // var_dump($res);
		//$res = Customer::adminAddCustomer('18519654002');
		//var_dump($res);
		//$res = \core\models\customer\CustomerAccessToken::checkSign('18519654001', md5('18519654001'.'pop_to_boss'), 25);
		//var_dump($res);
//		$res = \core\models\customer\Customer::operateBalance(144, 90, 'aaaaaaaaaa', 0);
//		$res = \core\models\customer\Customer::operateBalance(1, 40, 'adfasd', -1);
		//$res = \core\models\customer\Customer::getWorkersByPhone('13436939482');
//        $res = \core\models\customer\CustomerAddress::addAddress(151, '北京', '北京市', '大兴区', 'SOHO一期2单元908', '测试昵称', '18519654001');
//        $res = \core\models\customer\CustomerCode::generateAndSend('18519654001');
        //$res = \core\models\customer\CustomerCode::isSendOver('15623564859');


//        $res = \core\models\customer\Customer::getCityNameById(150);

//        $res = \core\models\customer\Customer::addWorker(144, 2);
        //$res= \core\models\customer\Customer::addSrcByChannalId('18519654001', 1);
//        $res = \core\models\customer\CustomerAccessToken::generateAccessTokenForAppleChecker('18519654001');


//        $res = \core\models\customer\CustomerAccessToken::checkSign('18519654003', md5('18519654003pop_to_boss'),1);
        $res = \core\models\customer\CustomerAccessToken::generateAccessTokenForPop('18519654004', md5('18519654004pop_to_boss'), 1);
		var_dump($res);
    }

    public function actionTestAddress(){
        $addr = CustomerAddress::addAddress(1, '7777', '北京市', '9999', '光华路SOHO一期二单元', 'aaaaa', '18519654001');
    }
}
