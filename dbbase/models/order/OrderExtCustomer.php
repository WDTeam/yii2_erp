<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;

/**
 * This is the model class for table "{{%order_ext_customer}}".
 *
 * @property string $order_id
 * @property string $customer_id
 * @property string $order_customer_phone
 * @property string $order_customer_is_vip
 * @property string $order_customer_need
 * @property string $order_customer_memo
 * @property string $comment_id
 * @property string $invoice_id
 * @property integer $order_customer_hidden
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderExtCustomer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_ext_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'comment_id', 'invoice_id', 'order_customer_hidden', 'order_customer_is_vip', 'created_at', 'updated_at'], 'integer'],
            [['order_customer_phone'], 'string', 'max' => 16],
            [['order_customer_need', 'order_customer_memo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'customer_id' => '客户ID',
            'order_customer_phone' => '客户手机号',
            'order_customer_is_vip' => '是否是vip',
            'order_customer_need' => '客户需求',
            'order_customer_memo' => '客户备注',
            'comment_id' => '评价id',
            'invoice_id' => '发票id',
            'order_customer_hidden' => '客户端是否已删除',
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
