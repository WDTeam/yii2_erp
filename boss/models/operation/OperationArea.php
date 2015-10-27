<?php

namespace boss\models\operation;

use Yii;
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
class OperationArea extends \core\models\operation\OperationArea
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
            'id' => '编号',
            'area_name' => '区域名称',
            'parent_id' => '父名称',
            'short_name' => '简称',
            'longitude' => '经度',
            'latitude' => '纬度',
            'level' => '行政级别：1省（直辖市），2地级市（地区），3县,区，县级市 ，4：乡镇街道',
            'position' => '逻辑关系位置',
            'sort' => '排序',
        ];
    }
}
