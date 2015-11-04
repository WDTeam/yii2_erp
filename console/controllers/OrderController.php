<?php
namespace console\controllers;

use core\models\order\Order;
use core\models\order\OrderSearch;
use core\models\customer\CustomerComment;

use Yii;
use yii\console\Controller;


class OrderController extends Controller{
    public function actionUnlock(){
        Order::manualAssignUnlock();
        echo 'success';
    }
    /**
     * 处理服务开始
     * @param unknown $order
     * @author CoLee
     */
    private function serviceStart($order)
    {
        try{
            if($order['order_booked_begin_time']<=time())
            {
                $res = Order::serviceStart($order['id']);
                echo 'Order ID:'.$order['id'].' start success!'.PHP_EOL;
            }else{
                echo 'Order ID:'.$order['id'].' time:'.date('Y-m-d H:i:s', $order['order_booked_begin_time']).' waiting!'.PHP_EOL;
            }
        }catch(\Exception $e){
            echo 'Order ID:'.$order['id'].' start error!'.PHP_EOL;
        }
    }
    /**
     * 处理服务结束
     * @author CoLee
     * @param unknown $order_id
     */
    private function serviceDone($order)
    {
        try{
            if($order['order_booked_end_time']>=time())
            {
                $res = Order::serviceDone($order['id']);
                echo 'Order ID:'.$order['id'].' Service done!'.PHP_EOL;
            }else{
                echo 'Order ID:'.$order['id'].' time:'.date('Y-m-d H:i:s', $order['order_booked_end_time']).' Service!'.PHP_EOL;
            }
        }catch(\Exception $e){
            echo 'Order ID:'.$order['id'].' done error!'.PHP_EOL;
        }
    }
    /**
     * 评价订单
     * @author CoLee
     * @param unknown $order
     */
    private function suggest($order)
    {
//         var_dump($order);exit;
        try{
            CustomerComment::autoaddUserSuggest([
                'order_id'=>$order['id'],
                'worker_id'=>$order['worker_id'],
                'customer_id'=>$order['customer_id'],
                'worker_tel'=>$order['order_worker_phone'],
                'operation_shop_district_id'=>$order['district_id'],
                'province_id'=>0,
                'city_id'=>$order['city_id'],
                'county_id'=>0,
                'customer_comment_phone'=>$order['order_customer_phone'],
            ]);
        }catch(\Exception $e){
            echo 'Order ID:'.$order['id'].' suggest error!'.PHP_EOL;
        }
    }
    /**
     * 定时处理服务状态
     * @author CoLee
     * 5分钟一次 
     * use: *\/5 * * * * yii order/change-service-status
     */
    public function actionChangeServiceStatus()
    {
        $waiting_list = OrderSearch::getWaitServiceOrderList();
        echo 'Waiting Total:'.count($waiting_list).PHP_EOL;
        foreach ($waiting_list as $order){
            $this->serviceStart($order);
        }
        
        $service_list = OrderSearch::getStartServiceOrderList();
        echo 'Service Total:'.count($service_list).PHP_EOL;
        foreach ($service_list as $order){
            $this->serviceDone($order);
        }
        
        //订单评论
        $orders = OrderSearch::getWaitSysCommentOrderList();
        foreach ($orders as $order){
           $this->suggest($order);
        }
    }
}