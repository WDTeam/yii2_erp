<?php

namespace core\models\operation;

use Yii;

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
class OperationPlatformVersion extends \dbbase\models\operation\OperationPlatformVersion
{

    /**
     * 根据平台名称和平台版本获取平台的编号
     *
     * @param   string  $operation_platform_name            平台名称
     * @param   string  $operation_platform_version_name    平台版本
     * @return  mix  如果有则返回编号，没有则返回false
     */
    public static function getPlatformId($operation_platform_name, $operation_platform_version_name)
    {
        $data = self::find()
            ->select(['operation_platform_id'])
            ->where([
                'operation_platform_name' => $operation_platform_name,
                'operation_platform_version_name' => $operation_platform_version_name
            ])
            ->one();

        if (isset($data['id']) && $data['id'] > 0) {
            return $data['id'];
        } else {
            return false;
        }
    }
    
}
