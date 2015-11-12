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
class OperationShopDistrict extends \core\models\operation\OperationShopDistrict
{

    /**
     * string 批量导入商圈数据
     */
	public $district_upload_url;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_area_id'], 'required', 'message' => '请选择商圈所属区域'],
            [['operation_city_id', 'created_at', 'updated_at', 'operation_shop_district_status'], 'integer'],
            [['operation_shop_district_name'], 'string', 'max' => 60],
            [['operation_city_name'], 'string', 'max' => 50],
            [['operation_shop_district_name'], 'required'],
            [['operation_area_name', 'district_upload_url'], 'string'],
            ['operation_area_id', 'safe'],
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
            'operation_area_id' => Yii::t('operation', '区域编号'),
            'operation_area_name' => Yii::t('operation', '所属区域'),
            'operation_shop_district_latitude_longitude' => Yii::t('operation', '商圈经纬度'),
            'created_at' => Yii::t('operation', '创建时间'),
            'updated_at' => Yii::t('operation', '编辑时间'),
            'operation_shop_district_status' => Yii::t('operation', '上线状态'),
            'district_upload_url' => Yii::t('operation', '批量导入'),
        ];
    }
}
