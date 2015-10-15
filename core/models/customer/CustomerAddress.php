<?php

namespace core\models\customer;

use Yii;
use common\models\GeneralRegion;

/**
 * This is the model class for table "{{%customer_address}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $general_region_id
 * @property string $customer_address_detail
 * @property integer $customer_address_status
 * @property double $customer_address_longitude
 * @property double $customer_address_latitude
 * @property string $customer_address_nickname
 * @property string $customer_address_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerAddress extends \common\models\CustomerAddress
{
    
    /**
     * 新增服务地址
     */
    public static function addAddress($customer_id, $general_region_id, $customer_address_detail, $customer_address_nickname, $customer_address_phone){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerAddress = new CustomerAddress;
            $customerAddress->customer_id = $customer_id;
            $customerAddress->general_region_id = $general_region_id;
            $customerAddress->customer_address_status = 1;
            $customerAddress->customer_address_detail = $customer_address_detail;
            $customerAddress->customer_address_longitude = '';
            $customerAddress->customer_address_latitude = '';
            $customerAddress->customer_address_nickname = $customer_address_nickname;
            $customerAddress->customer_address_phone = $customer_address_phone;
            $customerAddress->created_at = time();
            $customerAddress->updated_at = 0;
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            if ($customerAddress->hasErrors()) {
                return false;
            }
            $customerAddress->save();
            $customerAddresses = CustomerAddress::findAll('customer_id=:customer_id and id!=:id', 
                [':customer_id'=>$customer_id, ':id'=>$customerAddress->id]);
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->save();
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
        
    }

    /**
     * 软删除服务地址
     */
    public static function deleteAddress($id){
        $customerAddress = CustomerAddress::findOne($id);
        if ($customerAddress == NULL) {
            return false;
        }
        CustomerAddress::deleteAll(['id'=>$id]);
        $customerAddress = CustomerAddress::findOne($id);
        if ($customerAddress == NULL) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 修改服务地址
     */
    public static function updateAddress($id, $general_region_id, $customer_address_detail, $customer_address_nickname, $customer_address_phone){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerAddress = self::findOne($id);
            // $customerAddress->customer_id = $customer_id;
            $customerAddress->general_region_id = $general_region_id;
            $customerAddress->customer_address_status = 1;
            $customerAddress->customer_address_detail = $customer_address_detail;
            $customerAddress->customer_address_longitude = '';
            $customerAddress->customer_address_latitude = '';
            $customerAddress->customer_address_nickname = $customer_address_nickname;
            $customerAddress->customer_address_phone = $customer_address_phone;
            // $customerAddress->created_at = time();
            $customerAddress->updated_at = time();
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            if ($customerAddress->hasErrors()) {
                return false;
            }
            $customerAddress->save();
            $customerAddresses = CustomerAddress::findAll('customer_id=:customer_id and id!=:id', 
                [':customer_id'=>$customer_id, ':id'=>$customerAddress->id]);
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->save();
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 列出客户全部服务地址
     */
    public static function listAddress($customer_id){
        $customerAddresses = self::find()->where(['customer_id'=>$customer_id])->all();
        return $customerAddresses;
    }

    /**
     * 客户服务地址列表已数组形式，元素为字符串
     */
    public static function listAddressArr($customer_id){
        $customerAddresses = CustomerAddress::find()->where(['customer_id'=>$customer_id])->all();
        $customerAddressArr = array();
        if (!empty($customerAddresses)) {
            foreach ($customerAddresses as $value) {
                if ($value != NULL) {
                    $general_region_id = $value->general_region_id;
                    $generalRegion = GeneralRegion::findOne($general_region_id);
                    if ($generalRegion != NULL) {
                        $customerAddressArr[]['general_region_province_name'] = $generalRegion->general_region_province_name;
                        $customerAddressArr[]['general_region_city_name'] = $generalRegion->general_region_city_name;
                        $customerAddressArr[]['general_region_area_name'] = $generalRegion->general_region_area_name;
                        $customerAddressArr[]['customer_address_detail'] =  $value->customer_address_detail;
                        $customerAddressArr[]['customer_address_nickname'] =  $value->customer_address_nickname;
                        $customerAddressArr[]['customer_address_phone'] =  $value->customer_address_phone;
                        $customerAddressArr[]['province-city-area-detail'] = 
                            $customerAddressArr[]['general_region_province_name']
                            .$customerAddressArr[]['general_region_city_name']
                            .$customerAddressArr[]['general_region_area_name']
                            .$customerAddressArr[]['customer_address_detail'];
                    }
                }
            }
        }
        return $customerAddressArr;
    }

    /**
     * 根据地址id查询地址
     */
    public static function getAddress($id){
        return self::findOne($id) ? self::findOne($id) : false;
    }
}
