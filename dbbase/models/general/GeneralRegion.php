<?php

namespace dbbase\models;

use Yii;

/**
 * This is the model class for table "{{%general_region}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $general_region_area_name
 * @property string $general_region_province_name
 * @property string $general_region_city_name
 * @property string $general_region_area_ename
 * @property string $general_region_province_ename
 * @property string $general_region_city_ename
 * @property integer $general_region_level
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $isdel
 */
class GeneralRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%general_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'general_region_level', 'created_at', 'updated_at', 'isdel'], 'integer'],
            [['general_region_area_name'], 'required'],
            [['general_region_area_name', 'general_region_province_name'], 'string', 'max' => 20],
            [['general_region_city_name', 'general_region_area_ename', 'general_region_province_ename', 'general_region_city_ename'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', 'ID'),
            'parent_id' => Yii::t('boss', '上级ID'),
            'general_region_area_name' => Yii::t('boss', '地区名称'),
            'general_region_province_name' => Yii::t('boss', '省份名称'),
            'general_region_city_name' => Yii::t('boss', '城市名称'),
            'general_region_area_ename' => Yii::t('boss', '地区拼音'),
            'general_region_province_ename' => Yii::t('boss', '省份拼音'),
            'general_region_city_ename' => Yii::t('boss', '城市拼音'),
            'general_region_level' => Yii::t('boss', '地区级别'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'isdel' => Yii::t('boss', 'Isdel'),
        ];
    }
}
