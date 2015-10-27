<?php

namespace core\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card_order}}".
 *
 * @property string $id
 * @property string $order_code
 * @property string $usere_id
 * @property integer $order_customer_phone
 * @property string $server_card_id
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $par_value
 * @property string $reb_value
 * @property string $order_money
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property integer $order_channel_id
 * @property string $order_channel_name
 * @property integer $order_lock_status
 * @property integer $order_status_id
 * @property string $order_status_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $order_pay_type
 * @property integer $pay_channel_id
 * @property string $pay_channel_name
 * @property string $order_pay_flow_num
 * @property string $order_pay_money
 * @property integer $paid_at
 */
class OperationServerCardOrder extends \common\models\operation\OperationServerCardOrder
{
   /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'order_code' => Yii::t('app', '订单号'),
            'usere_id' => Yii::t('app', '用户id'),
            'order_customer_phone' => Yii::t('app', '用户手机号'),
            'server_card_id' => Yii::t('app', '卡id'),
            'card_name' => Yii::t('app', '卡名'),
            'card_type' => Yii::t('app', '卡类型'),
            'card_level' => Yii::t('app', '卡级别'),
            'par_value' => Yii::t('app', '卡面值'),
            'reb_value' => Yii::t('app', '卡优惠值'),
            'order_money' => Yii::t('app', '订单金额'),
            'order_src_id' => Yii::t('app', '订单来源id'),
            'order_src_name' => Yii::t('app', '订单来源名称'),
            'order_channel_id' => Yii::t('app', '订单渠道id'),
            'order_channel_name' => Yii::t('app', '订单渠道名称'),
            'order_lock_status' => Yii::t('app', '是否锁定'),
            'order_status_id' => Yii::t('app', '订单状态id'),
            'order_status_name' => Yii::t('app', '订单状态名称'),
            'created_at' => Yii::t('app', '订单创建时间'),
            'updated_at' => Yii::t('app', '订单更改时间'),
            'order_pay_type' => Yii::t('app', '支付方式'),
            'pay_channel_id' => Yii::t('app', '支付渠道id'),
            'pay_channel_name' => Yii::t('app', '支付渠道名称'),
            'order_pay_flow_num' => Yii::t('app', '支付流水号'),
            'order_pay_money' => Yii::t('app', '支付金额'),
            'paid_at' => Yii::t('app', '支付时间'),
        ];
    }
}
