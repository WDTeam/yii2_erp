<?php
namespace core\models\order;


use common\models\OrderWorkerRelation as OrderWorkerRelationModel;
use Yii;


class OrderWorkerRelation extends OrderWorkerRelationModel
{

    /**
     * 添加订单和阿姨的关系信息
     * @param $order_id
     * @param $worker_id
     * @param $memo
     * @param $status
     * @param $admin_id
     * @return bool
     */
    public static function addOrderWorkerRelation($order_id,$worker_id,$memo,$status,$admin_id)
    {
        $order_worker_relation = new OrderWorkerRelation();
        $order_worker_relation->order_id = $order_id;
        $order_worker_relation->worker_id = $worker_id;
        $order_worker_relation->admin_id = $admin_id;
        $order_worker_relation->order_worker_relation_memo = $memo;
        $order_worker_relation->order_worker_relation_status = $status;
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

    public static function getWorkerIdsByOrderIdAndStatus($order_id,$status)
    {
        $result = self::find()->select('worker_id')->where(['order_id'=>$order_id,'order_worker_relation_status'=>$status])->all();
        $worker_ids = [];
        foreach($result as $v){
            $worker_ids[]=$v['worker_id'];
        }
        return $worker_ids;
    }

}