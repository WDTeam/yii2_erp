<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_feedback}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $feedback_content
 * @property integer $created_at
 * @property integer $updated_at
 */
class CustomerFeedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'feedback_content', 'created_at', 'updated_at'], 'required'],
            [['customer_id', 'created_at', 'updated_at'], 'integer'],
            [['feedback_content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'customer_id' => Yii::t('boss', '关联用户'),
            'feedback_content' => Yii::t('boss', '反馈内容'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
        ];
    }
}
