<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_status_history}}".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $order_id
 * @property integer $order_before_status_dict_id
 * @property string $order_before_status_name
 * @property integer $order_status_dict_id
 * @property string $order_status_name
 * @property string $admin_id
 */
class OrderStatusHistory extends \common\models\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'order_id', 'order_before_status_dict_id', 'order_status_dict_id', 'admin_id'], 'integer'],
            [['order_id'], 'required'],
            [['order_before_status_name', 'order_status_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => '快照创建时间',
            'updated_at' => '快照修改时间',
            'order_id' => '订单ID',
            'order_before_status_dict_id' => '状态变更前订单状态字典ID',
            'order_before_status_name' => '状态变更前订单状态',
            'order_status_dict_id' => '订单状态字典ID',
            'order_status_name' => '订单状态',
            'admin_id' => '操作人id  0客户操作 1系统操作',
        ];
    }
}
