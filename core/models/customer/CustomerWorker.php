<?php

namespace core\models\customer;

use core\models\worker;
use Yii;

/**
 * This is the model class for table "{{%customer_worker}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $woker_id
 * @property integer $customer_worker_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerWorker extends \dbbase\models\customer\CustomerWorker
{

    /**
     * 删除常用/黑名单阿姨       郝建设
     * @param $costomer_id int 用户id
     * @param $worker      int 阿姨id
     * @param $type        int 标示类型，1时判断黑名单阿姨，0时判断常用阿姨
     * return  bool 
     */
    public static function deleteWorker($costomer_id, $worker, $type = 1, $is_block_del = 1)
    {
        $dataUserId = CustomerWorker::find()->where(['customer_id' => $costomer_id, 'worker_id' => $worker])->one();

        if ($dataUserId) {
            if ($type) {
                #数据逻辑删除 1常用阿姨
                $dataUserId->is_del = 1;
            } else {
                #数据逻辑删除 1黑名单阿姨
                if ($is_block_del) {
                    $dataUserId->is_block = 1;
                } else {
                    $dataUserId->is_block = 0;
                }
            }
            $dataUserId->updated_at = time();
            return $dataUserId->save(false);
        } else {
            return false;
        }

//       数据真实删除
//            $customerAddress = CustomerWorker::findOne($dataUserId['id']);
//            if ($customerAddress == NULL) {
//                return false;
//            }
//            CustomerWorker::deleteAll(['id' => $dataUserId['id']]);
//            $customerAddress = CustomerWorker::findOne($dataUserId['id']);
//            if ($customerAddress == NULL) {
//                return true;
//            } else {
//                return false;
//            }
        #  }
    }

    /**
     * 黑名单阿姨列表      郝建设
     * @param $costomer_id int 用户id
     * @param $is_block      int 黑名单标示
     * return  array 阿姨黑名单数据 
     */
    public static function blacklistworkers($costomer_id, $is_block)
    {
        $dataUserId = CustomerWorker::find()->where(['customer_id' => $costomer_id, 'is_block' => $is_block])->asArray()->All();

        $array = array();
        if (!empty($dataUserId)) {
            foreach ($dataUserId as $key => $val) {
                $workerData = \core\models\worker\Worker::getWorkerInfo($val['worker_id']);
                $array[] = $workerData;
            }
            return $array;
        } else {
            return false;
        }
    }

    /**
     * 统计阿姨服务过的用户数量
     * @param $worker_id
     * @return int|string
     */
    public static function countWorkerServerAllCustomer($worker_id){
        $result = CustomerWorker::find()->where(['worker_id'=>$worker_id])->count();
        return $result;
    }

    /**
     * 获取用户常用阿姨列表
     * @param $customer_id
     */
    public static function getCustomerCommonWorkerList($customer_id){

    }

    /**
     * 获取用户附近阿姨列表
     * @param $customer_id
     */
    public static function getCustomerNearbyWorkerList($customer_id){

    }

}
