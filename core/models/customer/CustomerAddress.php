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
            //先将该客户所有的地址状态设为0
            $customerAddress = self::find()->where(['customer_id'=>$customer_id])->all();
            if (!empty($customerAddress)) {
                foreach ($customerAddress as $address) {
                    $address->customer_address_status = 0;
                    $address->validate();
                    $address->save();
                }
            }

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

            $city_encode = urlencode($operation_city_name);
            $detail_encode = urlencode($customer_address_detail);
            $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
            $address_decode = json_decode($address_encode, true);
            $operation_longitude = $address_decode['result']['location']['lng'];
            $operation_latitude = $address_decode['result']['location']['lat'];

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
            $customerAddress->save();
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
            //先将该客户所有的地址状态设为0
            $customerAddress = self::findOne($id);
            if ($customerAddress != NULL) {
                $customer_id = $customerAddress->customer_id;
                $customerAddresses = self::find()->where(['customer_id'=>$customer_id])->all();
                foreach ($customerAddresses as $address) {
                    $address->customer_address_status = 0;
                    $address->validate();
                    $address->save();
                }
            }
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

            // $operation_longitude = $operationArea['longitude'];
            // $operation_latitude = $operationArea['latitude'];
            $city_encode = urlencode($operation_city_name);
            $detail_encode = urlencode($customer_address_detail);
            $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
            $address_decode = json_decode($address_encode);
            $operation_longitude = $address_decode['result']['location']['lng'];
            $operation_latitude = $address_decode['result']['location']['lat'];

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
     * 根据地址id查询地址
     */
    public static function getAddress($id){
        return self::findOne($id) ? self::findOne($id) : false;
    }
}
