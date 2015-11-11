<?php

namespace core\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_advert_content}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $operation_advert_start_time
 * @property integer $operation_advert_end_time
 * @property integer $operation_advert_online_time
 * @property integer $operation_advert_offline_time
 * @property string $operation_advert_picture
 * @property string $operation_advert_url
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertContent extends \dbbase\models\operation\OperationAdvertContent
{
    
    /** 
     * 获取广告图列表 TODO:未开发完成
     * @param type $generalWhere 普通查询条件
     * @param type $sectionWhere 区间查询条件
     */
    public static function getAdvertList($generalWhere = array(),$sectionWhere=array()){
        $query = new \yii\db\Query();
        
        $list = $query->from('{{%operation_advert_content}}')
            ->select('*')
            ->where($generalWhere);
        //广告上线时间
        if(isset($sectionWhere['operation_advert_online_time'])){
            $query->andWhere(['>','operation_advert_online_time',$sectionWhere['operation_advert_online_time']]);
        }
        //广告下线时间
        if(isset($sectionWhere['operation_advert_offline_time'])){
            $query->andWhere(['<','operation_advert_offline_time',$sectionWhere['operation_advert_offline_time']]);
        }
        //活动开始时间
        if(isset($sectionWhere['operation_advert_start_time'])){
            $query->andWhere(['>','operation_advert_start_time',$sectionWhere['operation_advert_start_time']]);
        }
        //活动结束时间
        if(isset($sectionWhere['operation_advert_end_time'])){
            $query->andWhere(['<','operation_advert_end_time',$sectionWhere['operation_advert_end_time']]);
        }
        $query->orderBy(['operation_advert_content_orders' =>'ASC']);
        echo  $query->createCommand()->getRawSql();
   



                
        
    }

    /**
     * 更新冗余的平台名称
     *
     * @param inter   $operation_platform_id     平台编号
     * @param string  $operation_platform_name   平台名称
     */
    public static function updatePlatformName($operation_platform_id, $operation_platform_name)
    {
        self::updateAll(['platform_name' => $operation_platform_name], 'platform_id= ' . $operation_platform_id);
    }

    /**
     * 更新冗余的平台版本
     *
     * @param inter   $operation_platform_version_id     平台版本编号
     * @param string  $operation_platform_version_name   平台版本名称
     */
    public static function updatePlatformVersion($operation_platform_version_id, $operation_platform_version_name)
    {
        self::updateAll(['platform_version_name' => $operation_platform_version_name], 'platform_version_id= ' . $operation_platform_version_id);
    }

    /**
     * 更新冗余的广告位置名称(暂没有使用)
     *
     * @param inter   $position_id     广告位置编号
     * @param string  $position_name   广告位置名称
     */
    public static function updateAdvertPositionName($position_id, $position_name)
    {
        self::updateAll(['position_name' => $position_name], 'position_id= ' . $position_id);
    }

    /**
     * 更新冗余的广告位置名称,平台信息
     *
     * @param inter   $position_id             广告位置编号
     * @param string  $position_name           广告位置名称
     * @param string  $platform_id             平台编号
     * @param string  $platform_version_id     平台版本编号
     * @param string  $platform_name           平台名称
     * @param string  $platform_version_name   平台版本名称
     */
    public static function updateAdvertPlatformInfo($position_id, $position_name, $platform_id, $platform_version_id, $platform_name, $platform_version_name)
    {
        self::updateAll(
            [
                'position_name'         => $position_name,
                'platform_id'           => $platform_id,
                'platform_version_id'   => $platform_version_id,
                'platform_name'         => $platform_name,
                'platform_version_name' => $platform_version_name,
            ],
            'position_id= ' . $position_id
        );
    }

    /**
     * 根据平台编号联动删除广告内容信息
     *
     * @param inter   $platform_id     平台编号
     */
    public static function updateAdvertContentStatus($platform_id)
    {
        self::deleteAll([
            'platform_id' => $platform_id,
        ]);
    }

    /**
     * 根据平台编号获取一个平台对应的所有的广告内容
     *
     * @param inter   $platform_id     平台编号
     */
    public static function getAdvertContent($platform_id)
    {
        $data = self::find()
            ->select(['id'])
            ->where(['platform_id' => $platform_id])
            ->asArray()
            ->All();
        if (isset($data) && count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 根据版本编号联动删除广告内容信息
     *
     * @param inter   $platform_version_id     版本编号
     */
    public static function updateAdvertContentStatusFromVersion($platform_version_id)
    {
        self::deleteAll([
            'platform_version_id' => $platform_version_id,
        ]);
    }

    /**
     * 根据版本编号获取一个平台对应的所有的广告内容
     *
     * @param inter   $platform_version_id     版本编号
     */
    public static function getAdvertContentFromVersion($platform_version_id)
    {
        $data = self::find()
            ->select(['id'])
            ->where(['platform_version_id' => $platform_version_id])
            ->asArray()
            ->All();
        if (isset($data) && count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 根据广告位置编号联动删除广告内容信息
     *
     * @param inter   $position_id     广告位置编号
     */
    public static function updateAdvertContentStatusFromPosition($position_id)
    {
        self::deleteAll([
            'position_id' => $position_id,
        ]);
    }

    /**
     * 根据广告位置编号获取一个平台对应的所有的广告内容
     *
     * @param inter   $position_id     广告位置编号
     */
    public static function getAdvertContentFromPosition($position_id)
    {
        $data = self::find()
            ->select(['id'])
            ->where(['position_id' => $position_id])
            ->asArray()
            ->All();
        if (isset($data) && count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }
}
