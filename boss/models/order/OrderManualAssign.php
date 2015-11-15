<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/10
 * Time: 13:58
 */
namespace boss\models\order;

use core\models\customer\Customer;
use core\models\order\OrderManualAssign as OrderManualAssignModel;
use core\models\order\OrderWorkerRelation;
use Yii;

class OrderManualAssign extends OrderManualAssignModel
{
    /**
     * 获取待客服指派的订单
     * 订单状态为系统指派失败的订单
     * @author lin
     * @param $admin_id 操作人id
     * @return $this|static
     */
    public static function getWaitAssignOrder($admin_id)
    {
        $order = parent::getWaitAssignOrder($admin_id);
        return self::_formatOrder($order);
    }

    /**
     * 获取待小家政指派的订单
     * 订单状态为系统指派失败的订单
     * @author lin
     * @param $admin_id 操作人id
     * @param $district_ids 商圈ids
     * @return $this|static
     */
    public static function getMiniBossWaitAssignOrder($admin_id,$district_ids)
    {
        $order = parent::getMiniBossWaitAssignOrder($admin_id,$district_ids);
        return self::_formatOrder($order);
    }


    /**
     * 获取可指派阿姨的列表
     * @param $order_id
     * @return array
     */
    public static function getCanAssignWorkerList($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $worker_list = parent::getCanAssignWorkerList($order);
        if(isset($worker_list['code']) || !$worker_list){
            return $worker_list;
        }else{
            $workers = self::_formatWorker($order,$worker_list);
            return ['code' => 200, 'data' => $workers];
        }
    }

    /**
     * 获取小家政可指派阿姨的列表
     * @param $order_id
     * @param $shop_ids
     * @return array
     */
    public static function getMiniBossCanAssignWorkerList($order_id,$shop_ids)
    {
        $order = OrderSearch::getOne($order_id);
        $worker_list = parent::getCanAssignWorkerList($order);
        if($worker_list){
            $workers = self::_formatWorker($order,$worker_list,$shop_ids);
            return ['code' => 200, 'data' => $workers];
        }else {
            return ['code' => 500, 'msg' => '获取阿姨列表接口异常！'];
        }
    }


    /**
     * 搜索阿姨
     * @param $order_id
     * @param $worker_name
     * @param $phone
     * @return array
     */
    public static function searchAssignWorker($order_id,$worker_name,$phone)
    {
        $order = OrderSearch::getOne($order_id);
        $worker_list = parent::searchWorker($worker_name, $phone);
        if($worker_list){
            $workers = self::_formatWorker($order, $worker_list);
            return ['code' => 200, 'data' => $workers];
        }else{
            return ['code' => 500, 'msg' => '获取阿姨列表接口异常！'];
        }
    }

    /**
     * 搜索阿姨
     * @param $order_id
     * @param $worker_name
     * @param $phone
     * @param $shop_ids
     * @return array
     */
    public static function searchMiniBossAssignWorker($order_id,$worker_name,$phone,$shop_ids)
    {
        $order = OrderSearch::getOne($order_id);
        $worker_list = parent::searchWorker($worker_name, $phone);
        if($worker_list){
            $workers = self::_formatWorker($order, $worker_list,$shop_ids);
            return ['code' => 200, 'data' => $workers];
        }else{
            return ['code' => 500, 'msg' => '获取阿姨列表接口异常！'];
        }
    }



