<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_service_card_with_customer".
 *
 * @property string $id
 * @property string $service_card_sell_record_id
 * @property string $service_card_sell_record_code
 * @property string $service_card_info_id
 * @property string $service_card_with_customer_code
 * @property string $service_card_info_name
 * @property string $customer_trans_record_pay_money
 * @property string $service_card_info_value
 * @property string $service_card_info_rebate_value
 * @property string $service_card_with_customer_balance
 * @property integer $customer_id
 * @property string $customer_phone
 * @property integer $service_card_info_scope
 * @property integer $service_card_with_customer_buy_at
 * @property integer $service_card_with_customer_valid_at
 * @property integer $service_card_with_customer_activated_at
 * @property integer $service_card_with_customer_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardWithCustomer extends \core\models\operation\OperationServiceCardWithCustomer
{
  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_card_sell_record_id', 'service_card_info_id', 'customer_id', 'service_card_info_scope', 'service_card_with_customer_buy_at', 'service_card_with_customer_valid_at', 'service_card_with_customer_activated_at', 'service_card_with_customer_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_trans_record_pay_money', 'service_card_info_value', 'service_card_info_rebate_value', 'service_card_with_customer_balance'], 'number'],
            [['service_card_sell_record_code', 'service_card_with_customer_code', 'service_card_info_name'], 'string', 'max' => 64],
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
            'service_card_sell_record_id' => Yii::t('app', '购卡销售记录id'),
            'service_card_sell_record_code' => Yii::t('app', '购卡订单号'),
            'service_card_info_id' => Yii::t('app', '服务卡信息id'),
            'service_card_with_customer_code' => Yii::t('app', '服务卡号'),
            'service_card_info_name' => Yii::t('app', '服务卡卡名'),
            'customer_trans_record_pay_money' => Yii::t('app', '实收金额'),
            'service_card_info_value' => Yii::t('app', '卡面金额'),
            'service_card_info_rebate_value' => Yii::t('app', '优惠金额'),
            'service_card_with_customer_balance' => Yii::t('app', '余额'),
            'customer_id' => Yii::t('app', '持卡人id'),
            'customer_phone' => Yii::t('app', '持卡人手机号'),
            'service_card_info_scope' => Yii::t('app', '使用范围'),
            'service_card_with_customer_buy_at' => Yii::t('app', '购买日期'),
            'service_card_with_customer_valid_at' => Yii::t('app', '有效截止日期'),
            'service_card_with_customer_activated_at' => Yii::t('app', '激活日期'),
            'service_card_with_customer_status' => Yii::t('app', '服务卡状态'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
            'is_del' => Yii::t('app', '状态'),
        ];
    }
}
