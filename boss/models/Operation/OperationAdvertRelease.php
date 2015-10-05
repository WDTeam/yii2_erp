<?php

namespace boss\models\Operation;

use Yii;

use core\models\Operation\CoreOperationAdvertRelease;
/**
 * This is the model class for table "{{%operation_advert_release}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_id
 * @property string $operation_advert_position_name
 * @property integer $operation_advert_content_id
 * @property integer $operation_advert_content_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertRelease extends CoreOperationAdvertRelease
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_advert_position_name'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['operation_advert_position_id'], 'string', 'max' => 60],
            [['operation_advert_contents'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_platform_id' => '发布目标平台编号',
            'operation_platform_name' => '发布目标平台',
            'operation_platform_version_id' => '发布目标版本编号',
            'operation_platform_version_name' => '发布目标版本',
            'operation_advert_position_id' => '发布目标位置编号',
            'operation_advert_position_name' => '发布目标位置',
            'operation_advert_contents' => '发布广告内容',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
