<?php

namespace boss\models\operation;

use Yii;

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
class OperationShopDistrictCoordinate extends \core\models\operation\OperationShopDistrictCoordinate
{

    /**
     * 自建校验器:经度 0~180
     */
    public function validateLongitude($attribute, $params)
    {
        $value = $this->$attribute;
        if ($value > $params['max'] || $val < $params['min']) {
            $this->addError($attribute, '经度超出范围');
        }
    }

    /**
     * 自建校验器:纬度 0~90
     */
    public function validateLatitude($attribute, $params)
    {
        $value = $this->$attribute;
        if ($value > $params['max'] || $val < $params['min']) {
            $this->addError($attribute, '纬度超出范围');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_city_id', 'created_at', 'updated_at'], 'integer'],
            [
                [
                    'operation_shop_district_coordinate_start_longitude',
                    'operation_shop_district_coordinate_end_longitude'
                ],
                'validateLongitude',
                'params' => ['min' => '0', 'max' => '180']
            ],
            [
                [
                    'operation_shop_district_coordinate_start_latitude',
                    'operation_shop_district_coordinate_end_latitude'
                ],
                'validateLatitude',
                'params' => ['min' => '0', 'max' => '90']
            ],
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
