<?php

namespace boss\models\operation;

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
class OperationAdvertContent extends \core\models\operation\OperationAdvertContent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform_id', 'platform_version_id', 'operation_advert_start_time', 'operation_advert_end_time', 'operation_advert_online_time', 'operation_advert_offline_time', 'created_at', 'updated_at'], 'integer'],
            [['position_name', 'platform_name', 'platform_version_name', 'operation_advert_content_name'], 'string', 'max' => 60],
            [['operation_advert_picture_text', 'operation_advert_url'], 'string', 'max' => 255],
            [['operation_advert_content_name'], 'required'],
            [['position_id'], 'integer', 'min' => 1],
            ['operation_advert_picture_text', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024],
            ['operation_advert_picture_text', 'required', 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_advert_content_name' => '广告内容标题',
            'position_id' => '广告位置编号',
            'position_name' => '广告位置名称',
            'platform_id' => '所属平台编号',
            'platform_name' => '所属平台',
            'platform_version_id' => '所属版本编号',
            'platform_version_name' => '所属版本',
            'operation_advert_start_time' => '活动开始时间',
            'operation_advert_end_time' => '活动结束时间',
            'operation_advert_online_time' => '广告上线时间',
            'operation_advert_offline_time' => '广告下线时间',
            'operation_advert_picture_text' => '广告图片',
            'operation_advert_url' => '广告链接地址',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
            'operation_advert_content_orders' => '排序',
        ];
    }
}
