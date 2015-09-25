<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationShopDistrict;

/**
 * This is the model class for table "{{%operation_shop_district}}".
 *
 * @property integer $id
 * @property string $operation_shop_district_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property string $operation_shop_district_latitude_longitude
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationShopDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_city_id', 'created_at', 'updated_at'], 'integer'],
            [['operation_shop_district_latitude_longitude'], 'string'],
            [['operation_shop_district_name'], 'string', 'max' => 60],
            [['operation_city_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('operation', '编号'),
            'operation_shop_district_name' => Yii::t('operation', '商圈名称'),
            'operation_city_id' => Yii::t('operation', '城市编号'),
            'operation_city_name' => Yii::t('operation', '城市名称'),
            'operation_shop_district_latitude_longitude' => Yii::t('operation', '商圈经纬度'),
            'created_at' => Yii::t('operation', '创建时间'),
            'updated_at' => Yii::t('operation', '编辑时间'),
        ];
    }
}
