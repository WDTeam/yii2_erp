<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/24
 * Time: 14:18
 */
namespace core\models\order;


use common\models\OrderStatusDict;
use Yii;
use core\models\worker\Worker;

class OrderPush extends Order
{

    const WAIT_IVR_PUSH_ORDERS_POOL = 'WAIT_IVR_PUSH_ORDERS_POOL';
    const PUSH_ORDER_LOCK = 'PUSH_ORDER_LOCK';

    /**
     * ��������
     * @param $order_id
     * @return array
     *
     *
     */
    public static function push($order_id)
    {
        $order = OrderSearch::getOne($order_id);

        $full_time = 1; //ȫְ
        $part_time = 2; //��ְ
        $push_status = 0; //����״̬
        if ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SYS_ASSIGN_START) { //��ʼϵͳָ�ɵĶ���
            if($order->order_booked_worker_id>0 && time() - $order->orderExtStatus->updated_at < 900){ //���ж���û��ָ������
                $workers[] = Worker::getWorkerInfo($order->order_booked_worker_id);
            }elseif (time() - $order->orderExtStatus->updated_at < 300) { //TODO 5�����ڵĶ������͸�ȫְ���� 5������Ҫ����
                //��ȡȫְ����
                $workers = Worker::getDistrictFreeWorker($order->district_id, $full_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                $push_status = $full_time;
                if (empty($workers)) {
                    //û��ȫְ���� ��ȡ��ְ����
                    $workers = Worker::getDistrictFreeWorker($order->district_id, $part_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                    $push_status = $part_time;
                }
            } elseif (time() - $order->orderExtStatus->updated_at < 900) { //TODO 15�����ڵĶ������͸���ְ���� 15������Ҫ����
                $workers = Worker::getDistrictFreeWorker($order->district_id, $part_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                $push_status = $part_time;
            }
            if (!empty($workers)) {
                self::pushToWorkers($order_id, $workers, $push_status);
            } else {//�����ѯ������ְ������ϵͳָ��ʧ��
                Order::sysAssignUndone($order_id);
            }
        } else {
            //״̬��������ָ����ֱ�ӴӶ�������ɾ��
            OrderPool::remOrder($order_id);
        }

        $order = OrderSearch::getOne($order_id);
        return ['order_id' => $order->id, 'created_at' => $order->orderExtStatus->updated_at, 'jpush' => $order->orderExtFlag->order_flag_worker_jpush, 'ivr' => $order->orderExtFlag->order_flag_worker_ivr, 'push_status'=>$push_status];
    }

    /**
     * ���͸�����
     * @param $order_id
     * @param $workers
     * @param $identity
     */
    public static function pushToWorkers($order_id, $workers, $identity)
    {
        $ivr_flag = false;
        $jpush_flag = false;
        $is_ivr_worker_ids = OrderWorkerRelation::getWorkerIdsByOrderIdAndStatus($order_id, 'IVR������');
        $is_jpush_worker_ids = OrderWorkerRelation::getWorkerIdsByOrderIdAndStatus($order_id, 'JPUSH������');
        foreach ($workers as $v) {
            if (!in_array($v['id'], $is_ivr_worker_ids)) { //�жϸð�����û�����͹��ö�������ֹ�ظ����͡�
                //�Ѹ�����ivr�İ��̷���ö����Ķ�����
                Yii::$app->redis->executeCommand('rPush', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id, json_encode(['id' => $v['id'], 'worker_phone' => $v['worker_phone']])]);
                $ivr_flag = true;
            }
            if (!in_array($v['id'], $is_jpush_worker_ids)) {
                $result = Yii::$app->jpush->push(["worker_{$v['id']}"], '����������'); //TODO ��������
                if (isset($result->isOK)) {
                    $worker_id = intval(str_replace('worker_', '', $v));
                    OrderWorkerRelation::addOrderWorkerRelation($order_id, $worker_id, '', 'JPUSH������', 1);
                    $jpush_flag = true;
                }
            }
        }
        if ($ivr_flag) {
            self::workerIVRPushFlag($order_id); //���ivr����
        }
        if ($jpush_flag) {
            self::workerJPushFlag($order_id); //��Ǽ�������
        }

        //���¼��붩����
        OrderPool::updateOrder($order_id,$identity);

        self::ivrPushToWorker($order_id); //��ʼivr����
    }

    /**
     * ivr���͸���������
     * @param $order_id
     */
    public static function ivrPushToWorker($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        if ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SYS_ASSIGN_START) { //��ʼϵͳָ�ɵĶ���
            $worker = json_decode(Yii::$app->redis->executeCommand('lPop', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id]), true);
            if (!empty($worker)) {
                $result = Yii::$app->ivr->send($worker['worker_phone'], 'pushToWorker_' . $order_id, "��ʼʱ��" . date('y��m��d��H��', $order->order_booked_begin_time) . "��ʱ��" . intval($order->order_booked_count / 60) . "��" . ($order->order_booked_count % 60 > 0 ? "��" : "") . "Сʱ����ַ{$order->order_address}��"); //TODO ��������
                if (isset($result['result']) && $result['result'] == 0) {
                    OrderWorkerRelation::addOrderWorkerRelation($order_id, $worker['id'], '', 'IVR������', 1);
                } else {
                    OrderWorkerRelation::addOrderWorkerRelation($order_id, $worker['id'], '', 'IVR����ʧ��', 1);
                }
            }
        } else {
            //�Ƴ��ö����Ķ���
            Yii::$app->redis->executeCommand('del', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id]);
        }
    }

    /**
     * ��Ƕ����ѷ��Ͷ��Ÿ�����
     * @param $order_id
     * @return bool
     */
    public static function workerSMSPushFlag($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->order_flag_worker_sms = $order->orderExtFlag->order_flag_worker_sms + 1;
        return $order->doSave(['OrderExtFlag']);
    }

    /**
     * ��Ƕ��������ͼ��������
     * @param $order_id
     * @return bool
     */
    public static function workerJPushFlag($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        $order->order_flag_worker_jpush = $order->orderExtFlag->order_flag_worker_jpush + 1;
        return $order->doSave(['OrderExtFlag']);
    }

    /**
     * ��Ƕ����ѷ���IVR������
     * @param $order_id
     * @return bool
     */
    public static function workerIVRPushFlag($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        $order->order_flag_worker_ivr = $order->orderExtFlag->order_flag_worker_ivr + 1;
        return $order->doSave(['OrderExtFlag']);
    }

}