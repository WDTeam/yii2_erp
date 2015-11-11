<?php

namespace core\models\customer;
use Yii;
use dbbase\models\GeneralRegion;

use dbbase\models\operation\OperationArea;


use core\models\customer\Customer;

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
class CustomerAddress extends \dbbase\models\customer\CustomerAddress
{

    /**
     * add customer address
     * @param $customer_id
     * @param $operation_province_name
     * @param $operation_city_name
     * @param $operation_area_name
     * @param $customer_address_detail
     * @param string $customer_address_nickname
     * @param $customer_address_phone
     * @return array|bool|CustomerAddress|null|\yii\db\ActiveRecord
     */
    public static function addAddress($customer_id, $operation_province_name, $operation_city_name, $operation_area_name, $customer_address_detail, $customer_address_nickname='', $customer_address_phone){
        $customer_address = self::getAddressOne($customer_id, $operation_province_name, $operation_city_name, $operation_area_name, $customer_address_detail, $customer_address_nickname, $customer_address_phone);

        //address for custoemr is unique
        if(!empty($customer_address)){
            return $customer_address;
        }
        
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
			$operationProvince = OperationArea::find()->where([
				'level'=>1,
				])->andWhere(['like', 'area_name', $operation_province_name])->asArray()->one();
			$operation_province_id = $operationProvince['id'];
			$operation_province_name = $operationProvince['area_name'];
            $operation_province_short_name = $operationProvince['short_name'];

			$operationCity = OperationArea::find()->where([
				'level'=>2,
				])->andWhere(['like', 'area_name', $operation_city_name])->asArray()->one();
			$operation_city_id = $operationCity['id'];
			$operation_city_name = $operationCity['area_name'];
            $operation_city_short_name = $operationCity['short_name'];

			$operationArea = OperationArea::find()->where([
				'level'=>3,
				])->andWhere(['like', 'area_name', $operation_area_name])->asArray()->one();
			$operation_area_id = $operationArea['id'];
			$operation_area_name = $operationArea['area_name'];
            $operation_area_short_name = $operationArea['short_name'];


            $city_encode = urlencode($operation_city_name);
            $detail_encode = urlencode($operation_province_name.$operation_city_name.$operation_area_name.$customer_address_detail);
            $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
            $address_decode = json_decode($address_encode, true);

            /**
             * http://developer.baidu.com/map/index.php?title=webapi/guide/webservice-geocoding
             * 名称           类型          说明
             * status        Int           返回结果状态值， 成功返回0，其他值请查看下方返回码状态表。
             * location      object        经纬度坐标
             *  lat          float         纬度值
             *  lng          float         经度值
             * precise       Int           位置的附加信息，是否精确查找。1为精确查找，0为不精确。
             * confidence    Int           可信度
             * level         string        地址类型
             * && $address_decode['result']['precise'] == 1
             */
			if($address_decode['status'] == 0){
				$operation_longitude = $address_decode['result']['location']['lng'];
            	$operation_latitude = $address_decode['result']['location']['lat'];
			}else{
				$operation_longitude = '';
	  			$operation_latitude = '';
			}

            //if the address is the first address for customer, than customer 's city name is getted by
            $address_list = self::listAddress($customer_id);
            if(!$address_list){
                $customer = Customer::getCustomerById($customer_id);
                $customer->operation_city_id = $operation_city_id;
                $customer->operation_city_name = $operation_city_name;
                $customer->updated_at = time();
                $customer->save();
            }

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

            $customerAddress->save();
            $transaction->commit();
            return $customerAddress;
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
    }

