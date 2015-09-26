<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationAdvertPosition;
/**
 * This is the model class for table "{{%operation_advert_position}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_name
 * @property integer $operation_platform_id
 * @property string $operation_platform_name
 * @property integer $operation_platform_version_id
 * @property string $operation_platform_version_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $operation_advert_position_width
 * @property integer $operation_advert_position_height
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertPosition extends CoreOperationAdvertPosition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_platform_id', 'operation_platform_version_id', 'operation_city_id', 'operation_advert_position_width', 'operation_advert_position_height', 'created_at', 'updated_at'], 'integer'],
            [['operation_advert_position_name', 'operation_platform_name', 'operation_platform_version_name', 'operation_city_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_advert_position_name' => '位置名称',
            'operation_platform_id' => '平台编号',
            'operation_platform_name' => '平台名称',
            'operation_platform_version_id' => '版本编号',
            'operation_platform_version_name' => '版本名称',
            'operation_city_id' => '城市编号',
            'operation_city_name' => '城市名称',
            'operation_advert_position_width' => '宽度（像素）',
            'operation_advert_position_height' => '高度（像素）',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
