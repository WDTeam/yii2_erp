<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationAdvertPosition as CommonOperationAdvertPosition;
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
class OperationAdvertPosition extends CommonOperationAdvertPosition
{
   
    /**
     * 验证同平台版本广告位置是否重复
     *
     * @param array $post    要验证的广告位置信息
     */
    public static function verifyRepeat($post)
    {
        $data = self::find()
            ->select(['id'])
            ->where([
                'operation_platform_id'          => $post['operation_platform_id'],
                'operation_platform_version_id'  => $post['operation_platform_version_id'],
                'operation_advert_position_name' => $post['operation_advert_position_name'],
            ])
            ->asArray()
            ->one();

        if (isset($data) && $data['id'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}