    /**
     * delete customer address
     * @param $id
     * @return bool
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
     * update customer address
     * @param $id
     * @param $operation_province_name
     * @param $operation_city_name
     * @param $operation_area_name
     * @param $customer_address_detail
     * @param string $customer_address_nickname
     * @param $customer_address_phone
     * @return bool|null|static
     */
    public static function updateAddress($id, $operation_province_name, $operation_city_name, $operation_area_name, $customer_address_detail, $customer_address_nickname='', $customer_address_phone){
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
//            $operationProvince = OperationArea::find()->where([
//				'area_name'=>$operation_province_name,
//				'level'=>1,
//				])->asArray()->one();
            $operationProvince = OperationArea::find()->where([
                'level'=>1,
                ])->andWhere(['like', 'area_name', $operation_province_name])->asArray()->one();
			$operation_province_id = $operationProvince['id'];
			$operation_province_name = $operationProvince['area_name'];
            $operation_province_short_name = $operationProvince['short_name'];

//			$operationCity = OperationArea::find()->where([
//				'area_name'=>$operation_city_name,
//				'level'=>2,
//				])->asArray()->one();
            $operationCity = OperationArea::find()->where([
                'level'=>2,
                ])->andWhere(['like', 'area_name', $operation_city_name])->asArray()->one();
			$operation_city_id = $operationCity['id'];
			$operation_city_name = $operationCity['area_name'];
            $operation_city_short_name = $operationCity['short_name'];

//			$operationArea = OperationArea::find()->where([
//				'area_name'=>$operation_area_name,
//				'level'=>3,
//				])->asArray()->one();
            $operationArea = OperationArea::find()->where([
                'level'=>3,
                ])->andWhere(['like', 'area_name', $operation_area_name])->asArray()->one();
			$operation_area_id = $operationArea['id'];
			$operation_area_name = $operationArea['area_name'];
            $operation_area_short_name = $operationArea['short_name'];

            // $operation_longitude = $operationArea['longitude'];
            // $operation_latitude = $operationArea['latitude'];
            $city_encode = urlencode($operation_city_name);
            $detail_encode = urlencode($operation_province_name.$operation_city_name.$operation_area_name.$customer_address_detail);
            $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
            $address_decode = json_decode($address_encode,true);
            $operation_longitude = $address_decode['result']['location']['lng'];
            $operation_latitude = $address_decode['result']['location']['lat'];

            //update customer 's city name while updating address
            $customer_id = $customerAddress['customer_id'];
            $id = $customerAddress['id'];
            if(self::isFirstAddress($customer_id, $id)){
                $customer = Customer::getCustomerById($customer_id);
                $customer->operation_city_id = $operation_city_id;
                $customer->operation_city_name = $operation_city_name;
                $customer->updated_at = time();
                $customer->save();
            }

            //update address
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
     * add address for pop while area name and province are unknown
     * @param $customer_id
     * @param $customer_address_phone
     * @param $city_name
     * @param $address
     * @return array|bool|CustomerAddress|null|\yii\db\ActiveRecord
     */
    public static function addAddressForPop($customer_id, $customer_address_phone, $city_name, $address){
		try{
		    $customerAddress = self::find()->where(['customer_id' => $customer_id, 'customer_address_detail' => $address])->one();
		    if (!empty($customerAddress)) {
		        // found the address
		        return $customerAddress;
		    }
		    // 地址解析   
		    $city_encode = urlencode($city_name);
		    $detail_encode = urlencode($address);
		    $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
		    $address_decode=json_decode($address_encode);
		    //逆地址解析
		    $lat = $address_decode->result->location->lat;
		    $lng = $address_decode->result->location->lng;
		    $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?location=".$lat.",".$lng."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
		    $address_decode=json_decode($address_encode);
		    // 获得区名
		    $area_name = $address_decode->result->addressComponent->district;
		    // 获得城市名
		    $city_name = $address_decode->result->addressComponent->city;
		    // 获得城市名
		    $province_name = $address_decode->result->addressComponent->province;

		    // add address here
		    return self::addAddress($customer_id, $province_name, $city_name, $area_name, $address, '未知', $customer_address_phone);
		}catch(\Exception $e){
		    return false;
		}
	}

    /**
     * list customer address
     * @param $customer_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listAddress($customer_id){
        $customerAddresses = self::find()->where(['customer_id'=>$customer_id])->all();
        return $customerAddresses;
    }

    /**
     * get customer address by id
     * @param $id
     * @return bool|null|static
     */
    public static function getAddress($id){
        return self::findOne($id) ? self::findOne($id) : false;
    }

    /**
     * get customer current address
     * @param $customer_id
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public static function getCurrentAddress($customer_id){
		$currentAddress = self::find()->where(['customer_id'=>$customer_id, 'customer_address_status'=>1])->one();
		return $currentAddress == NULL ? false : $currentAddress;
		
	}

    /**
     * get customer address as array
     * @param $customer_id
     * @param $operation_province_name
     * @param $operation_city_name
     * @param $operation_area_name
     * @param $customer_address_detail
     * @param string $customer_address_nickname
     * @param $customer_address_phone
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getAddressOne($customer_id, $operation_province_name, $operation_city_name, $operation_area_name, $customer_address_detail, $customer_address_nickname='', $customer_address_phone){
        $customer_address = self::find()->where([
            'customer_id'=>$customer_id,
            'operation_province_name'=>$operation_province_name,
            'operation_city_name'=>$operation_city_name,
            'operation_area_name'=>$operation_area_name,
            'customer_address_detail'=>$customer_address_detail,
            'customer_address_nickname'=>$customer_address_nickname,
            'customer_address_phone'=>$customer_address_phone,
        ])->asArray()->one();
        //var_dump($customer_address);
        return $customer_address;
    }

    /**
     * is first address
     * @param $customer_id
     * @param $id
     * @return bool
     */
    public static function isFirstAddress($customer_id, $id){
        $address = self::find()
            ->select(['id'])
            ->where(['customer_id'=>$customer_id])
            ->orderBy('created_at asc')
            ->asArray()
            ->one();
        return $id == $address['id'];
    }
}

































