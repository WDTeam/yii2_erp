<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card_customer}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $order_code
 * @property string $card_id
 * @property string $card_no
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $pay_value
 * @property string $par_value
 * @property string $reb_value
 * @property string $res_value
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_phone
 * @property integer $use_scope
 * @property integer $buy_at
 * @property integer $valid_at
 * @property integer $activated_at
 * @property integer $freeze_flag
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationServerCardCustomer extends \core\models\operation\OperationServerCardCustomer
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'card_id', 'card_type', 'card_level', 'customer_id', 'use_scope', 'buy_at', 'valid_at', 'activated_at', 'freeze_flag', 'created_at', 'updated_at'], 'integer'],
            [['pay_value', 'par_value', 'reb_value', 'res_value'], 'number'],
            [['order_code', 'card_no', 'card_name'], 'string', 'max' => 64],
            [['customer_name'], 'string', 'max' => 16],
            [['customer_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'order_id' => Yii::t('app', '订单id'),
            'order_code' => Yii::t('app', '订单编号'),
            'card_id' => Yii::t('app', '卡信息id'),
            'card_no' => Yii::t('app', '卡号'),
            'card_name' => Yii::t('app', '卡名'),
            'card_type' => Yii::t('app', '卡类型'),
            'card_level' => Yii::t('app', '卡级别'),
            'pay_value' => Yii::t('app', '实收金额'),
            'par_value' => Yii::t('app', '卡面金额'),
            'reb_value' => Yii::t('app', '优惠金额'),
            'res_value' => Yii::t('app', '余额'),
            'customer_id' => Yii::t('app', '持卡人id'),
            'customer_name' => Yii::t('app', '持卡人名称'),
            'customer_phone' => Yii::t('app', '持卡人手机号'),
            'use_scope' => Yii::t('app', '使用范围'),
            'buy_at' => Yii::t('app', '购买日期'),
            'valid_at' => Yii::t('app', '有效截止日期'),
            'activated_at' => Yii::t('app', '激活日期'),
            'freeze_flag' => Yii::t('app', '冻结标识'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
        ];
    }
}
