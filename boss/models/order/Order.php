<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace boss\models\order;

use Yii;
use core\models\order\Order as OrderModel;


class Order extends OrderModel
{

    public $orderBookedDate;
    public $orderBookedTimeRange;



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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return parent::attributeLabels()+[
            'orderBookedDate' => '预约服务日期',
            'orderBookedTimeRange' => '预约服务时间',
        ];
    }


    public function createNew($post)
    {
        $post['Order']['admin_id'] = Yii::$app->user->id;
        $post['Order']['order_ip'] = ip2long(Yii::$app->request->userIP);
        $post['Order']['order_src_id'] = 1; //订单来源BOSS
        //预约时间处理
        $time = explode('-',$post['Order']['orderBookedTimeRange']);
        $post['Order']['order_booked_begin_time'] = strtotime($post['Order']['orderBookedDate'].' '.$time[0].':00');
        $post['Order']['order_booked_end_time'] = strtotime(($time[1]=='24:00')?date('Y-m-d H:i:s',strtotime($post['Order']['orderBookedDate'].'00:00:00 +1 days')):$post['Order']['orderBookedDate'].' '.$time[1].':00');
        if(parent::createNew($post['Order'])){ //创建成功后进行支付
            switch($post['Order']['order_pay_type']){
                case self::ORDER_PAY_TYPE_OFF_LINE://现金支付
                        $order = Order::findOne($this->id);
                        $order->admin_id = $post['Order']['admin_id'];
                        $order->order_ip = $post['Order']['order_ip'];
                        return self::isPaymentOffLine($order);
                    break;
                case self::ORDER_PAY_TYPE_ON_LINE://线上支付

                    break;
                case self::ORDER_PAY_TYPE_POP://第三方预付

                    break;
                default:break;

            }
        }
        return false;
    }

}