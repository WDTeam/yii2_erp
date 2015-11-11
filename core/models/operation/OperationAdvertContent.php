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
}
