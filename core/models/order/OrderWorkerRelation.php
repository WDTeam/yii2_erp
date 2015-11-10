<?php
namespace core\models\order;


use core\models\worker\WorkerStat;
use dbbase\models\order\OrderWorkerRelation as OrderWorkerRelationModel;
use Yii;


class OrderWorkerRelation extends OrderWorkerRelationModel
{

    /**
     * 人工指派未响应
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     * @return bool
     */
    public static function workerContactFailure($order_id,$worker_id,$admin_id)
    {
        $status = OrderOtherDict::NAME_WORKER_CONTACT_FAILURE;
        $memo = '人工指派未响应';
        return self::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id);
    }


    /**
     * 拒绝指派
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     * @param $memo
     * @return bool
     */
    public static function workerRefuse($order_id,$worker_id,$admin_id,$memo)
    {
        //修改阿姨拒单率拒绝订单数量
        WorkerStat::updateWorkerStatRefuseNum($worker_id,1);
        $status = OrderOtherDict::NAME_WORKER_REFUSE;
        return self::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id);
    }

    /**
     * IVR已推送
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     * @return bool
     */
    public static function ivrPushSuccess($order_id,$worker_id,$admin_id)
    {
        $status = OrderOtherDict::NAME_IVR_PUSH_SUCCESS;
        $memo = 'IVR已推送';
        return self::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id);
    }

    /**
     * IVR推送失败
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     * @return bool
     */
    public static function ivrPushFailure($order_id,$worker_id,$admin_id)
    {
        $status = OrderOtherDict::NAME_IVR_PUSH_FAILURE;
        $memo = 'IVR推送失败';
        return self::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id);
    }


    /**
     * 极光已推送
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     * @return bool
     */
    public static function jpushPushSuccess($order_id,$worker_id,$admin_id)
    {
        $status = OrderOtherDict::NAME_JPUSH_PUSH_SUCCESS;
        $memo = 'JPUSH已推送';
        return self::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id);
    }

    /**
     * 极光推送失败
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     * @return bool
     */
    public static function jpushPushFailure($order_id,$worker_id,$admin_id)
    {
        $status = OrderOtherDict::NAME_JPUSH_PUSH_SUCCESS;
        $memo = 'JPUSH推送失败';
        return self::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id);
    }

    /**
     * 添加订单和阿姨的关系信息
     * @param $order_id
     * @param $worker_id
     * @param $memo
     * @param $status
     * @param $admin_id
     * @return bool
     *
     * TODO 需要修改阿姨拒单数量接口
     *
     */
    public static function addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id)
    {
        $order_worker_relation = new OrderWorkerRelation();
        $order_worker_relation->order_id = $order_id;
        $order_worker_relation->worker_id = $worker_id;
        $order_worker_relation->admin_id = $admin_id;
        $order_worker_relation->order_worker_relation_memo = $memo;
        $order_worker_relation->order_worker_relation_status_id = $status;
        $order_worker_relation->order_worker_relation_status = OrderOtherDict::getName($status);
        return $order_worker_relation->save();
    }

    /**
     * 获取订单阿姨关系信息
     * @param $order_id
     * @param array $worker_ids
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListByOrderIdAndWorkerIds($order_id,array $worker_ids)
    {
        return self::find()->where(['order_id'=>$order_id,'worker_id'=>$worker_ids])->all();
    }

    /**
     * 获取阿姨id数组 根据订单id和订单跟阿姨的关系状态id
     * @param $order_id
     * @param $status_id
     * @return array
     */
    public static function getWorkerIdsByOrderIdAndStatusId($order_id,$status_id)
    {
        $result = self::find()->select('worker_id')->where(['order_id'=>$order_id,'order_worker_relation_status_id'=>$status_id])->all();
        $worker_ids = [];
        foreach($result as $v){
            $worker_ids[]=$v['worker_id'];
        }
        return $worker_ids;
    }

}