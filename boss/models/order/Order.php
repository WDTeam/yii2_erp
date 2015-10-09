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

}