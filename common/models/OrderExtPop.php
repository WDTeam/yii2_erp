<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_ext_pop}}".
 *
 * @property string $order_id
 * @property string $order_pop_order_code
 * @property string $order_pop_group_buy_code
 * @property string $order_pop_operation_money
 * @property string $order_pop_order_money
 * @property string $order_pop_pay_money
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderExtPop extends \common\models\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_ext_pop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_pop_operation_money', 'order_pop_order_money', 'order_pop_pay_money'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
            [['order_pop_order_code', 'order_pop_group_buy_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'order_pop_order_code' => '第三方订单编号',
            'order_pop_group_buy_code' => '第三方团购码',
            'order_pop_operation_money' => '第三方运营费',
            'order_pop_order_money' => '第三方订单金额',
            'order_pop_pay_money' => '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。',
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
