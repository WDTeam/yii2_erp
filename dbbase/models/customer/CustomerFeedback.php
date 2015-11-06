<?php

namespace dbbase\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_feedback}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $customer_phone
 * @property string $feedback_content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
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
            [['customer_id', 'customer_phone', 'feedback_content', 'created_at', 'updated_at'], 'required'],
            [['customer_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['feedback_content'], 'string'],
            [['customer_phone'], 'string', 'max' => 11],
            [['customer_phone'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dbbase', '主键'),
            'customer_id' => Yii::t('dbbase', '关联用户'),
            'customer_phone' => Yii::t('dbbase', '客户手机'),
            'feedback_content' => Yii::t('dbbase', '反馈内容'),
            'created_at' => Yii::t('dbbase', '创建时间'),
            'updated_at' => Yii::t('dbbase', '更新时间'),
            'is_del' => Yii::t('dbbase', '是否逻辑删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerFeedbackQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerFeedbackQuery(get_called_class());
    }
}
