<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order_ext_pay}}".
 *
 * @property string $order_id
 * @property string $pay_channel_type_id
 * @property string $order_pay_channel_type_name
 * @property string $pay_channel_id
 * @property string $order_pay_channel_name
 * @property string $order_pay_flow_num
 * @property string $order_pay_money
 * @property string $order_use_acc_balance
 * @property string $card_id
 * @property string $order_use_card_money
 * @property string $coupon_id
 * @property string $order_coupon_code
 * @property string $order_use_coupon_money
 * @property string $promotion_id
 * @property string $order_use_promotion_money
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderExtPay extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_ext_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_channel_id'],'required'],
            [[ 'pay_channel_id','pay_channel_type_id', 'card_id', 'coupon_id', 'promotion_id', 'created_at', 'updated_at'], 'integer'],
            [['order_pay_money', 'order_use_acc_balance', 'order_use_card_money', 'order_use_coupon_money', 'order_use_promotion_money'], 'number'],
            [['order_pay_channel_name','order_pay_channel_type_name','order_coupon_code'], 'string', 'max' => 128],
            [['order_pay_flow_num'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'pay_channel_type_id' => '支付渠道分类id',
            'order_pay_channel_type_name' => '支付渠道分类名称',
            'pay_channel_id' => '支付渠道id',
            'order_pay_channel_name' => '支付渠道名称',
            'order_pay_flow_num' => '支付流水号',
            'order_pay_money' => '支付金额',
            'order_use_acc_balance' => '使用余额',
            'card_id' => '服务卡ID',
            'order_use_card_money' => '使用服务卡金额',
            'coupon_id' => '优惠券ID',
            'order_coupon_code' => '优惠码',
            'order_use_coupon_money' => '使用优惠卷金额',
            'promotion_id' => '促销id',
            'order_use_promotion_money' => '使用促销金额',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
