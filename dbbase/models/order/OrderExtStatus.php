<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_ext_status}}".
 *
 * @property string $order_id
 * @property integer $order_before_status_dict_id
 * @property string $order_before_status_name
 * @property integer $order_status_dict_id
 * @property string $order_status_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderExtStatus extends \common\models\order\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_ext_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_before_status_dict_id', 'order_status_dict_id', 'created_at', 'updated_at'], 'integer'],
            [['order_before_status_name', 'order_status_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'order_before_status_dict_id' => '状态变更前订单状态字典ID',
            'order_before_status_name' => '状态变更前订单状态',
            'order_status_dict_id' => '订单状态字典ID',
            'order_status_name' => '订单状态',
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
