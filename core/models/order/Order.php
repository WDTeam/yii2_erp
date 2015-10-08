<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;

use Yii;
use common\models\Order as OrderModel;
use common\models\OrderHistory;
use common\models\OrderStatusHistory;
use common\models\OrderStatusDict;
use common\models\OrderSrc;

class Order extends OrderModel
{

    /**
     * 追加新订单
     * @param $post
     * @return bool
     */
    public function additional($post)
    {
        if ($post['order']['order_parent_id'] <= 0) {
            $this->addError('order_parent_id', '追加订单必须指定主订单编号！');
        } else {
            $post['order']['order_is_parent'] = 1;
            return $this->create($post);
        }
    }

    /**
     * 创建新订单
     * @param $post
     * @return bool
     */
    public function createNew($post)
    {
        $post['Order']['order_parent_id'] = 0;
        $post['Order']['order_is_parent'] = 0;
        return $this->create($post);
    }

    /**
     * 创建订单
     * @param $post
     * @return bool
     */
    public function create($post)
    {
        if ($this->load($post)) {
            $statusFrom = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //创建订单状态
            $statusTo = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //初始化订单状态
            $orderCode = date('ymdHis') . str_pad($this->order_service_type_id, 2, '0', STR_PAD_LEFT) . str_pad($this->customer_id, 10, '0', STR_PAD_LEFT); //TODO 订单号待优化
            $this->setAttributes([
                'order_code' => $orderCode,
                'order_before_status_dict_id' => $statusFrom->id,
                'order_before_status_name' => $statusFrom->order_status_name,
                'order_status_dict_id' => $statusTo->id,
                'order_status_name' => $statusTo->order_status_name,
                'order_src_name' => $this->getOrderSrcName($this->order_src_id),
                'order_channel_name' => $this->getOrderChannelList($this->channel_id),
                'order_service_type_name' => $this->getServiceList($this->order_service_type_id),
                'order_flag_send' => 0, //'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
                'order_flag_urgent' => 0,//加急 数字越大约紧急
                'order_flag_exception' => 0,//异常标识
                'order_lock_status' => 0,
                'worker_id' => 0,
                'worker_type_id' => 0,
                'order_worker_send_type' => 0,
                'comment_id' => 0,
                'order_customer_hidden' => 0,
                'order_pop_pay_money' => 0,
                'shop_id' => 0,
                'order_worker_type_name' => '',
                'pay_channel_id' => 0,//支付渠道id
                'order_pay_channel_name' => '',//支付渠道
                'order_pay_flow_num' => '',//支付流水号
                'order_pay_money' => 0,//支付金额
                'invoice_id' => 0, //发票id 用户需求中有开发票就绑定发票id
                'checking_id' => 0,
                'isdel' => 0,
            ]);

            if($this->coupon_id>0){ //如果使用了优惠卷
                $coupon = $this->getCouponById($this->coupon_id);
                $this->order_use_coupon_money = $coupon->coupon_money;
            }
            if($this->card_id>0){ //如果使用了服务卡
                $card = $this->getCardById($this->card_id);
                $this->order_use_card_money = $card->card_money;
            }

            if ($this->doSave(true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 保存订单
     * 保存时记录订单历史
     * @param bool $statusChanged
     * @return bool
     */
    public function doSave($statusChanged = false)
    {
        $transaction = static::getDb()->beginTransaction(); //开启一个事务
        $this->order_booked_begin_time = strtotime($this->order_booked_begin_time);
        $this->order_booked_end_time = strtotime($this->order_booked_end_time);
        if ($this->save()) {
            $attributes = $this->attributes;
            foreach ($attributes as $k => $v) {
                $attributes[$k] = ($v === null) ? '' : $v;
            }
            $attributes['order_id'] = $attributes['id'];
            $attributes['order_created_at'] = $attributes['created_at'];
            $attributes['order_updated_at'] = $attributes['updated_at'];
            $attributes['order_isdel'] = $attributes['isdel'];
            unset($attributes['id']);
            unset($attributes['created_at']);
            unset($attributes['updated_at']);
            unset($attributes['isdel']);

            //插入订单操作历史
            $orderHistory = new OrderHistory();
            $orderHistory->setAttributes($attributes);
            if ($statusChanged) { //订单状态有改动
                //插入订单状态变更历史
                $orderStatusHistory = new OrderStatusHistory();
                $orderStatusHistory->setAttributes($attributes);
                if (!$orderStatusHistory->save()) {
                    $transaction->rollBack(); //插入不成功就回滚事务
                    return false;
                }
            }
            if (!$orderHistory->save()) {
                $transaction->rollBack();//插入不成功就回滚事务
            } else {
                $transaction->commit();
                return true;
            }
        }
        return false;
    }

    /**
     * 修改订单状态
     * @param $from OrderStatusDict
     * @param $to OrderStatusDict
     */
    public function statusChange($from, $to)
    {
        $this->setAttributes([
            'order_before_status_dict_id' => $from->id,
            'order_before_status_name' => $from->$order_status_name,
            'order_status_dict_id' => $to->id,
            'order_status_dict_name' => $to->order_status_name
        ]);
        $this->doSave(true);
    }

    /**
     * 根据订单id获取订单
     * @param $id
     * @return null|static
     */
    public static function findById($id)
    {
        return self::findOne($id);
    }

    public function getOrderChannelList($channel_id = 0)
    {
        $channel = [1 => 'BOSS', 2 => '美团', 3 => '大众点评'];
        return $channel_id == 0 ? $channel : (isset($channel[$channel_id]) ? $channel[$channel_id] : false);
    }

    public function getServiceList($service_id = 0)
    {
        $service = [1 => '家庭保洁', 2 => '新居开荒', 3 => '擦玻璃'];
        return $service_id == 0 ? $service : (isset($service[$service_id]) ? $service[$service_id] : false);
    }

    public function getOrderBookedCountList()
    {
        return ["120" => "两小时", "150" => "两个半小时", "180" => "三小时", "210" => "三个半小时", "240" => "四小时", "270" => "四个半小时", "300" => "五小时", "330" => "五个半小时", "360" => "六小时"];
    }

    public function getOrderBookedTimeRangeList($range = 2)
    {
        $order_booked_time_range = [];
        for ($i = 8; $i <= 18; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $hour2 = str_pad($i + intval($range), 2, '0', STR_PAD_LEFT);
            $minute = ($range - intval($range) == 0) ? '00' : '30';
            $order_booked_time_range["{$hour}:00-{$hour2}:{$minute}"] = "{$hour}:00-{$hour2}:{$minute}";
        }
        return $order_booked_time_range;
    }

    public function getOrderSrcName($id)
    {
        return OrderSrc::findOne($id)->order_src_name;
    }

    public function getCouponById($id)
    {
        $coupon = [
            1 => [
                "id" => 1,
                "coupon_name" => "优惠券30",
                "coupon_money" => 30
            ],
            2 => [
                "id" => 2,
                "coupon_name" => "优惠券30",
                "coupon_money" => 30
            ],
            3 => [
                "id" => 3,
                "coupon_name" => "优惠券30",
                "coupon_money" => 30
            ]
        ];
        return $coupon[$id];
    }
    public function getCardById($id)
    {
        $card = [
            1 => [
                "id" => 1,
                "card_code" => "1234567890",
                "card_money" => 1000
            ],
            2 => [
                "id" => 2,
                "coupon_name" => "9876543245",
                "coupon_money" => 3000
            ],
            3 => [
                "id" => 3,
                "coupon_name" => "3840959205",
                "coupon_money" => 5000
            ]
        ];
        return $card[$id];
    }

}