<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationShopDistrictCoordinate;

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
class OperationShopDistrictCoordinate extends \yii\db\ActiveRecord
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
            'operation_shop_district_coordinate_start_longitude' => Yii::t('operation', '开始经度'),
            'operation_shop_district_coordinate_start_latitude' => Yii::t('operation', '开始纬度'),
            'operation_shop_district_coordinate_end_longitude' => Yii::t('operation', '结束经度'),
            'operation_shop_district_coordinate_end_latitude' => Yii::t('operation', '结束纬度'),
            'updated_at' => Yii::t('operation', '编辑时间'),
        ];
    }
}
