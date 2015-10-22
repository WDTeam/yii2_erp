<?php

namespace core\models\customer;


use Yii;
use yii\web\BadRequestHttpException;

use common\models\CustomerAddress;
use common\models\CustomerWorker;
use common\models\Worker;
//use common\models\CustomerExtBalance;
use common\models\GeneralRegion;
use common\models\OrderExtCustomer;
use common\models\Operation\CommonOperationCity;

class Customer extends \common\models\Customer
{

    /**
     * 是否有该手机号的客户
     */
    public static function hasCustomer($phone){
        $customer = self::find()->where(['customer_phone'=>$phone])->one();
        return $customer == NULL ? false : true;
    }
	
	/**
 	 * 根据手机号创建一个客户
	 */
	public static function addCustomer($phone){
		$
		$customer = new Customer;
		$customer->customer_phone = $phone;
		$customer->created_at = time();
		$customer->updated_at = 0;
		$customer->is_del = 0;
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
