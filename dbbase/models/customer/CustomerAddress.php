<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_address}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $operation_province_id
 * @property integer $operation_city_id
 * @property integer $operation_area_id
 * @property string $operation_province_name
 * @property string $operation_city_name
 * @property string $operation_area_name
 * @property string $operation_province_short_name
 * @property string $operation_city_short_name
 * @property string $operation_area_short_name
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
class CustomerAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_address_status', 'customer_address_nickname', 'customer_address_phone', 'created_at', 'updated_at'], 'required'],
            [['customer_id', 'operation_province_id', 'operation_city_id', 'operation_area_id', 'customer_address_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_address_longitude', 'customer_address_latitude'], 'number'],
            [['operation_province_name', 'operation_city_name', 'operation_area_name', 'operation_province_short_name', 'operation_city_short_name', 'operation_area_short_name'], 'string', 'max' => 255],
            [['customer_address_detail'], 'string', 'max' => 64],
            [['customer_address_nickname'], 'string', 'max' => 32],
            [['customer_address_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'customer_id' => Yii::t('boss', '关联客户'),
            'operation_province_id' => Yii::t('boss', '省'),
            'operation_city_id' => Yii::t('boss', '市'),
            'operation_area_id' => Yii::t('boss', '区'),
            'operation_province_name' => Yii::t('boss', '省名'),
            'operation_city_name' => Yii::t('boss', '市名'),
            'operation_area_name' => Yii::t('boss', '区名'),
            'operation_province_short_name' => Yii::t('boss', '省短名'),
            'operation_city_short_name' => Yii::t('boss', '市短名'),
            'operation_area_short_name' => Yii::t('boss', '区短名'),
            'customer_address_detail' => Yii::t('boss', '详细地址'),
            'customer_address_status' => Yii::t('boss', '客户地址类型,1为默认地址，-1为非默认地址'),
            'customer_address_longitude' => Yii::t('boss', '经度'),
            'customer_address_latitude' => Yii::t('boss', '纬度'),
            'customer_address_nickname' => Yii::t('boss', '被服务者昵称'),
            'customer_address_phone' => Yii::t('boss', '被服务者手机'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }
}
