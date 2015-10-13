<?php

namespace core\models;


use Yii;
// use common\models\Customer;
use common\models\CustomerAddress;
use common\models\CustomerWorker;
use common\models\Worker;
use common\models\CustomerExtBalance;
use common\models\GeneralRegion;
use yii\web\BadRequestHttpException;

class Customer extends \common\models\Customer
{

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
        $worker = [];
        foreach($customerWorker as $k=>$v)
        {
            $worker[$k] = $v->attributes;
            $worker[$k]['worker_name'] = Worker::findOne($v->worker_id)->worker_name;
        }
        return $worker;
    }
}