    /**
     * 格式化手工派单页面展示
     * @param $order
     * @return array|bool
     */
    private static function _formatOrder($order)
    {
        if ($order) {
            $week = ['日', '一', '二', '三', '四', '五', '六'];
            $booked_time_range = date("Y-m-d  （周", $order->order_booked_begin_time) . $week[date('w', $order->order_booked_begin_time)] . date('）  H:i-', $order->order_booked_begin_time) . date('H:i', $order->order_booked_end_time);
            $ext_pay = $order->orderExtPay;
            if ($order->order_is_parent > 0) {
                $orders = OrderSearch::getChildOrder($order->id);
                foreach ($orders as $child) {
                    $order->order_money += $child->order_money;
                    if ($ext_pay->order_pay_type == OrderExtPay::ORDER_PAY_TYPE_ON_LINE) {
                        $ext_pay->order_pay_money += $child->orderExtPay->order_pay_money;
                        $ext_pay->order_use_acc_balance += $child->orderExtPay->order_use_acc_balance;
                        $ext_pay->order_use_card_money += $child->orderExtPay->order_use_card_money;
                        $ext_pay->order_use_coupon_money += $child->orderExtPay->order_use_coupon_money;
                        $ext_pay->order_use_promotion_money += $child->orderExtPay->order_use_promotion_money;
                    }
                    $booked_time_range .= '<br/>' . date("Y-m-d  （周", $child->order_booked_begin_time) . $week[date('w', $child->order_booked_begin_time)] . date('）  H:i-', $child->order_booked_begin_time) . date('H:i', $child->order_booked_end_time);
                }
            }
            $workers = [];
            if ($order->order_booked_worker_id > 0) {
                $worker_list = Worker::getWorkerStatInfo($order->order_booked_worker_id);
                if (!empty($worker_list)) {
                    $workers = Order::assignWorkerFormat($order, [$worker_list]);
                }
            }
            return
                [
                    'order' => $order,
                    'ext_pay' => $ext_pay,
                    'ext_pop' => $order->orderExtPop,
                    'ext_customer' => $order->orderExtCustomer,
                    'ext_flag' => $order->orderExtFlag,
                    'operation_long_time' => Yii::$app->params['order']['MANUAL_ASSIGN_lONG_TIME'],
                    'booked_time_range' => $booked_time_range,
                    'booked_workers' => $workers
                ];
        } else {
            return false;
        }
    }

    /**
     * 可指派的阿姨格式化
     * @param $order
     * @param $worker_list
     * @param $shop_ids
     * @return array
     */
    private static function _formatWorker($order,$worker_list,$shop_ids = null){
        //获取常用阿姨
        $used_worker_list = Customer::getCustomerUsedWorkers($order->orderExtCustomer->customer_id);
        $used_worker_ids = [];
        if (is_array($used_worker_list)) {
            foreach ($used_worker_list as $v) {
                $used_worker_ids[] = $v['worker_id'];
            }
        }
        $worker_ids = [];
        $workers = [];
        if (is_array($worker_list)) {
            foreach ($worker_list as $k => $v) {
                if($shop_ids!=null && !in_array($v['shop_id'],$shop_ids)){
                   continue;
                }else {
                    $worker_ids[] = $v['id'];
                    $workers[$v['id']] = $worker_list[$k];
                    $workers[$v['id']]['tag'] = in_array($v['id'], $used_worker_ids) ? '服务过' : "";
                    $workers[$v['id']]['tag'] = ($v['id'] == $order->order_booked_worker_id) ? '指定阿姨' : $workers[$v['id']]['tag'];
                    $workers[$v['id']]['order_booked_time_range'] = [];
                    $workers[$v['id']]['memo'] = [];
                    $workers[$v['id']]['status'] = [];
                }
            }
            //获取阿姨当天订单
            $worker_orders = OrderSearch::getListByWorkerIds($worker_ids, $order->order_booked_begin_time);
            foreach ($worker_orders as $v) {
                $workers[$v->orderExtWorker->worker_id]['order_booked_time_range'][] = date('H:i', $v->order_booked_begin_time) . '-' . date('H:i', $v->order_booked_end_time);
            }
            //获取阿姨跟订单的关系
            $order_worker_relations = OrderWorkerRelation::getListByOrderIdAndWorkerIdsForManualAssign($order->id, $worker_ids);
            foreach ($order_worker_relations as $v) {
                $workers[$v->worker_id]['memo'][] = $v->order_worker_relation_memo;
                $workers[$v->worker_id]['status'][] = $v->order_worker_relation_status;
            }
        }
        return $workers;
    }
}