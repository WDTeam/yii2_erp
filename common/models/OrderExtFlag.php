<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_ext_flag}}".
 *
 * @property string $order_id
 * @property integer $order_flag_send
 * @property integer $order_flag_urgent
 * @property integer $order_flag_exception
 * @property integer $order_flag_sys_assign
 * @property integer $order_flag_lock
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderExtFlag extends \common\models\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_ext_flag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_flag_send', 'order_flag_urgent', 'order_flag_exception', 'order_flag_sys_assign', 'order_flag_lock', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'order_flag_send' => '指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => '加急',
            'order_flag_exception' => '异常 1无经纬度',
            'order_flag_sys_assign' => '是否需要系统指派 1是 0否',
            'order_flag_lock' => '是否锁定 1锁定 0未锁定',
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
