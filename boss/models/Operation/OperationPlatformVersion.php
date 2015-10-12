<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationPlatformVersion;
/**
 * This is the model class for table "{{%operation_platform_version}}".
 *
 * @property integer $id
 * @property integer $operation_platform_id
 * @property string $operation_platform_name
 * @property string $operation_platform_version_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationPlatformVersion extends CoreOperationPlatformVersion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_platform_id', 'created_at', 'updated_at'], 'integer'],
            [['operation_platform_name', 'operation_platform_version_name'], 'string', 'max' => 60],
            [['operation_platform_version_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_platform_id' => '平台编号',
            'operation_platform_name' => '平台名称',
            'operation_platform_version_name' => '版本名称',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
