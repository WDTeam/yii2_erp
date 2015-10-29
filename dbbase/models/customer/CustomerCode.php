<?php

namespace dbbase\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_code}}".
 *
 * @property integer $id
 * @property string $customer_code
 * @property integer $customer_code_expiration
 * @property string $customer_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_code', 'customer_code_expiration', 'created_at', 'updated_at'], 'required'],
            [['customer_code_expiration', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_code'], 'string', 'max' => 8],
            [['customer_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', 'ID'),
            'customer_code' => Yii::t('boss', '验证码'),
            'customer_code_expiration' => Yii::t('boss', '验证码过期时间'),
            'customer_phone' => Yii::t('boss', '顾客电话'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '逻辑删除'),
        ];
    }
}
