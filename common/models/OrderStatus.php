<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_status}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $order_status_code
 * @property integer $order_status_cancel
 * @property integer $order_status_pey_begin
 * @property integer $order_status_pay_done
 * @property integer $order_status_wait_send
 * @property integer $order_status_send
 * @property integer $order_status_allot
 * @property integer $order_status_labor_send
 * @property integer $order_status_labor_send_failure
 * @property integer $order_status_send_done
 * @property integer $order_status_service_begin
 * @property integer $order_status_service_done
 * @property integer $order_status_comment_done
 * @property integer $order_status_worker_payout
 * @property integer $order_status_php_pay
 * @property integer $order_status_shop_pay
 * @property integer $order_status_payback
 */
class OrderStatus extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'created_at', 'updated_at', 'order_status_code', 'order_status_cancel', 'order_status_pey_begin', 'order_status_pay_done', 'order_status_wait_send', 'order_status_send', 'order_status_allot', 'order_status_labor_send', 'order_status_labor_send_failure', 'order_status_send_done', 'order_status_service_begin', 'order_status_service_done', 'order_status_comment_done', 'order_status_worker_payout', 'order_status_php_pay', 'order_status_shop_pay', 'order_status_payback'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'created_at' => '创建时间初始时间',
            'updated_at' => '更新时间',
            'order_status_code' => '状态码=所有状态的布尔值组合成二进制再转成10进制',
            'order_status_cancel' => '取消订单',
            'order_status_pey_begin' => '付款中',
            'order_status_pay_done' => '已付款',
            'order_status_wait_send' => '待指派',
            'order_status_send' => '系统指派中',
            'order_status_allot' => '待系统分单',
            'order_status_labor_send' => '人工派单中',
            'order_status_labor_send_failure' => '人工派单失败',
            'order_status_send_done' => '待服务',
            'order_status_service_begin' => '服务中',
            'order_status_service_done' => '服务完成待评价',
            'order_status_comment_done' => '评价完成',
            'order_status_worker_payout' => '工人已结算',
            'order_status_php_pay' => '第三方对账',
            'order_status_shop_pay' => '门店已结算',
            'order_status_payback' => '退款 0未 1已退款',
        ];
    }
}
