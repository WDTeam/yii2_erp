<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_access_token}}".
 *
 * @property integer $id
 * @property string $customer_access_token
 * @property integer $customer_access_token_expiration
 * @property integer $customer_code_id
 * @property string $customer_code
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerAccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_access_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_access_token', 'customer_access_token_expiration', 'created_at', 'updated_at'], 'required'],
            [['customer_access_token_expiration', 'customer_code_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_access_token'], 'string', 'max' => 64],
            [['customer_code'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', 'ID'),
            'customer_access_token' => Yii::t('boss', 'access_token'),
            'customer_access_token_expiration' => Yii::t('boss', 'access_token过期时间'),
            'customer_code_id' => Yii::t('boss', '关联验证码'),
            'customer_code' => Yii::t('boss', '验证码'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '逻辑删除'),
        ];
    }
}
