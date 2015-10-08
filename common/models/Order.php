<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $order_code
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isdel
 * @property integer $order_ip
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_count
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $address_id
 * @property string $order_address
 * @property string $order_booked_worker_id
 * @property string $checking_id
 * @property string $order_cs_memo
 *
 * @property OrderExtCustomer $orderExtCustomer
 * @property OrderExtFlag $orderExtFlag
 * @property OrderExtPay $orderExtPay
 * @property OrderExtPop $orderExtPop
 * @property OrderExtStatus $orderExtStatus
 * @property OrderExtWorker $orderExtWorker
 */
class Order extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'order_src_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id', 'checking_id'], 'integer'],
            [['order_unit_money', 'order_money'], 'number'],
            [['order_code', 'order_channel_name'], 'string', 'max' => 64],
            [['order_service_type_name', 'order_src_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo'], 'string', 'max' => 255],
            [['order_code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'order_code' => '订单号',
            'order_parent_id' => '父级id',
            'order_is_parent' => '有无子订单 1有 0无',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'isdel' => '是否已删除',
            'order_ip' => '下单IP',
            'order_service_type_id' => '订单服务类别ID',
            'order_service_type_name' => '订单服务类别',
            'order_src_id' => '订单来源，订单入口id',
            'order_src_name' => '订单来源，订单入口名称',
            'channel_id' => '订单渠道ID',
            'order_channel_name' => '订单渠道名称',
            'order_unit_money' => '订单单位价格',
            'order_money' => '订单金额',
            'order_booked_count' => '预约服务数量（时长）',
            'order_booked_begin_time' => '预约开始时间',
            'order_booked_end_time' => '预约结束时间',
            'address_id' => '地址ID',
            'order_address' => '详细地址 包括 联系人 手机号',
            'order_booked_worker_id' => '指定阿姨',
            'checking_id' => '对账id',
            'order_cs_memo' => '客服备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtCustomer()
    {
        return $this->hasOne(OrderExtCustomer::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtFlag()
    {
        return $this->hasOne(OrderExtFlag::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtPay()
    {
        return $this->hasOne(OrderExtPay::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtPop()
    {
        return $this->hasOne(OrderExtPop::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtStatus()
    {
        return $this->hasOne(OrderExtStatus::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtWorker()
    {
        return $this->hasOne(OrderExtWorker::className(), ['order_id' => 'id']);
    }
}
