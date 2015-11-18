<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/5
 * Time: 15:20
 */

namespace core\models\order;

use Yii;
use yii\base\Exception;
use yii\base\Model;

class OrderMsg extends Model
{
    public static function payment($order)
    {
        try {
            $msg = "【预约成功】您已成功预约,服务时长{$order->order_booked_count}小时，{$order->order_service_item_name}，已支付费用".($order->orderExtPay->order_pay_money+
                    $order->orderExtPay->order_use_acc_balance+$order->orderExtPay->order_use_card_money+$order->orderExtPop->order_pop_order_money)."元，订单待指派。如有疑问可致电客服热线4006767636。";
            Yii::$app->sms->send($order->orderExtCustomer->order_customer_phone, $msg);
        } catch (Exception $e) {

        }
    }

    public static function assignDone($order_id)
    {
        try {
            $order = OrderSearch::getOne($order_id);
            $week = ['日', '一', '二', '三', '四', '五', '六'];
            $range = date('H:i', $order->order_booked_begin_time) . '-' . date('H:i', $order->order_booked_end_time);
            $time_msg = date('Y年m月d日', $order->order_booked_begin_time) . "，星期" . $week[date('w', $order->order_booked_begin_time)] . "，" . $range . "，时长" . $order->order_booked_count."小时。";
            $customer_msg = "【订单受理】您预约的服务已由{$order->orderExtWorker->order_worker_name}接单，服务人员电话：{$order->orderExtWorker->order_worker_phone}。如有疑问可致电客服热线4006767636。";
            Yii::$app->sms->send($order->orderExtCustomer->order_customer_phone,$customer_msg);
            $worker_msg = "亲爱的阿姨，您有一个新的待服务订单，订单详情如下：服务时间：{$time_msg}；服务地点:{$order->order_address}；客户电话：{$order->orderExtCustomer->order_customer_phone}. 如有疑问请联系e家洁客服：4006767636";
            Yii::$app->sms->send($order->orderExtWorker->order_worker_phone, $worker_msg);
        } catch (Exception $e) {

        }
    }

    public static function serviceDone($order)
    {
        try {
            $customer_msg = "【服务完成】您预约" . date('y年m月d日', $order->order_booked_begin_time) . "的服务，服务时长" . $order->order_booked_count . "小时，
                         消费" . $order->order_money . "元。感谢您对ｅ家洁的支持，麻烦您对阿姨的服务进行评价，如有疑问请联系客服：4006767636。";
            Yii::$app->sms->send($order->orderExtCustomer->order_customer_phone, $customer_msg);
//        $worker_msg = "亲爱的阿姨，{$order->order_code}订单已经完成，您辛苦了！休息一下吧！";
//        Yii::$app->sms->send($order->orderExtWorker->order_worker_phone,$worker_msg);
        } catch (Exception $e) {

        }
    }

    public static function cancel($order)
    {
        try {
            $customer_msg = "【订单取消】您预约的" . date('y年m月d日H点i分', $order->order_booked_begin_time) . "的" . $order->order_service_item_name . "因" . $order->order_cancel_cause_detail . "已取消，感谢您对e家洁的关注与支持。如有疑问可致电客服热线4006767636。";
            Yii::$app->sms->send($order->orderExtCustomer->order_customer_phone, $customer_msg);
            if ($order->orderExtWorker->worker_id > 0) {
                $worker_msg = "亲爱的阿姨，" . date('y年m月d日H点i分', $order->order_booked_begin_time) . "的订单已被取消，请重新安排您的工作时间，如有疑问请联系e家洁客服：4006767636";
                Yii::$app->sms->send($order->orderExtWorker->order_worker_phone, $worker_msg);
            }
        } catch (Exception $e) {

        }
    }

    public static function cancelAssignWorker($order)
    {
        try {
            if ($order->orderExtWorker->worker_id > 0) {
                $worker_msg = "亲爱的阿姨，" . date('y年m月d日H点i分', $order->order_booked_begin_time) . "的订单已被取消，请重新安排您的工作时间，如有疑问请联系e家洁客服：4006767636";
                Yii::$app->sms->send($order->orderExtWorker->order_worker_phone, $worker_msg);
            }
        } catch (Exception $e) {

        }
    }

    public static function updateAddress($order,$from_address,$to_address)
    {
        if ($order->orderExtWorker->worker_id > 0) {
            $worker_msg = "地点更改：亲爱的阿姨，" . date('y年m月d日H点i分', $order->order_booked_begin_time) . "的订单地点{$from_address}改为{$to_address}，请及时查看，如有疑问请联系e家洁客服：4006767636";
            Yii::$app->sms->send($order->orderExtWorker->order_worker_phone, $worker_msg);
        }
    }

    public static function updateBookedTime($order,$from_time,$to_time)
    {

        try {
            if ($order->orderExtWorker->worker_id > 0) {
                $worker_msg = "时间更改：亲爱的阿姨，" . date('y年m月d日H点i分', $order->$from_time) . "订单时间改为" . date('y年m月d日H点i分', $order->$to_time) . "，请及时查看，如有疑问请联系e家洁客服：4006767636";
                Yii::$app->sms->send($order->orderExtWorker->order_worker_phone, $worker_msg);
            }
        } catch (Exception $e) {

        }
    }

}