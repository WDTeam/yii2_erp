<?php

namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property integer $province_id
 * @property string $province_name
 * @property integer $city_id
 * @property string $city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'operation_city_is_online', 'created_at', 'updated_at'], 'integer'],
            [['province_name'], 'string', 'max' => 30],
            [['city_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '编号'),
            'province_id' => Yii::t('boss', '省份编号'),
            'province_name' => Yii::t('boss', '省份名称'),
            'city_id' => Yii::t('boss', '城市编号'),
            'city_name' => Yii::t('boss', '城市名称'),
            'operation_city_is_online' => Yii::t('boss', '城市是否上线（1为上线，2为下线）'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '编辑时间'),
        ];
    }
}
