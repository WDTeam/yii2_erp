<?php
/**
 * 统计条件
 * @author CoLee
 * 暂时用SQL方式实现，以后应该结合统计系统数据共同实现, log by colee 2015-10-30
 */
namespace core\models\worker;

use  core\models\customer\CustomerComment;

use yii\base\Model;
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
        $data = [];
        //取消订单
        $sql = "SELECT COUNT(1) FROM {{%order_ext_worker}} AS a
        LEFT JOIN {{%order_history}} AS b
        ON a.`order_id`=b.order_id
        AND b.`order_status_dict_id`=16
        WHERE b.`order_status_dict_id`=16
        AND b.created_at>={$start_time}
        AND b.created_at<{$end_time}
        AND a.worker_id={$worker_id}";
        $data[1] = (int)\Yii::$app->db->createCommand($sql)->queryScalar();
        //拒绝订单
        $sql = "";
        $data[2] = 0;
        //服务老用户,先算总数，
        $sql = "SELECT COUNT(1) as ct FROM {{%order_ext_worker}} AS a
        LEFT JOIN {{%order_history}} AS b
        ON a.`order_id`=b.order_id  AND b.`order_status_dict_id`=11
        LEFT JOIN ejj_order_ext_customer AS c
        ON c.`order_id`=a.`order_id`
        WHERE b.`order_status_dict_id`=11
        AND b.created_at>={$start_time}
        AND b.created_at<{$end_time}
        AND a.`worker_id`={$worker_id}
        GROUP BY c.`comment_id`";
        $args = (array)\Yii::$app->db->createCommand($sql)->queryColumn();
        $fdl = 0;
        foreach ($args as $d){
            if($d>1){
                $fdl++;
            }
        }
        $division = count($args)==0?1:count($args);
        $data[3] = (int)(($fdl/$division)*100);
        //主动接单
        $sql = "SELECT COUNT(1) FROM {{%order_ext_worker}} AS a
        LEFT JOIN {{%order_status_history}} AS b
        ON a.`order_id`=b.`order_id`
        AND b.order_status_dict_id=4
        WHERE a.order_worker_assign_type=1
        AND a.worker_id={$worker_id}
        AND b.order_status_dict_id=4
        AND b.`created_at`>={$start_time}
        AND b.`created_at`<{$end_time}";
        $data[4] = (int)\Yii::$app->db->createCommand($sql)->queryScalar();
        //完成工时
        $sql = "SELECT COUNT(1)
        FROM {{%order_ext_worker}} AS a
        LEFT JOIN {{%order_history}} AS b ON a.`order_id`=b.order_id
        LEFT JOIN {{%order}} AS c ON c.`id`=a.`order_id`
        WHERE b.order_status_dict_id=11
        AND a.worker_id={$worker_id}
        AND b.created_at>={$start_time}
        AND b.created_at<{$end_time}";
        $data[5] = (int)\Yii::$app->db->createCommand($sql)->queryScalar();
        //好评
        $data[6] = (int)CustomerComment::getCommentworkercount($worker_id,$start_time,$end_time);
    
        return $data;
    }
}