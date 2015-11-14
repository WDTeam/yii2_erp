<?php
namespace console\controllers;

use core\models\order\Order;
use core\models\order\OrderSearch;
use core\models\customer\CustomerComment;

use Yii;
use yii\console\Controller;
use core\components\ConsoleHelper;


class OrderController extends Controller{
    public function actionUnlock(){
        Order::manualAssignUnlock();
        echo 'success';
    }
    /**
     * 处理服务开始
     * @param Order $order
     * @author CoLee
     */
    private function serviceStart($order)
    {
        try{
            if($order['order_booked_begin_time']<=time())
            {
                $res = Order::serviceStart($order['id']);
                ConsoleHelper::log('订单（ID：%s）开始', [$order['id']]);
            }else{
                ConsoleHelper::log('订单（ID：%s）等待中……，将在%s启动', [$order['id'], date('Y-m-d H:i:s', $order['order_booked_begin_time'])]);
            }
        }catch(\Exception $e){
            ConsoleHelper::log('订单（ID：%s）处理时失败了', [$order['id']]);
        }
    }
    /**
     * 处理服务结束
     * @author CoLee
     * @param unknown $order
     */
    private function serviceDone($order)
    {
        try{
            if($order['order_booked_end_time']<=time())
            {
                $res = Order::serviceDone($order['id']);
                ConsoleHelper::log('订单（ID：%s）结束', [$order['id']]);
            }else{
                ConsoleHelper::log('订单（ID：%s）进行中……，将在%s结束', [$order['id'], date('Y-m-d H:i:s', $order['order_booked_end_time'])]);
            }
        }catch(\Exception $e){
            ConsoleHelper::log('订单（ID：%s）处理时失败了', [$order['id']]);
        }
    }
    /**
     * 评价订单
     * @author CoLee
     * @param unknown $order
     * $order['worker_id'] 未定义，请检查错误
     */
    private function suggest($order)
    {
        try{
            CustomerComment::autoaddUserSuggest([
                'order_id'=>$order['id'],
                'worker_id'=>$order['orderExtWorker']['worker_id'],
                'customer_id'=>$order['orderExtCustomer']['customer_id'],
                'worker_tel'=>$order['orderExtWorker']['order_worker_phone'],
                'operation_shop_district_id'=>$order['district_id'],
                'province_id'=>0,
                'city_id'=>$order['city_id'],
                'county_id'=>0,
                'customer_comment_phone'=>$order['orderExtCustomer']['order_customer_phone'],
            ]);
            ConsoleHelper::log('订单（ID：%s）自动评价成功了', [$order['id']]);
        }catch(\Exception $e){
            var_dump($e);
            \Yii::error($e);
            ConsoleHelper::log('订单（ID：%s）自动评价失败了', [$order['id']]);
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
        ConsoleHelper::log('等待开始的订单总数（%s）', [count($waiting_list)]);
        foreach ($waiting_list as $order){
            $this->serviceStart($order);
        }
        
        $service_list = OrderSearch::getStartServiceOrderList();
        ConsoleHelper::log('等待结束的订单总数（%s）', [count($service_list)]);
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