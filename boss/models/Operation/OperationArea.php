<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationArea;

/**
 * This is the model class for table "{{%operation_area}}".
 *
 * @property integer $id
 * @property string $area_name
 * @property integer $parent_id
 * @property string $short_name
 * @property string $longitude
 * @property string $latitude
 * @property integer $level
 * @property string $position
 * @property integer $sort
 */
class OperationArea extends CoreOperationArea
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'level', 'sort'], 'integer'],
            [['area_name', 'position'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 50],
            [['longitude', 'latitude'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('operation', '编号'),
            'area_name' => Yii::t('operation', '区域名称'),
            'parent_id' => Yii::t('operation', '父名称'),
            'short_name' => Yii::t('operation', '简称'),
            'longitude' => Yii::t('operation', '经度'),
            'latitude' => Yii::t('operation', '纬度'),
            'level' => Yii::t('operation', '行政级别：1省（直辖市），2地级市（地区），3县,区，县级市 ，4：乡镇街道'),
            'position' => Yii::t('operation', '逻辑关系位置'),
            'sort' => Yii::t('operation', '排序'),
        ];
    }
}
