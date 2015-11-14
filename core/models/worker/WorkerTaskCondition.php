<?php
/**
 * 统计条件
 * @author CoLee
 * 
 */
namespace core\models\worker;

use yii\base\Model;
use core\models\order\OrderSearch;
class WorkerTaskCondition extends Model
{

    /**
     * 指定时间段内阿姨各个条件完成值
     *  条件类型：
     1=>'取消订单 ',
     2=>'拒绝订单',
     3=>'服务老用户',
     4=>'主动接单',
     5=>'完成工时',
     6=>'好评 ',
     * @param unknown $start_time
     * @param unknown $end_time
     * @param unknown $worker_id
     */
    public static function getConditionsValues($start_time, $end_time, $worker_id)
    {
        $data = OrderSearch::getStatistical($start_time, $end_time, $worker_id);
        return $data;
    }
}