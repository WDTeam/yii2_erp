<?php

namespace core\models\customer;


use Yii;
use yii\web\BadRequestHttpException;

use core\models\customer\CustomerAddress;
use core\models\customer\CustomerWorker;
use core\models\customer\CustomerChannal;
use core\models\customer\CustomerExtSrc;
use core\models\customer\CustomerExtBalance;
use core\models\customer\CustomerExtScore;
use common\models\Worker;

use common\models\OrderExtCustomer;
use common\models\Operation\CommonOperationCity;

class Customer extends \common\models\Customer
{
	
	/**
 	 * add customer while ordering by admin
	 */
	public static function adminAddCustomer($phone){
		$has_customer = self::hasCustomer($phone);
		if($has_customer){
			return false;
		}
		$transaction = \Yii::$app->db->beginTransaction();
		try{
			//customer basic info
			$customer = new Customer;
			$customer->customer_phone = $phone;
			$customer->created_at = time();
			$customer->updated_at = 0;
			$customer->is_del = 0;
			$customer->validate();
			if($customer->hasErrors()){
				var_dump($customer->getErrors());
				exit();
			}
			$customer->save();
		
			//customer address
			//customer's worker
			//customer channal and src
			$customerChannal = CustomerChannal::find()->where(['channal_name'=>'客服下单'])->one();
			if($customerChannal == NULL){
				$customerChannal = new CustomerChannal;
				$customerChannal->channal_name = '客服下单';
				$customerChannal->channal_ename = 'admin';
				$customerChannal->pid = 0;
				$customerChannal->created_at = time();
				$customerChannal->updated_at = 0;
				$customerChannal->is_del = 0;
				$customerChannal->validate();
				if($customerChannal->hasErrors()){
					var_dump($customerChannal->getErrors());
					exit();
				}
				$customerChannal->save();
			}
		
		
			$customerExtSrc = new CustomerExtSrc;
			$customerExtSrc->customer_id = $customer->id;
			$customerExtSrc->platform_id = 0;
			$customerExtSrc->platform_name = '';
			$customerExtSrc->platform_ename = '';
			$customerExtSrc->channal_id = $customerChannal->id;
			$customerExtSrc->channal_name = $customerChannal->channal_name;
			$customerExtSrc->channal_ename = $customerChannal->channal_ename;
			$customerExtSrc->device_name = '';
			$customerExtSrc->device_no = '';
			$customerExtSrc->created_at = time();
			$customerExtSrc->updated_at = 0;
			$customerExtSrc->is_del = 0;
			$customerExtSrc->validate();
			if($customerExtSrc->hasErrors()){
				var_dump($customerExtSrc->getErrors());
				exit();
			}
			$customerExtSrc->save();
			//customer balance and score
			$customerExtBalance = new CustomerExtBalance;
			$customerExtBalance->customer_id = $customer->id;
			$customerExtBalance->customer_balance = 0;
			$customerExtBalance->created_at = time();
			$customerExtBalance->updated_at = 0;
			$customerExtBalance->is_del = 0;
			$customerExtBalance->validate();
			if($customerExtBalance->hasErrors()){
				var_dump($customerExtBalance->getErrors());
				exit();
			}
			$customerExtBalance->save();

			$customerExtScore = new CustomerExtScore;
			$customerExtScore->customer_id = $customer->id;
			$customerExtScore->customer_score = 0;
			$customerExtScore->created_at = time();
			$customerExtScore->updated_at = 0;
			$customerExtScore->is_del = 0;
			$customerExtScore->validate();
			if($customerExtScore->hasErrors()){
				var_dump($customerExtScore->getErrors());
				exit();
			}
			$customerExtScore->save();
			$transaction->commit();
			return true;
		}catch(\Exception $e){
			$transaction->rollback();
			return false;
		}
	}
	
    /**
     * 是否有该手机号的客户
     */
    public static function hasCustomer($phone){
        $customer = self::find()->where(['customer_phone'=>$phone])->one();
        return $customer == NULL ? false : true;
    }


    /**
     * 根据customer_id获取顾客信息
     */
    public static function getCustomerById($customer_id)
    {
        $customer = Customer::findOne($customer_id);

        return $customer != NULL ? $customer : false;
    }
    
    /**
     * 根据手机号获取顾客基本信息
     */
    public static function getCustomerInfo($phone)
    {
        $customer = Customer::find()->where(array(
            'customer_phone'=>$phone,
            ))->one();
        if ($customer == NULL) {
            return false;
        }
        
        $customerBalance = \common\models\CustomerExtBalance::find()->where(array(
            'customer_id'=>$customer->id,
            ))->one();
        // if ($customerBalance == NULL) {
        //     $customer_balance = 0;
        // }
        // $customer_balance = $customerBalance->customer_balance;
        $customer_balance = $customerBalance == NULL ? 0 : $customerBalance->customer_balance;
        return array(
            'id'=>$customer->id,
            'customer_phone'=>$phone,
            'customer_balance'=>$customer_balance,
            );
    }

    /**
     * 获取客户的手机号
     */
    public static function getCustomerPhone($customer_id)
    {
        $customer = self::findOne($customer_id);
        if ($customer == NULL) {
            return false;
        }else{
            return $customer->customer_phone;
        }
    }

    /**
     * 根据客户id获取常用阿姨列表
     */
    public function getCustomerWorkers($customer_id)
    {
        $customer = self::findOne($customer_id);
        $customerWorkers = $customer->hasMany('\common\models\customerWorker', ['customer_id'=>'id'])->all();
        return $customerWorkers != NULL ? $customerWorkers : false;
    }

 
   

    public static function getCustomerUsedWorkers($id)
    {
        $customerWorker = CustomerWorker::findAll(['customer_id'=>$id]);
        $workers = [];
        foreach($customerWorker as $k=>$v)
        {
            $worker = $v->attributes;
            $worker_model = Worker::findOne($v->worker_id);
            if(!empty($worker_model)) {
                $worker['worker_name'] = $worker_model->worker_name;
                $workers[]=$worker;
            }
        }
        return $workers;
    }

   
    /**
     * 客户城市
     */
    public static function getCityName($customer_id){
        $customer = self::findOne($customer_id);
        if ($customer == NULL) {
            return '-';
        }
        $operationCity = CommonOperationCity::findOne($customer->operation_city_id);
        return $operationCity == NULL ? '-' : $operationCity->city_name == '' ? '-' : $operationCity->city_name;
    }
}
