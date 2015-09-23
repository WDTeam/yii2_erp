<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_help}}".
 *
 * @property integer $id
 * @property string $help_question
 * @property string $help_solution
 * @property integer $help_status
 * @property integer $help_sort
 * @property integer $created_at
 * @property integer $update_at
 */
class CustomerHelp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_help}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['help_question', 'help_status', 'help_sort'], 'required'],
            [['help_solution'], 'string'],
            [['help_status', 'help_sort', 'created_at', 'update_at'], 'integer'],
            [['help_question'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'help_question' => Yii::t('boss', '问题'),
            'help_solution' => Yii::t('boss', '回答'),
            'help_status' => Yii::t('boss', '0隐藏，1显示'),
            'help_sort' => Yii::t('boss', '排序,越小排在前面'),
            'created_at' => Yii::t('boss', '创建时间'),
            'update_at' => Yii::t('boss', '更新时间'),
        ];
    }
}
