<?php

namespace core\models\customer;


use Yii;
// use common\models\Customer;

use common\models\customer\CustomerExtSrc;
use core\models\customer\CustomerAddress;
use core\models\customer\CustomerWorker;
use common\models\Worker;
use core\models\customer\CustomerExtBalance;
use core\models\customer\CustomerExtScore;
use common\models\customer\GeneralRegion;
use yii\web\BadRequestHttpException;

class Customer extends \common\models\customer\Customer
{

	/**
	 * add customer while customer is not exist by phone
	 */
	public static function addCustomer($phone, $channal_id){
		$customer = self::find()->where(['customer_phone'=>$phone])->one();
		if($customer != NULL){
			return false;
		}else if(!preg_match('/^1([0-9]{9})/', $phone)){
			return false;
		}else{
			$transaction = \Yii::$app->db->beginTransaction();
			try{
				//customer basic info
				$customer = new Customer;
				$customer->customer_phone = $phone;
				$customer->created_at = time();
				$customer->updated_at = 0;
				$customer->is_del = 0;
				$customer->save();
			
				//customer balance
				$customerExtBalance = new CustomerExtBalance;
				$customerExtBalance->customer_id = $customer->id;
				$customerExtBalance->customer_balance = 0;
				$customerExtBalance->created_at = time();
				$customerExtBalance->updated_at = 0;
				$customerExtBalance->is_del = 0;
				$customerExtBalance->save();

				//customer score
				$customerExtScore = new CustomerExtScore;
				$customerExtScore->customer_id = $customer->id;
				$customerExtScore->customer_score = 0;
				$customerExtScore->created_at = time();
				$customerExtScore->updated_at = 0;
				$customerExtScore->is_del = 0;
				$customerExtScore->save();

				self::addSrcByChannalId($phone, $channal_id);
				
				$transaction->commit();
				return true;
			}catch(\Exception $e){

				$transaction->rollback();
				return false;
			}
		}
	}

	/**
     * get complaint count by customer_phone
	 */
	public static function getComplaintCount($customer_phone){
		
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
        
        $customerBalance = CustomerExtBalance::find()->where(array(
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

    // public static function getCustomerAddresses($customer_id)
    // {
    //     $customerAddress = CustomerAddress::find()->where(array(
    //         'customer_id'=>$customer_id,
    //         ))->all();
    //     foreach ($customerAddress as $value) {
    //         $generalRegion = GeneralRegion::findOne($value['general_region_id']);
    //     }
    // }

    /**
     * 获取顾客地址集
     */
    public static function getCustomerAddresses($customer_id){
        $customer = self::findOne($customer_id);
        $customerAddresses = $customer->hasMany('\common\models\CustomerAddress', ['customer_id'=>'id'])->all();
        return $customerAddresses != NULL ? $customerAddresses : false;
    }

    /**
     * 新增顾客服务地址
     */
    public function addCustomerAddress($customer_id, $general_region_id, $detail, $nickname, $phone){
        
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            // 所有的查询都在主服务器上执行
            $customerAddresses = CustomerAddress::find()->where(array(
                'customer_id'=>$customer_id,
                ))->all();
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->validate();
                $customerAddress->save();
            }
            $customerAddress = new customerAddress;
            $customerAddress->customer_id = $customer_id;
            $customerAddress->general_region_id = $general_region_id;
            $customerAddress->customer_address_detail = $detail;
            $customerAddress->customer_address_status = 1;
            //根据地址获取经纬度信息
            $customerAddress->customer_address_longitude = '';
            $customerAddress->customer_address_latitude = '';
            $customerAddress->customer_address_nickname = $nickname;
            $customerAddress->customer_address_phone = $phone;
            $customerAddress->created_at = time();
            $customerAddress->updated_at = 0;
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            $customerAddress->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 修改顾客服务地址
     */
    public function updateCustomerAddress($customer_id, $general_region_id, $detail, $nickname, $phone){
        
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            // 所有的查询都在主服务器上执行
            $customerAddresses = CustomerAddress::find()->where(array(
                'customer_id'=>$customer_id,
                ))->all();
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->validate();
                $customerAddress->save();
            }
            $customerAddress = CustomerAddress::find()->where(array(
                'customer_id'=>$customer_id,
                ))->one();
            $customerAddress->general_region_id = $general_region_id;
            $customerAddress->customer_address_detail = $detail;
            $customerAddress->customer_address_status = 1;
            //根据地址获取经纬度信息
            $customerAddress->customer_address_longitude = '';
            $customerAddress->customer_address_latitude = '';
            $customerAddress->customer_address_nickname = $nickname;
            $customerAddress->customer_address_phone = $phone;
            $customerAddress->created_at = time();
            $customerAddress->updated_at = 0;
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            $customerAddress->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
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

	/*******************************************客户渠道**********************************************/
	/**
     * get all customer srcs
	 */
	public static function getAllSrcs(){
		$all_srcs = CustomerExtSrc::find()->asArray()->all();
		return $all_srcs;
	}

	/**
	 * get customer srcs
	 */
	public static function getSrcs($customer_phone){
		$srcs = CustomerExtSrc::find()->where(['customer_phone'=>$customer_phone])->asArray()->all();
		return $srcs;
	}

	/**
 	 * get customer first src
     */
	public static function getFirstSrc($customer_phone){
		$srcs = CustomerExtSrc::find()->where(['customer_phone'=>$customer_phone])->orderBy('created_at asc')->asArray()->one();
		return $srcs;
	}

	/**
     * add customer src by channal_id
	 */
	public static function addSrcByChannalId($customer_phone, $channal_id){
		$customer = self::find()->where(['customer_phone'=>$customer_phone])->asArray()->one();
		if(empty($customer)) return false;

		$channal_name = funcname($channal_id);
	
		$customerExtSrc = new CustomerExtSrc;
		$customerExtSrc->customer_id = $customer["id"];
		$customerExtSrc->customer_phone = $customer["customer_phone"];
		$customerExtSrc->finance_order_channal_id = $channal_id;
		$customerExtSrc->platform_name = "";
		$customerExtSrc->platform_ename = "";
		$customerExtSrc->channal_name = $channal_name;
		$customerExtSrc->channal_ename = "";
		$customerExtSrc->device_name = "";
		$customerExtSrc->device_no = "";
		$customerExtSrc->created_at = time();
		$customerExtSrc->updated_at = 0;
		$customerExtSrc->is_del = 0;
		if($customerExtSrc->validate()){
			$customerExtSrc->save();
			return true;
		}
		return false;
	}

	/**
     * add csutomer src by channal_name
	 */
	public static function addSrcByChannalName($customer_phone, $channal_name){
		$customer = self::find()->where(['customer_phone'=>$customer_phone])->asArray()->one();
		if(empty($customer)) return false;

		$channal_id = funcname($channal_name);
	
		$customerExtSrc = new CustomerExtSrc;
		$customerExtSrc->customer_id = $customer["id"];
		$customerExtSrc->customer_phone = $customer["customer_phone"];
		$customerExtSrc->finance_order_channal_id = $channal_id;
		$customerExtSrc->platform_name = "";
		$customerExtSrc->platform_ename = "";
		$customerExtSrc->channal_name = $channal_name;
		$customerExtSrc->channal_ename = "";
		$customerExtSrc->device_name = "";
		$customerExtSrc->device_no = "";
		$customerExtSrc->created_at = time();
		$customerExtSrc->updated_at = 0;
		$customerExtSrc->is_del = 0;
		if($customerExtSrc->validate()){
			$customerExtSrc->save();
			return true;
		}
		return false;
		
		
	}
}
