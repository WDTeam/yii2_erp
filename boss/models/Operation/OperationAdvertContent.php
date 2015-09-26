<?php

namespace boss\models\Operation;

use Yii;

use core\models\Operation\CoreOperationAdvertContent;
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
class OperationAdvertContent extends CoreOperationAdvertContent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_city_id', 'operation_advert_start_time', 'operation_advert_end_time', 'operation_advert_online_time', 'operation_advert_offline_time', 'created_at', 'updated_at'], 'integer'],
            [['operation_advert_position_name', 'operation_city_name'], 'string', 'max' => 60],
            [['operation_advert_picture', 'operation_advert_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_advert_position_name' => '广告名称',
            'operation_city_id' => '城市编号',
            'operation_city_name' => '城市名称',
            'operation_advert_start_time' => '活动开始时间',
            'operation_advert_end_time' => '活动结束时间',
            'operation_advert_online_time' => '广告上线时间',
            'operation_advert_offline_time' => '广告下线时间',
            'operation_advert_picture' => '广告图片',
            'operation_advert_url' => '广告链接地址',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
