<?php

namespace core\models\customer;

use core\models\worker\Worker;
use Yii;
use yii\helpers\ArrayHelper;

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
     * 获取商圈中阿姨列表
     * *******接口需求*******
     * 当前用户完成订单中服务过的归属于该商圈非系统黑名单/用户黑名单的常用阿姨，
     * 按最后一次服务时间倒序显示在列表最上；
     * 其他阿姨随机显示在常用阿姨下边
     * 仅显示当前日七日后一个月内有可选时间段的阿姨
     * 阿姨列表默认展示按上述规则排序前20位阿姨，下拉刷新，上滑加载更多；
     * ********************×
     * @param $customer_id 用户id
     * @param $district_id 商圈id
     * @param int $page 页数
     * @param int $pageNum 每页返回条数
     * @return array 阿姨列表
     */
    public static function getCustomerDistrictNearbyWorker($customer_id,$district_id,$page=1,$pageNum=20){
        if(empty($customer_id) && empty($district_id)){
            return false;
        }
        $start = ($page-1)*$pageNum;
        //获取阿姨
        $result = (new \yii\db\Query())
            ->select(['worker.id',
                'worker.worker_name',
                'worker.worker_photo',
                'worker.worker_star',
                'customer_worker.updated_at',
                ])
            ->from('{{%worker}} as worker')
            ->where(['customer_worker.is_block'=>0])
            ->leftJoin('{{%customer_worker}} as customer_worker','customer_worker.worker_id = worker.id and worker.worker_is_blacklist=0 and customer_worker.customer_id='.$customer_id)
            ->innerJoin('{{%worker_district}} as worker_district' ,'worker_district.worker_id=worker.id and operation_shop_district_id='.$district_id)
            ->orderBy("updated_at DESC")
            ->offset($start)
            ->limit($pageNum)
            ->all();
        if($result){
            foreach ($result as $key=>$val) {
                $result[$key]['worker_server_num'] = self::countWorkerServerAllCustomer($val['id']);
                $result[$key]['worker_comment_score'] = self::countWorkerServerAllCustomer($val['id']);
            }
        }
        return $data = ['page'=>$page,'pageNum'=>$pageNum,'data'=>$result];;
    }
}
