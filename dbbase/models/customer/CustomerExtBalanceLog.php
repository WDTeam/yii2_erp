<?php

namespace dbbase\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_ext_balance_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $customer_phone
 * @property string $customer_ext_balance_begin_balance
 * @property string $customer_ext_balance_end_balance
 * @property string $customer_ext_balance_operate_balance
 * @property integer $customer_ext_balance_operate_type
 * @property string $customer_ext_balance_operate_type_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerExtBalanceLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_ext_balance_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_ext_balance_operate_type', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_ext_balance_begin_balance', 'customer_ext_balance_end_balance', 'customer_ext_balance_operate_balance'], 'number'],
            [['customer_phone'], 'string', 'max' => 11],
            [['customer_ext_balance_operate_type_name'], 'string', 'max' => 255]
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
            'customer_ext_balance_begin_balance' => Yii::t('dbbase', '客户操作前余额'),
            'customer_ext_balance_end_balance' => Yii::t('dbbase', '客户操作后余额'),
            'customer_ext_balance_operate_balance' => Yii::t('dbbase', '客户操作余额量'),
            'customer_ext_balance_operate_type' => Yii::t('dbbase', '客户操作余额类型-1为减少1为增加0为不变'),
            'customer_ext_balance_operate_type_name' => Yii::t('dbbase', '客户操作余额类型名称'),
            'created_at' => Yii::t('dbbase', '创建时间'),
            'updated_at' => Yii::t('dbbase', '更新时间'),
            'is_del' => Yii::t('dbbase', '是否删除'),
        ];
    }
}
