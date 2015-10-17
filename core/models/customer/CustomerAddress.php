<?php

namespace core\models\customer;
use Yii;
use common\models\GeneralRegion;
use common\models\Operation\CommonOperationArea;

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
    public static function addAddress($customer_id, $operation_area_name, $customer_address_detail, $customer_address_nickname, $customer_address_phone){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerAddress = new CustomerAddress;
            $customerAddress->customer_id = $customer_id;

            //根据区名查询省市区
            $operationArea = CommonOperationArea::find()->where([
                'area_name'=>$operation_area_name,
                'level'=>3,
                ])->asArray()->one();
            
            $operation_area_id = $operationArea['id'];
            $operation_area_name = $operationArea['area_name'];
            $operation_area_short_name = $operationArea['short_name'];
            $operation_city_id = $operationArea['parent_id'];

            $operation_longitude = $operationArea['longitude'];
            $operation_latitude = $operationArea['latitude'];

            $operationCity = CommonOperationArea::find()->where([
                'id'=>$operation_city_id,
                'level'=>2,
                ])->asArray()->one();
            $operation_city_id = $operationCity['id'];
            $operation_city_name = $operationCity['area_name'];
            $operation_city_short_name = $operationCity['short_name'];
            $operation_province_id = $operationCity['parent_id'];

            $operationProvince = CommonOperationArea::find()->where([
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
            $customerAddress->customer_address_detail = $customer_address_detail;
            $customerAddress->customer_address_longitude = $operation_longitude;
            $customerAddress->customer_address_latitude = $operation_latitude;
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
            return $customerAddress;
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
    public static function updateAddress($id, $operation_area_name, $customer_address_detail, $customer_address_nickname, $customer_address_phone){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerAddress = self::findOne($id);
            //根据区名查询省市区
            $operationArea = CommonOperationArea::find()->where([
                'area_name'=>$operation_area_name,
                'level'=>3,
                ])->asArray()->one();
            
            $operation_area_id = $operationArea['id'];
            $operation_area_name = $operationArea['area_name'];
            $operation_area_short_name = $operationArea['short_name'];
            $operation_city_id = $operationArea['parent_id'];

            $operation_longitude = $operationArea['longitude'];
            $operation_latitude = $operationArea['latitude'];

            $operationCity = CommonOperationArea::find()->where([
                'id'=>$operation_city_id,
                'level'=>2,
                ])->asArray()->one();
            $operation_city_id = $operationCity['id'];
            $operation_city_name = $operationCity['area_name'];
            $operation_city_short_name = $operationCity['short_name'];
            $operation_province_id = $operationCity['parent_id'];

            $operationProvince = CommonOperationArea::find()->where([
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
            $customerAddress->customer_address_detail = $customer_address_detail;
            $customerAddress->customer_address_longitude = $operation_longitude;
            $customerAddress->customer_address_latitude = $operation_latitude;
            $customerAddress->customer_address_nickname = $customer_address_nickname;
            $customerAddress->customer_address_phone = $customer_address_phone;

            $customerAddress->updated_at = time();
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            $customerAddress->save();
            $customerAddresses = CustomerAddress::findAll('customer_id=:customer_id and id!=:id', 
                [':customer_id'=>$customerAddress->customer_id, ':id'=>$customerAddress->id]);
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->save();
            }
            $transaction->commit();
            return $customerAddress;
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
    public static function getAddressArr($customer_id){
        $customerAddresses = self::find()->where(['customer_id'=>$customer_id])->asArray()->all();
        $customerAddressArr = array();
        if (!empty($customerAddresses)) {
            foreach ($customerAddresses as $value) {
                if (!empty($value)) {
                    $general_region_id = $value['general_region_id'];
                    $generalRegion = GeneralRegion::find()->where(['id'=>$general_region_id])->asArray()->one();
                    if (!empty($generalRegion)) {
                        $customerAddressArr[] = array(
                            'general_region_province_name'=>$generalRegion['general_region_province_name'],
                            'general_region_city_name'=>$generalRegion['general_region_city_name'],
                            'general_region_area_name'=>$generalRegion['general_region_area_name'],
                            'customer_address_detail'=>$value['customer_address_detail'],
                            'customer_address_nickname'=>$value['customer_address_nickname'],
                            'customer_address_phone'=>$value['customer_address_phone'],
                            'province_city_area_detail'=>$generalRegion['general_region_province_name']
                                .$generalRegion['general_region_city_name']
                                .$generalRegion['general_region_area_name']
                                .$value['customer_address_detail'],
                        );
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
