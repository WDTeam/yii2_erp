<?php

namespace core\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_platform}}".
 *
 * @property integer $id
 * @property string $operation_platform_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationPlatform extends \dbbase\models\operation\OperationPlatform
{
    /**
     * 删除标志
     */
    const IS_SOFTDEL = 1;

    /**
     * 删除平台信息
     *
     * @param integer   $operation_platform_id     平台编号
     */
    public static function updatePlatformStatus($operation_platform_id)
    {
        self::updateAll(['is_softdel' => self::IS_SOFTDEL], 'id= ' . $operation_platform_id);
    }
}
