<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order_ext_pay}}".
 *
 * @property string $order_id
 * @property integer $order_pay_type
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
    const ORDER_PAY_TYPE_OFF_LINE = 1;
    const ORDER_PAY_TYPE_ON_LINE = 2;
    const ORDER_PAY_TYPE_POP = 3;

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
            [['order_pay_type'],'required'],
            [['order_pay_type', 'pay_channel_id', 'card_id', 'coupon_id', 'promotion_id', 'created_at', 'updated_at'], 'integer'],
            [['order_pay_money', 'order_use_acc_balance', 'order_use_card_money', 'order_use_coupon_money', 'order_use_promotion_money'], 'number'],
            [['order_pay_channel_name','order_coupon_code'], 'string', 'max' => 128],
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
            'order_pay_type' => '支付方式 0未支付 1现金支付 2线上支付 3第三方预付 ',
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

    public function orderPayTypeLabels()
    {
        return [
          self::ORDER_PAY_TYPE_OFF_LINE => '现金支付',
          self::ORDER_PAY_TYPE_ON_LINE => '线上支付',
          self::ORDER_PAY_TYPE_POP => '第三方预付'
        ];
    }

    public function getOrderPayTypeName()
    {
        $names = $this->orderPayTypeLabels();
        return isset($names[$this->order_pay_type])?$names[$this->order_pay_type]:'未支付';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
