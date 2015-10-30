<?php

namespace core\models\customer;

use core\models\worker\Worker;
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
    public static function getCustomerCommonWorkerList($customer_id,$district_id,$lat,$lng){
        $condition = '';
        $result = self::find()
            ->andWhere($condition)
            ->andWhere(['customer_id'=>$customer_id,'is_block'=>0])
            ->innerjoinWith('workerRelation')
            ->andOnCondition(['worker_is_block'=>0,'worker_is_vacation'=>0,'worker_is_blacklist'=>0])
            ->innerjoinWith('workerDistrictRelation')
            ->andOnCondition(['operation_shop_district_id'=>$district_id])
            ->orderBy('updated_ad desc')
            ->limit(10)
            ->asArray()
            ->all();
        var_dump($result);die;
    }

    /**
     * 获取用户附近阿姨列表
     * @param $customer_id
     */
    public static function getCustomerNearbyWorkerList($customer_id,$lat,$lng,$page=1,$pageNum=10){
        $result = (new \yii\db\Query())
            ->select(['ejj_worker.id',
                'ejj_worker.worker_name',
                'ejj_worker.worker_star',
                'ejj_customer_worker.updated_at',
                'ejj_worker.worker_work_lat',
                'ejj_worker.worker_work_lng'])
            ->from('ejj_customer_worker')
            ->where(['ejj_customer_worker.customer_id'=>$customer_id])
            ->innerJoin('ejj_worker','ejj_customer_worker.worker_id = ejj_worker.id')
            ->union('	SELECT
		ejj_worker.id,
		ejj_worker.worker_name,
		ejj_worker.worker_star,
		0 AS updated_at,
		ejj_worker.worker_work_lat,
		ejj_worker.worker_work_lng
	FROM
		ejj_worker')
            ->limit(10)
            ->all();
        var_dump($result);die;
        $start = ($page-1)*$pageNum;
        $condition = '';
        $result = Worker::find()
            ->andWhere($condition)
            ->andWhere(['worker_is_block'=>0,'worker_is_vacation'=>0,'worker_is_blacklist'=>0])
            ->orderBy('ACOS(SIN(('.$lat.' * 3.1415) / 180 ) *SIN((worker_work_lat * 3.1415) / 180 ) +COS(('.$lat.' * 3.1415) / 180 ) * COS((worker_work_lat * 3.1415) / 180 ) *COS(('.$lng.'* 3.1415) / 180 - (worker_work_lng * 3.1415) / 180 ) ) * 6380 asc')
            ->offset($start)
            ->limit($pageNum)
            ->asArray()
            ->all();
        var_dump($result);die;
    }

    public function getWorkerRelation(){
        return $this->hasOne(Worker::className(),['id'=>'worker_id']);
    }

    public function getWorkerDistrictRelation(){
        return $this->hasOne(Worker::className(),['worker_id'=>'worker_id']);
    }
}
