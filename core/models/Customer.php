<?php

namespace core\models;

use Yii;
use core\models\Customer;
use core\models\customerAddress;
use yii\web\BadRequestHttpException;

class Customer extends \common\models\Customer
{
    /**
     * 根据手机号获取顾客基本信息
     */
    public function getCustomer($phone)
    {
        $customer = Customer::find()->where(array(
            'customer_phone'=>$phone,
            ))->one();
        return $customer;
    }

    /**
     * 获取顾客地址集
     */
    public function getCustomerAddresses($customer_id){
        $customerAddresses = $this::hasMany('\boss\models\customerAddress', 'customer_id', 'id');
        return $customerAddresses;
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
}
