<?php

namespace dbbase\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_status_dict}}".
 *
 * @property integer $id
 * @property string $order_status_name
 * @property string $order_status_customer
 * @property string $order_status_worker
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isdel
 */
class OrderStatusDict extends \common\models\order\ActiveRecord
{
    const ORDER_INIT = 1; //已创建
    const ORDER_WAIT_ASSIGN = 2; //待指派
    const ORDER_SYS_ASSIGN_START = 3; //智能指派开始
    const ORDER_SYS_ASSIGN_DONE = 4; //智能指派完成
    const ORDER_SYS_ASSIGN_UNDONE = 5; //未完成智能指派 待人工指派
    const ORDER_MANUAL_ASSIGN_START = 6; //开始人工指派
    const ORDER_MANUAL_ASSIGN_DONE = 7; //完成人工指派
    const ORDER_MANUAL_ASSIGN_UNDONE = 8; //未完成人工指派，如果客服和小家政都未完成人工指派则去响应，否则重回待指派状态。
    const ORDER_WORKER_BIND_ORDER = 9; //阿姨自助抢单
    const ORDER_SERVICE_START = 10; //开始服务
    const ORDER_SERVICE_DONE = 11; //完成服务
    const ORDER_CUSTOMER_ACCEPT_DONE = 12; //完成评价 可申请结算
    const ORDER_CHECKED = 13; //已核实 已对账
    const ORDER_PAYOFF_DONE = 14; //已完成结算
    const ORDER_PAYOFF_SHOP_DONE = 15; //已完成门店结算
    const ORDER_CANCEL = 16; //已取消
    const ORDER_DIED = 17; //已归档


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status_dict}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status_name'], 'required'],
            [['created_at', 'updated_at', 'isdel'], 'integer'],
            [['order_status_name', 'order_status_customer', 'order_status_worker'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_status_name' => '状态名称',
            'order_status_customer' => '客户端状态名称',
            'order_status_worker' => '阿姨端状态名称',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isdel' => 'Isdel',
        ];
    }
}
