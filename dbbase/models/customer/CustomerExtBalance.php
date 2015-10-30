<?php

namespace dbbase\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_ext_balance}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $customer_phone
 * @property string $customer_balance
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerExtBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_ext_balance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_balance'], 'number'],
            [['customer_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dbbase', 'ID'),
            'customer_id' => Yii::t('dbbase', '客户'),
            'customer_phone' => Yii::t('dbbase', '手机号'),
            'customer_balance' => Yii::t('dbbase', '客户余额'),
            'created_at' => Yii::t('dbbase', '创建时间'),
            'updated_at' => Yii::t('dbbase', '更新时间'),
            'is_del' => Yii::t('dbbase', '是否删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerExtBalanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerExtBalanceQuery(get_called_class());
    }
}
