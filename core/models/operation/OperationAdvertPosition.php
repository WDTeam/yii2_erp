<?php

namespace core\models\operation;

use Yii;

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
class OperationAdvertPosition extends \dbbase\models\operation\OperationAdvertPosition
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

    /**
     * 根据平台名称和平台版本获取平台的编号
     *
     * @param   string  $position_name   位置名称
     * @return  mix  如果有则返回编号，没有则返回false
     */
    public static function getAdvertPositionId($position_name)
    {
        $data = self::find()
            ->select(['id'])
            ->where([
                'operation_advert_position_name' => $position_name,
            ])
            ->one();

        if (isset($data['id']) && $data['id'] > 0) {
            return $data['id'];
        } else {
            return false;
        }
    }

    /**
     * 更新冗余的平台名称
     *
     * @param inter   $operation_platform_id     平台编号
     * @param string  $operation_platform_name   平台名称
     */
    public static function updatePlatformName($operation_platform_id, $operation_platform_name)
    {
        self::updateAll(['operation_platform_name' => $operation_platform_name], 'operation_platform_id= ' . $operation_platform_id);
    }

    /**
     * 更新冗余的平台版本
     *
     * @param inter   $operation_platform_version_id     平台版本编号
     * @param string  $operation_platform_version_name   平台版本名称
     */
    public static function updatePlatformVersion($operation_platform_version_id, $operation_platform_version_name)
    {
        self::updateAll(['operation_platform_version_name' => $operation_platform_version_name], 'operation_platform_version_id= ' . $operation_platform_version_id);
    }
}
