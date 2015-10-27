<?php

namespace boss\models\customer;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\finance\FinanceOrderChannel;
use common\models\finance\FinancePayChannel;
/**
 * This is the model class for table "{{%customer_trans_record}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
 * @property integer $order_channel_id
 * @property integer $customer_trans_record_order_channel
 * @property integer $pay_channel_id
 * @property integer $customer_trans_record_pay_channel
 * @property integer $customer_trans_record_mode
 * @property integer $customer_trans_record_mode_name
 * @property string $customer_trans_record_coupon_money
 * @property string $customer_trans_record_cash
 * @property string $customer_trans_record_pre_pay
 * @property string $customer_trans_record_online_pay
 * @property string $customer_trans_record_online_balance_pay
 * @property string $customer_trans_record_online_service_card_on
 * @property string $customer_trans_record_online_service_card_pay
 * @property string $customer_trans_record_refund_money
 * @property string $customer_trans_record_order_total_money
 * @property string $customer_trans_record_total_money
 * @property string $customer_trans_record_current_balance
 * @property string $customer_trans_record_befor_balance
 * @property string $customer_trans_record_transaction_id
 * @property string $customer_trans_record_remark
 * @property string $customer_trans_record_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class CustomerTransRecord extends \core\models\customer\CustomerTransRecord
{
    public $record_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_trans_record}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'order_channel_id' => Yii::t('app', '订单渠道'),
            'customer_trans_record_order_channel' => Yii::t('app', '订单渠道名称'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'customer_trans_record_pay_channel' => Yii::t('app', '支付渠道名称'),
            'customer_trans_record_mode' => Yii::t('app', '交易方式'),
        ]);
    }
}
