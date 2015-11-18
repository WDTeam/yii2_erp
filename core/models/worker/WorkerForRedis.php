<?php

namespace core\models\worker;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use dbbase\models\order\OrderExtWorker;
use core\models\worker\Worker;

/**
 * This is the model class for worker redis
 *
 */
class WorkerForRedis extends Model
{

    const DISTRICT_WORKER_RELATION = 'DISTRICT_WORKER_RELATION';
    const WORKER_INFO = 'WORKER_INFO';

    /**
     * 初始化Redis中 所有阿姨信息和商圈关联阿姨信息
     */
    public static function initAllWorkerToRedis(){
        //清除Redis中旧的所有阿姨信息
        self::deleteAllWorker();

        //清除Redis旧的所有商圈关联关系
        self::deleteAllDistrict();
        $i=1;
        $count = 0;
        while(1){
            $limit = 3000;
            $start = ($i-1)*$limit;
            //重新添加阿姨信息到Redis中
            $defaultCondition['isdel'] = 0;
            $defaultCondition['worker_is_block'] = 0;
            $defaultCondition['worker_is_vacation'] = 0;
            $defaultCondition['worker_is_blacklist'] = 0;
            $defaultCondition['worker_is_dimission'] = 0;
            $defaultCondition['worker_auth_status'] = [4,6,8,10];//3基础培训通过,5试工通过,6已上岗的阿姨可以 可以接单
            $workerResult = Worker::find()
                ->select('{{%worker}}.id ,shop_id,worker_name,worker_phone,worker_idcard,worker_identity_id,worker_type')
                ->joinWith('workerDistrictsRelation') //关联worker workerDistrictRelation方法
                ->joinWith('workerScheduleRelation') //关联WorkerScheduleRelation方法
                ->where($defaultCondition)
                ->offset($start)
                ->limit($limit)
                ->asArray()
                ->all();
            $count += count($workerResult);
            if(empty($workerResult)){
                break;
            }
            foreach ((array)$workerResult as $val) {
                //整理阿姨信息
                $workerDataForRedis = self::handleWorkerInfo($val);
                $workerInfo = json_encode($workerDataForRedis);
                Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$val['id'],$workerInfo]);
                //整理阿姨商圈关联信息
                $district = $val['workerDistrictsRelation'];
                $districtIds = ArrayHelper::getColumn($district,'operation_shop_district_id');//['1','2']
                //添加新的阿姨关联商圈信息到Redis中
                self::addDistrictWorkerRelation($val['id'],$districtIds);
            }
            echo "已处理完".$count."个阿姨".PHP_EOL;
            sleep(1);
            $i++;
        }
    }



    /**
     * 初始化单个阿姨信息到Redis
     * 接口调用场景:
     *  阿姨请假休假结束
     *  阿姨封号结束
     *  阿姨黑名单状态被关闭
     *  阿姨离职状态被关闭
     *  阿姨信息异常 初始化阿姨信息
     * @param $worker_id
     * @return bool
     */
    public static function initWorkerToRedis($worker_id){

        if(empty($worker_id)){
            return false;
        }
        //如果阿姨不可用，则停止初始化操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }

        self::initWorker($worker_id);

    }

    /**
     * 从Redis中删除阿姨
     * 调用接口场景
     * 1 当阿姨在后台直接被删除
     * 1 当阿姨被拉入黑名单
     * 2 当阿姨处于休假，请假中
     * 3 当阿姨离职
     * 4 当阿姨处于封号中
     * 4 当阿姨试工不通过时
     * 5 当阿姨上岗被退回
     * @param $worker_id
     * @return bool
     */
    public static function deleteWorkerToRedis($worker_id){
        if(empty($worker_id)) return false;

        //删除Redis中原有的阿姨关联商圈信息
        self::deleteDistrictWorkerRelation($worker_id);

        //删除Redis中原有的阿姨信息
        Yii::$app->redis->executeCommand('del', [self::WORKER_INFO.'_'.$worker_id]);

    }


    /**
     * 从Redis中删除商圈
     * 当商圈下线或商圈被删除时调用
     * @param $district_id
     * @return mixed
     */
    public static function deleteDistrictToRedis($district_id){
        if(empty($district_id)){
            return false;
        }
        return Yii::$app->redis->executeCommand('del', [self::DISTRICT_WORKER_RELATION.'_'.$district_id]);
    }


    /**
     * 添加新阿姨信息到redis
     * @param $worker_id
     * @param $shop_id
     * @param $worker_name
     * @param $worker_phone
     * @param $worker_type
     * @param $worker_identity_id 阿姨身份id 1全时2兼职
     * @return bool
     */
    public static function addWorkerInfoToRedis($worker_id,$shop_id,$worker_phone,$worker_name,$worker_type,$worker_identity_id){
        if(empty($worker_id) || empty($worker_phone) || empty($worker_type)){
            return false;
        }
        //如果阿姨不可用，则停止更新Redis操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }
        $workerInfo['info'] = [
            'worker_id'=>$worker_id,
            'shop_id'=>$shop_id,
            'worker_phone'=>$worker_phone,
            'worker_name'=>$worker_name,
            'worker_identity_id'=>$worker_identity_id,
            'worker_type'=>$worker_type
        ];
        $workerInfo['schedule'] = [];
        $workerInfo['order'] = [];
        $workerInfo = json_encode($workerInfo);
        Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);
        Yii::$app->redis->close();
        return true;
    }

    /**
     * 更新阿姨信息到redis
     * @param $worker_id
     * @param $shop_id
     * @param $worker_name
     * @param $worker_phone
     * @param $worker_type
     * @param $worker_identity_id 阿姨身份id 1全时2兼职
     * @return bool
     */
    public static function updateWorkerInfoToRedis($worker_id,$shop_id,$worker_phone,$worker_name,$worker_type,$worker_identity_id){
        if(empty($worker_id) || empty($worker_phone) || empty($worker_type)){
            return false;
        }
        //如果阿姨不可用，则停止更新操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }

        $workerInfo = Yii::$app->redis->executeCommand('get',[self::WORKER_INFO.'_'.$worker_id]);
        if($workerInfo){
            $workerInfo = json_decode($workerInfo,1);
            $workerInfo['info'] = [
                'worker_id'=>$worker_id,
                'shop_id'=>$shop_id,
                'worker_phone'=>$worker_phone,
                'worker_name'=>$worker_name,
                'worker_identity_id'=>$worker_identity_id,
                'worker_type'=>$worker_type
            ];
            $workerInfo = json_encode($workerInfo);
            Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);
        }else{
            self::initWorker($worker_id);
        }
        Yii::$app->redis->close();
        return true;

    }

    /**
     * 操作阿姨的订单信息
     * @param $worker_id
     * @param $type 操作类型 1添加2修改
     * @param $order_id
     * @param $order_booked_count 订单服务时长
     * @param $order_booked_begin_time 订单开始时间
     * @param $order_booked_end_time 订单结束时间
     * @return bool
     */
    public static function operateWorkerOrderInfoToRedis($worker_id,$type,$order_id,$order_booked_count,$order_booked_begin_time,$order_booked_end_time){
        if(empty($worker_id) || empty($order_id) || empty($order_booked_count) || empty($order_booked_begin_time) || empty($order_booked_end_time) ){
            return false;
        }
        //如果阿姨不可用，则停止更新操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }

        $workerInfo =  Yii::$app->redis->executeCommand('get', [self::WORKER_INFO.'_'.$worker_id]);
        if($workerInfo){
            //整理阿姨订单信息
            /*根据需求 需要给阿姨留有路程上的时间 阿姨订单开始时间-1小时 和 订单结束时间+1小时*/
            $orderInfo['order_id'] = $order_id;
            $orderInfo['order_booked_count'] = intval($order_booked_count);
            $orderInfo['order_booked_begin_time'] = intval($order_booked_begin_time)-3600;
            $orderInfo['order_booked_end_time'] = intval($order_booked_end_time)+3600;
            //添加阿姨订单信息
            if($type==1){
                $workerInfo = json_decode($workerInfo,1);
                array_push($workerInfo['order'],$orderInfo);
                $workerInfo = json_encode($workerInfo);
                Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);
            //修改阿姨订单信息
            }elseif($type==2){
                $workerInfo = json_decode($workerInfo,1);
                foreach ($workerInfo['order'] as $key=>$val) {
                    if($val['order_id']==$order_id){
                        $workerInfo['order'][$key] = $orderInfo;
                    }
                }
                $workerInfo = json_encode($workerInfo);
                Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);
            }else{
                Yii::$app->redis->close();
                return false;
            }
        }else{
            self::initWorker($worker_id);
        }
        Yii::$app->redis->close();
        return true;
    }

    /**
     * 删除阿姨的订单信息
     * @param $worker_id 阿姨id
     * @param $order_id 订单id
     * @return bool
     */
    public static function deleteWorkerOrderInfoToRedis($worker_id,$order_id){
        if(empty($worker_id) || empty($order_id)){
            return false;
        }
        //如果阿姨不可用，则停止更新操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }

        $workerInfo =  Yii::$app->redis->executeCommand('get', [self::WORKER_INFO.'_'.$worker_id]);
        if($workerInfo){
            $workerInfo = json_decode($workerInfo,1);
            foreach ($workerInfo['order'] as $key=>$val) {
                if($val['order_id']==$order_id){
                    unset($workerInfo['order'][$key]);
                }
            }
            $workerInfo = json_encode($workerInfo);
            Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);
        }else{
            self::initWorker($worker_id);
        }
        Yii::$app->redis->close();
        return true;
    }

    /**
     * 更新商圈绑定阿姨关系信息
     * @param $worker_id
     * @param $districtIdsArr
     * @return bool
     */
    public static function operateDistrictWorkerRelationToRedis($worker_id,$districtIdsArr){

        if(empty($worker_id) || empty($districtIdsArr)){
            return false;
        }
        //如果阿姨不可用，则停止更新操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }
        //添加新的商圈绑定阿姨关系 [1,3,4]
        foreach ((array)$districtIdsArr as $val) {
            //如果商圈不存在，默认添加商圈set，并存储阿姨id
            Yii::$app->redis->executeCommand('sadd', [self::DISTRICT_WORKER_RELATION.'_'.$val,$worker_id]);
        }
        Yii::$app->redis->close();
        return true;
    }

    /**
     * 删除Redis中阿姨在商圈中的关联关系
     * @param $worker_id
     * @return bool
     */
    public static function deleteDistrictWorkerRelationToRedis($worker_id){
        if(empty($worker_id)){
           return false;
        }
        if(!self::checkWorkerIsEnabled($worker_id)){
           return false;
        }
        self::deleteDistrictWorkerRelation($worker_id);
        Yii::$app->redis->close();
        return true;
    }

    /**
     * 更新阿姨排班表信息
     * @param $worker_id
     * @return bool
     */
    public static function updateWorkerScheduleInfoToRedis($worker_id){

        if(empty($worker_id)){
            return false;
        }
        //如果阿姨不可用，则停止更新操作
        if(!self::checkWorkerIsEnabled($worker_id)){
            return false;
        }

        $workerScheduleResult = WorkerSchedule::find()->where(['worker_id'=>$worker_id])->asArray()->all();
        if($workerScheduleResult){
            foreach ($workerScheduleResult as $key=>$val) {

                $scheduleInfo[] = [
                    'schedule_id' => $val['id'],
                    'worker_schedule_start_date' => $val['worker_schedule_start_date'],
                    'worker_schedule_end_date' => $val['worker_schedule_end_date'],
                    'worker_schedule_timeline' => json_decode($val['worker_schedule_timeline'],1),
                ];
            }
        }else{
            $scheduleInfo = [];
        }

        $workerInfo = Yii::$app->redis->executeCommand('get', [self::WORKER_INFO.'_'.$worker_id]);
        //如果有阿姨信息则更新阿姨的排班表信息
        if($workerInfo){
            $workerInfo = json_decode($workerInfo,1);
            $workerInfo['schedule'] = $scheduleInfo;
            $workerInfo = json_encode($workerInfo);
            Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);
        //如果阿姨信息在数据库中存在，redis无此阿姨，且阿姨为可用阿姨，则执行初始化此阿姨信息到Redis中
        }else{
            self::initWorker($worker_id);
        }

        Yii::$app->redis->close();
        return true;
    }

    /**
     * 添加指定阿姨的商圈关联信息
     * @param $worker_id
     * @param $districtIdsArr
     */
    private static function addDistrictWorkerRelation($worker_id,$districtIdsArr){
        //添加新的商圈绑定阿姨关系 [1,3,4]
        foreach ((array)$districtIdsArr as $val) {
            //如果商圈不存在，默认添加商圈set，并存储阿姨id
            Yii::$app->redis->executeCommand('sadd', [self::DISTRICT_WORKER_RELATION.'_'.$val,$worker_id]);
        }
    }

    /**
     * 清除指定阿姨的商圈关联关系信息
     * @param $worker_id
     */
    private static function deleteDistrictWorkerRelation($worker_id){
        $districtArr = Yii::$app->redis->executeCommand('keys', [self::DISTRICT_WORKER_RELATION.'_*']);
        foreach ($districtArr as $val) {
            Yii::$app->redis->executeCommand('srem', [$val,$worker_id]);
        }
    }

    /**
     * 清除所有商圈阿姨关联信息
     */
    private static function deleteAllDistrict(){
        $districtsArr = Yii::$app->redis->executeCommand('keys', [self::DISTRICT_WORKER_RELATION.'_*']);
        foreach ((array)$districtsArr as $val) {
            Yii::$app->redis->executeCommand('del', [$val]);
        }
    }

    /**
     * 清除所有阿姨信息
     */
    private static function deleteAllWorker(){
        $workersArr = Yii::$app->redis->executeCommand('keys', [self::WORKER_INFO.'_*']);
        foreach ((array)$workersArr as $val) {
            Yii::$app->redis->executeCommand('del', [$val]);
        }
    }

    /**
     * 整理阿姨信息
     * @param $workerResult 数据库中查询出的阿姨信息结果
     * @return $workerDataForRedis 整理后的阿姨信息
     */
    private static function handleWorkerInfo($workerResult){
        $worker_id = $workerResult['id'];
        //整理阿姨基本信息
        $workerDataForRedis['info'] = [
            'worker_id'=>$workerResult['id'],
            'shop_id'=>$workerResult['shop_id'],
            'worker_name'=>$workerResult['worker_name'],
            'worker_phone'=>$workerResult['worker_phone'],
            'worker_identity_id'=>$workerResult['worker_identity_id'],
            'worker_type'=>$workerResult['worker_type'],
        ];

        //整理阿姨排班表信息
        $schedule = $workerResult['workerScheduleRelation'];
        $workerDataForRedis['schedule'] = [];
        foreach ((array)$schedule as $val) {
            $workerDataForRedis['schedule'][] = [
                'schedule_id'=>$val['id'],
                'worker_schedule_start_date'=>$val['worker_schedule_start_date'],
                'worker_schedule_end_date'=>$val['worker_schedule_end_date'],
                'worker_schedule_timeline'=>json_decode($val['worker_schedule_timeline'],1)
            ];
        }
        //整理阿姨订单信息
        $time = time();
        $orderWorkerResult = OrderExtWorker::find()
            ->select('order_id,worker_id,order_booked_count,order_booked_begin_time,order_booked_end_time')
            ->where(['worker_id'=>$worker_id])
            ->innerJoinWith('order')
            ->onCondition("order_booked_end_time>=$time") //只获取结束时间大于当前时间的订单
            ->asArray()
            ->all();
        $workerDataForRedis['order'] = [];
        foreach ($orderWorkerResult as $val) {
            $workerDataForRedis['order'][] = [
                'order_id'=>$val['order_id'],
                'order_booked_count'=>$val['order_booked_count'],
                /*根据需求 需要给阿姨留有路程上的时间 阿姨订单开始时间-1小时 和 订单结束时间+1小时*/
                'order_booked_begin_time'=>intval($val['order_booked_begin_time'])-3600,
                'order_booked_end_time'=>intval($val['order_booked_end_time'])+3600,
            ];
        }
        return $workerDataForRedis;
    }

    /**
     * 初始化阿姨信息
     * @param $worker_id
     * @return bool
     */
    private static function initWorker($worker_id){
        $workerResult = Worker::find()
            ->select('{{%worker}}.id ,shop_id,worker_name,worker_phone,worker_idcard,worker_identity_id,worker_type')
            ->joinWith('workerDistrictsRelation') //关联worker workerDistrictRelation方法
            ->joinWith('workerScheduleRelation') //关联WorkerScheduleRelation方法
            ->where(['{{%worker}}.id'=>$worker_id])
            ->asArray()
            ->one();
        if(empty($workerResult)){
            return false;
        }
        //整理阿姨信息
        $worker = self::handleWorkerInfo($workerResult);

        //添加阿姨信息到Redis
        $workerInfo = json_encode($worker);
        Yii::$app->redis->executeCommand('set', [self::WORKER_INFO.'_'.$worker_id,$workerInfo]);

        //整理阿姨商圈关联信息
        $district = $workerResult['workerDistrictsRelation'];
        $districtIds = ArrayHelper::getColumn($district,'operation_shop_district_id');//['1','2']
        //删除Redis中原有的阿姨关联商圈信息
        self::deleteDistrictWorkerRelation($worker_id);
        //添加新的阿姨关联商圈信息到Redis中
        self::addDistrictWorkerRelation($worker_id,$districtIds);
    }

    /**
     * 检查此阿姨是否可用
     * @param $worker_id
     * @return bool
     */
    private static function checkWorkerIsEnabled($worker_id){
        $condition['id'] = $worker_id;
        $condition['isdel'] = 0;
        $condition['worker_is_block'] = 0;
        $condition['worker_is_vacation'] = 0;
        $condition['worker_is_blacklist'] = 0;
        $condition['worker_is_dimission'] = 0;
        $condition['worker_auth_status'] = [4,6,8,9,10];//4基础培训通过6试工通过8已上岗9晋升培训失败10已晋升培训
        $result = Worker::find()->where($condition)->asArray()->one();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取阿姨排班表
     * @param $worker_id
     * @return bool
     */
    public static function getWorkerSchedule($worker_id){
        $worker =  Yii::$app->redis->executeCommand('get', [self::WORKER_INFO.'_'.$worker_id]);
        if($worker){
            $worker = json_decode($worker,1);
            return $worker['schedule'];
        }else{
            return [];
        }

    }

    public static function checkWorkerIsInRedis($worker_id){
        if(empty($worker_id)){
            return false;
        }
        $workerInfo = Yii::$app->redis->executeCommand('get',[self::WORKER_INFO.'_'.$worker_id]);
        if($workerInfo){
            return true;
        }else{
            return false;
        }
    }


}
