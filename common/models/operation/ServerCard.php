<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%server_card}}".
 *
 * @property string $id
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $par_value
 * @property string $reb_value
 * @property integer $use_scope
 * @property integer $valid_days
 * @property integer $created_at
 * @property integer $updated_at
 */
class ServerCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%server_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_type', 'card_level', 'use_scope', 'valid_days', 'created_at', 'updated_at'], 'integer'],
            [['par_value', 'reb_value'], 'number'],
            [['card_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'card_name' => Yii::t('app', '卡名'),
            'card_type' => Yii::t('app', '卡类型'),
            'card_level' => Yii::t('app', '卡级别'),
            'par_value' => Yii::t('app', '卡面金额'),
            'reb_value' => Yii::t('app', '优惠金额'),
            'use_scope' => Yii::t('app', '使用范围'),
            'valid_days' => Yii::t('app', '有效时间(天)'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
        ];
    }
}
