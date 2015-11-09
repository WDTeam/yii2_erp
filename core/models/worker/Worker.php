<?php

namespace core\models\worker;


use core\models\customer\CustomerComment;
use dbbase\models\Help;
use JPush\Exception\APIRequestException;
use Symfony\Component\Console\Helper\Helper;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

use dbbase\models\order\OrderExtWorker;
use core\models\shop\ShopManager;
use core\models\shop\Shop;

use core\models\worker\WorkerVacation;
use core\models\worker\WorkerVacationApplication;
use core\models\worker\WorkerStat;
use core\models\worker\WorkerExt;
use core\models\worker\WorkerIdentityConfig;
use core\models\worker\WorkerRuleConfig;
use core\models\worker\WorkerSkill;
use core\models\worker\WorkerSkillConfig;
use core\models\worker\WorkerSchedule;
use core\models\customer\CustomerWorker;
use core\models\operation\OperationShopDistrict;
use core\models\operation\OperationCity;
use core\models\operation\OperationArea;
use crazyfd\qiniu\Qiniu;

/**
 * This is the model class for table "{{%worker}}".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $worker_name
 * @property string $worker_phone
 * @property string $worker_idcard
 * @property string $worker_password
 * @property string $worker_photo
 * @property integer $worker_level
 * @property integer $worker_auth_status
 * @property integer $worker_work_city
 * @property integer $worker_work_area
 * @property string $worker_work_street
 * @property double $worker_work_lng
 * @property double $worker_work_lat
 * @property integer $worker_type
 * @property integer $worker_rule_id
 * @property integer $worker_identity_id
 * @property integer $worker_is_block
 * @property integer $worker_is_blacklist
 * @property integer $worker_is_vacation
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $isdel
 */
class Worker extends \dbbase\models\worker\Worker
{

    const DISTRICT = 'DISTRICT';
    const WORKER = 'WORKER';
    /**
     * 获取阿姨基本信息
     * @param integer $worker_id  阿姨id
     * @return array 阿姨信息
     */
    public static function getWorkerInfo($worker_id){
        if(empty($worker_id)){
            return [];
        }else{
            $workerInfo = self::find()->where((['id'=>$worker_id]))->select('id,shop_id,worker_name,worker_phone,worker_idcard,worker_type,worker_photo,worker_identity_id,worker_star,created_ad')->asArray()->one();
            if($workerInfo){
                //门店名称,家政公司名称
                $shopInfo = Shop::findone($workerInfo['shop_id']);
                if($shopInfo){
                    $shopManagerInfo = ShopManager::findOne($shopInfo['shop_manager_id']);
                }
                $workerInfo['shop_name'] = isset($shopInfo['name'])?$shopInfo['name']:'';
                $workerInfo['shop_manager_id'] = isset($shopInfo['shop_manager_id'])?$shopInfo['shop_manager_id']:'';
                $workerInfo['worker_skill'] = WorkerSkill::getWorkerSkill($worker_id);
                $workerInfo['shop_manager_name'] = isset($shopManagerInfo['name'])?$shopManagerInfo['name']:'';
                $workerInfo['worker_type_description'] = self::getWorkerTypeShow($workerInfo['worker_type']);
                $workerInfo['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($workerInfo['worker_identity_id']);
            }else{
                $workerInfo = [];
            }
            return $workerInfo;
        }
    }



    /**
     * 获取阿姨详细信息
     * @param $worker_id 阿姨id
     * @return array 阿姨详细信息
     */
    public static function getWorkerDetailInfo($worker_id){
        if(empty($worker_id)){
            return [];
        }else{
            $workerDetailResult = self::find()
                ->where(['id'=>$worker_id])
                ->select('id,shop_id,worker_name,worker_idcard,worker_phone,worker_photo,worker_age,worker_type,worker_identity_id,worker_star,worker_sex,worker_edu,worker_stat_order_num,worker_stat_order_refuse,worker_stat_order_complaint,worker_stat_order_money,worker_live_province,worker_live_city,worker_live_area,worker_live_street')
                ->joinWith('workerExtRelation')
                ->joinWith('workerStatRelation')
                ->asArray()
                ->one();

            if(!empty($workerDetailResult)){
                $workerDetailResult['worker_sex'] = Worker::getWorkerSexShow($workerDetailResult['worker_sex']);
                $workerDetailResult['worker_type_description'] = self::getWorkerTypeShow($workerDetailResult['worker_type']);
                $workerDetailResult['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($workerDetailResult['worker_identity_id']);
                $workerDetailResult['worker_live_place'] = self::getWorkerPlaceShow($workerDetailResult['worker_live_province'],$workerDetailResult['worker_live_city'],$workerDetailResult['worker_live_area'],$workerDetailResult['worker_live_street']);
                $workerDetailResult['worker_district'] = self::getWorkerDistrict($worker_id);
                //$workerDetailResult['worker_skill'] = WorkerSkill::getWorkerSkill($worker_id);
                $workerDetailResult['worker_comment'] = CustomerComment::getWorkerCommentCount($worker_id);
                unset($workerDetailResult['workerStatRelation']);
                unset($workerDetailResult['workerExtRelation']);
            }else{
                return [];
            }
            return $workerDetailResult;
        }
    }

    /**
     * 获取阿姨统计信息
     * @param $worker_id 阿姨id
     * @return array 阿姨统计信息
     */
    public static function getWorkerStatInfo($worker_id){
        if(empty($worker_id)){
            return [];
        }else{
            $workerStatResult = self::find()
                ->where(['id'=>$worker_id])
                ->select('id,shop_id,worker_type,worker_identity_id,worker_name,worker_phone,worker_stat_order_num,worker_stat_order_refuse,worker_stat_order_complaint,worker_stat_order_money')
                ->joinWith('workerStatRelation')
                ->asArray()
                ->one();
            if($workerStatResult){
                //门店名称,家政公司名称
                $shopInfo = Shop::findone($workerStatResult['shop_id']);
                $workerStatResult['shop_name'] = isset($shopInfo['name'])?$shopInfo['name']:'';
                $workerStatResult['worker_type_description'] = self::getWorkerTypeShow($workerStatResult['worker_type']);
                $workerStatResult['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($workerStatResult['worker_identity_id']);
                if($workerStatResult['worker_stat_order_num']!=0){
                    $workerStatResult['worker_stat_order_refuse_percent'] = Yii::$app->formatter->asPercent($workerStatResult['worker_stat_order_refuse']/$workerStatResult['worker_stat_order_num']);
                }else{
                    $workerStatResult['worker_stat_order_refuse_percent'] = '0%';
                }
                //获取阿姨服务过的用户数
                $workerStatResult['worker_stat_server_customer'] = CustomerWorker::countWorkerServerAllCustomer($worker_id);
                unset($workerStatResult['workerStatRelation']);
            }
            return $workerStatResult;
        }
    }

    /**
     * 获取阿姨银行信息
     * @param int|array $worker_id 阿姨id
     * @return array
     */
    public static function getWorkerBankInfo($worker_id){
        if(empty($worker_id)){
            return [];
        }else{
            $condition['worker_id'] = $worker_id;
            $workerBankInfo = WorkerExt::find()->where($condition)->select('worker_id,worker_bank_name,worker_bank_from,worker_bank_area,worker_bank_card')->asArray()->all();
            if($workerBankInfo) {
                return $workerBankInfo;
            }else{
                return [];
            }
        }
    }


    /**
     * 通过电话获取阿姨信息
     * @param string $phone 阿姨电话
     * @return array 阿姨详细信息(阿姨id，阿姨姓名)
     */
    public static function getWorkerInfoByPhone($phone){
        if(empty($phone)){
            return [];
        }else{
            $condition = ['worker_phone'=>$phone,'isdel'=>0,'worker_is_block'=>0,'worker_is_vacation'=>0,'worker_is_blacklist'=>0];
            $workerInfo = worker::find()->where($condition)->select('id,shop_id,worker_name,worker_phone,worker_idcard,worker_type,worker_identity_id,created_ad')->asArray()->one();
            if($workerInfo){
                //门店名称,家政公司名称
                $shopInfo = Shop::findone($workerInfo['shop_id']);
                if($shopInfo){
                    $shopManagerInfo = ShopManager::findOne($shopInfo['shop_manager_id']);
                }
                $workerInfo['shop_name'] = isset($shopInfo['name'])?$shopInfo['name']:'';
                $workerInfo['shop_manager_name'] = isset($shopManagerInfo['name'])?$shopManagerInfo['name']:'';
                //阿姨类型描述信息
                $workerInfo['worker_type_description'] = self::getWorkerTypeShow($workerInfo['worker_type']);
                //阿姨身份描述信息
                $workerInfo['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($workerInfo['worker_identity_id']);
            }else{
                $workerInfo = [];
            }
            return $workerInfo;
        }
    }



    /**
     * 获取指定时间段内阿姨的未工作时间
     * @param $worker_id
     * @param $startTime
     * @param $endTime
     * @return int
     */
    public static function getWorkerNotWorkTime($worker_id,$startTime,$endTime){
        if(empty($worker_id) || empty($startTime) || empty($endTime)){
            return false;
        }
        $condition = "(worker_vacation_start_time>=$startTime and worker_vacation_finish_time<$endTime) or (worker_vacation_start_time<=$startTime and worker_vacation_finish_time>$startTime) or (worker_vacation_start_time<$endTime and worker_vacation_finish_time>$endTime)";
        $vacationResult = WorkerVacation::find()->where($condition)->andWhere(['worker_id'=>$worker_id])->select('worker_vacation_start_time,worker_vacation_finish_time')->asArray()->all();
        $vacationTime = 0;
        foreach ((array)$vacationResult as $val) {
             if($val['worker_vacation_start_time']>=$startTime && $val['worker_vacation_finish_time']<$endTime){
                 $vacationTime = $vacationTime+intval($val['worker_vacation_start_time']-$val['worker_vacation_start_time']);
             }
        }
        $notWorkTime = $vacationTime;
        return $notWorkTime;
    }



    /**
     * 批量获取阿姨id
     * @param  int $type 阿姨类型 1自营 2非自营
     * @param  int $identity_id 阿姨身份id
     * @return array 阿姨id列表
     */
    public static function getWorkerIds($type=null,$identity_id=null){
        $condition = [];
        if($type){
            $condition['worker_type'] = $type;
        }
        if($identity_id){
            $condition['worker_identity_id'] = $identity_id;
        }
        $workerList = self::find()->select('id')->where($condition)->asArray()->all();
        return $workerList?ArrayHelper::getColumn($workerList,'id'):[];
    }




    /**
     * 通过阿姨id批量获取阿姨信息
     * @param array $workerIdsArr 阿姨id数组
     * @param string $field 返回字段
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getWorkerListByIds($workerIdsArr,$field='worker_name'){
        if(empty($workerIdsArr)){
            return [];
        }else{
            $condition['id'] = $workerIdsArr;
            $workerResult = Worker::find()->where($condition)->select($field)->asArray()->all();

            return $workerResult;
        }
    }

    /**
     * 通过阿姨id批量获取阿姨详细信息
     * @param array $workerIdsArr 阿姨id数组
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getWorkerDetailListByIds($workerIdsArr){

        if(empty($workerIdsArr)){
            return [];
        }else{
            $condition['worker_is_block'] = 0;
            $condition['worker_is_vacation'] = 0;
            $condition['worker_is_blacklist'] = 0;
            $condition['{{%worker}}.id'] = $workerIdsArr;
            $workerResult = Worker::find()
                ->select('{{%worker}}.id,shop_id,worker_name,worker_phone,worker_idcard,worker_identity_id,worker_type,name as shop_name,worker_stat_order_num,worker_stat_order_refuse')
                ->joinWith('workerStatRelation') //关联worker WorkerStatRelation方法
                ->joinWith('shopRelation') //关联worker shopRelation方法
                ->where($condition)
                ->asArray()
                ->all();
            $workerIdentityConfigArr = WorkerIdentityConfig::getWorkerIdentityList();

            if(empty($workerResult)){
                return [];
            }else{
                foreach ($workerResult as $val) {

                    $val['worker_id'] = $val['id'];
                    $val['worker_type_description'] = self::getWorkerTypeShow($val['worker_type']);
                    $val['worker_identity_description'] = $workerIdentityConfigArr[$val['worker_identity_id']];

                    $val['shop_name'] = isset($val['shop_name'])?$val['shop_name']:'';
                    $val['worker_stat_order_num'] = intval($val['worker_stat_order_num']);
                    $val['worker_stat_order_refuse'] = intval($val['worker_stat_order_refuse']);

                    if($val['worker_stat_order_num']!=0){
                        $val['worker_stat_order_refuse_percent'] = Yii::$app->formatter->asPercent($val['worker_stat_order_refuse']/$val['worker_stat_order_num']);
                    }else{
                        $val['worker_stat_order_refuse_percent'] = '0%';
                    }

                    unset($val['workerStatRelation']);
                    unset($val['shopRelation']);
                    $workerList[] = $val;
                }
                return $workerList;
            }
        }

    }




    /**
     * 根据条件搜索阿姨
     * @param $worker_name 阿姨姓名(不搜索此项传null)
     * @param $worker_phone 阿姨电话(不搜索此项传null)
     * @return array 阿姨列表
     */
    public static function searchWorker($worker_name=null,$worker_phone=null){
        $defaultCondition['worker_is_block'] = 0;
        $defaultCondition['worker_is_vacation'] = 0;
        $defaultCondition['worker_is_blacklist'] = 0;
        //$condition = array_merge($defaultCondition,$filterCondition);
        //获取所属商圈中所有阿姨
        $districtWorkerResult = Worker::find()
            ->select('{{%worker}}.id,shop_id,worker_name,worker_phone,worker_idcard,worker_identity_id,worker_type,name as shop_name,worker_stat_order_num,worker_stat_order_refuse')
            ->joinWith('workerStatRelation') //关联worker WorkerStatRelation方法
            ->joinWith('shopRelation') //关联worker shopRelation方法
            ->where($defaultCondition)
            ->andFilterWhere(['like', 'worker_name', $worker_name])
            ->andFilterWhere(['like', 'worker_phone', $worker_phone])
            ->asArray()
            ->all();
        $workerIdentityConfigArr = WorkerIdentityConfig::getWorkerIdentityList();

        if(empty($districtWorkerResult)){
            return [];
        }else{
            foreach ($districtWorkerResult as $val) {

                $val['worker_id'] = $val['id'];
                $val['worker_type_description'] = self::getWorkerTypeShow($val['worker_type']);
                $val['worker_identity_description'] = $workerIdentityConfigArr[$val['worker_identity_id']];

                $val['shop_name'] = isset($val['shop_name'])?$val['shop_name']:'';
                $val['worker_stat_order_num'] = intval($val['worker_stat_order_num']);
                $val['worker_stat_order_refuse'] = intval($val['worker_stat_order_refuse']);
                if($val['worker_stat_order_num']!=0){
                    $val['worker_stat_order_refuse_percent'] = Yii::$app->formatter->asPercent($val['worker_stat_order_refuse']/$val['worker_stat_order_num']);
                }else{
                    $val['worker_stat_order_refuse_percent'] = '0%';
                }

                unset($val['workerStatRelation']);
                unset($val['shopRelation']);
                $districtWorkerArr[] = $val;
            }
            return $districtWorkerArr;
        }
    }


    /**
     * 获取商圈所有阿姨
     * @param $district_id
     * @param $worker_id
     * @return array
     */
    public static function getDistrictAllWorker($district_id,$worker_id=''){
        $dataSource = 1;//1redis 2mysql
        //如果redis可用
        if($dataSource){
           return self::getDistrictAllWorkerFromRedis($district_id,$worker_id);
        }else{
            $workerCondition = $worker_id ? ['{{%worker}}.id'=>$worker_id] : [];
            $result = self::getDistrictAllWorkerFromMysql($district_id,$workerCondition);
            foreach ($result as $key=>$val) {
                $workerSchedule = $val['workerScheduleRelation'];
                foreach ((array)$workerSchedule as $s_key=>$s_val) {
                    $workerSchedule[$s_key]['worker_schedule_timeline'] = json_decode($s_val['worker_schedule_timeline'],1);
                }
                unset($val['workerScheduleRelation']);
                $val['worker_id'] = $val['id'];
                $new_districtWorker[] = [
                    'info'=>$val,
                    'schedule'=>$workerSchedule,
                    'order'=>self::getWorkerOrderInfo($val['id'])
                ];

            }
            return $new_districtWorker;
        }


    }

    /**
     * 获取商圈所有阿姨从redis
     * @param $district_id
     * @param $worker_id
     * @return array
     */
    protected static function getDistrictAllWorkerFromRedis($district_id,$worker_id){
        $workerIdsArr =  Yii::$app->redis->executeCommand('smembers', [self::DISTRICT.'_'.$district_id]);
        if($workerIdsArr){
            //指定阿姨
            if($worker_id){
                //指定阿姨必须在该商圈中
                if(in_array($worker_id,$workerIdsArr)){
                    $workerInfoArr = Yii::$app->redis->executeCommand('get',[self::WORKER.'_'.$worker_id]);
                    if($workerInfoArr){
                        $workerInfoArr = json_decode($workerInfoArr,1);
                        return [$workerInfoArr];
                    }else{
                        return [];
                    }
                }else{
                    return [];
                }
            //所有阿姨
            }else{
                foreach((array)$workerIdsArr as $key=>$val){
                    $workerIdsArr[$key] = self::WORKER.'_'.$val;
                }
                $workerInfoArr = Yii::$app->redis->executeCommand('mget',$workerIdsArr);

                //过滤不存在阿姨
                $new_workerInfoArr = [];
                foreach ((array)$workerInfoArr as $val) {
                    if($val!==null){
                        $new_workerInfoArr[] = json_decode($val,1);
                    }
                }
                return $new_workerInfoArr;
            }
        }else{
            return [];
        }
    }

    /**
     * 获取商圈中所有阿姨mysql
     * @param $district_id
     * @param array $filterCondition 阿姨筛选条件
     * @return array 阿姨列表
     */
     protected static function getDistrictAllWorkerFromMysql($district_id,$filterCondition=[]){
         if(empty($district_id) || !is_array($filterCondition)){
             return [];
         }
         $defaultCondition['worker_is_block'] = 0;
         $defaultCondition['worker_is_vacation'] = 0;
         $defaultCondition['worker_is_blacklist'] = 0;
         $condition = array_merge($defaultCondition,$filterCondition);
         //获取所属商圈中所有阿姨
         $districtWorkerResult = Worker::find()
             ->select('{{%worker}}.id ,shop_id,worker_name,worker_phone,worker_idcard,worker_identity_id,worker_type')
             ->innerJoinWith('workerDistrictRelation') //关联worker workerDistrictRelation方法
             ->andOnCondition(['{{%worker_district}}.operation_shop_district_id'=>$district_id])
             //->joinWith('shopRelation') //关联worker shopRelation方法
             ->innerJoinWith('workerScheduleRelation') //关联WorkerScheduleRelation方法
             //->andOnCondition([])
             //->joinWith('workerStatRelation') //关联worker WorkerStatRelation方法
             ->where($condition)
             ->asArray()
             ->all();

         return $districtWorkerResult;
     }

    protected static function getWorkerOrderInfo($worker_id){
        $orderWorkerResult = OrderExtWorker::find()
            ->select('order_id,worker_id,order_booked_count,order_booked_begin_time,order_booked_end_time')
            ->where(['worker_id'=>$worker_id])
            ->innerJoinWith('order')
            ->asArray()
            ->all();
        /*根据需求 需要给阿姨留有路程上的时间 阿姨订单开始时间-1小时 和 订单结束时间+1小时*/
        foreach ((array)$orderWorkerResult as $key=>$val) {
            $orderWorkerResult[$key]['order_booked_end_time'] = $val['order_booked_end_time']-3600;
            $orderWorkerResult[$key]['order_booked_end_time'] = $val['order_booked_end_time']+3600;
        }
        return $orderWorkerResult;
    }

    /**
     * 获取阿姨时间排班表
     * @param int $district_id 商圈id
     * @param int $serverDurationTime 服务时长
     * @param string $beginTime 排班表开始时间 默认今天
     * @param int $timeLineLength 排班表长度 默认返回7天的排班表
     * @param string $worker_id 阿姨id 通过阿姨id获取指定阿姨的排班表 默认返回所有阿姨排班表
     * @return array
     */
    public static function getWorkerTimeLine($district_id,$serverDurationTime=2,$beginTime='',$timeLineLength=7,$worker_id=''){
        $disabledTimesArr = self::getCycleTimes($timeLineLength);
        $beginTime = $beginTime ? $beginTime : time();
        $beginTime = strtotime(date('Y-m-d',$beginTime));

        //如果无商圈id,返回不可用排班表
        if(empty($district_id)){
            return  self::generateTimeLine($disabledTimesArr,$serverDurationTime,$beginTime,$timeLineLength);
        }


        $districtWorkerResult = self::getDistrictAllWorker($district_id,$worker_id);
        //如果商圈中无阿姨信息,返回不可用排班表
        if(empty($districtWorkerResult)){
            return  self::generateTimeLine($disabledTimesArr,$serverDurationTime,$beginTime,$timeLineLength);
        }
//        echo '<pre>';
//        print_r($districtWorkerResult);die;
        //处理数据 生成排班表
        $isEmptyDisabledTime = [];
        foreach($districtWorkerResult as $val){

            for($i=0;$i<$timeLineLength;$i++){
                if(empty($disabledTimesArr[$i])){
                    $isEmptyDisabledTime[$i]=true;
                    continue;
                }
                $time = strtotime("+$i day",$beginTime);
                $scheduleInfo = isset($val['schedule'])?$val['schedule']:[];
                $orderInfo = isset($val['order'])?$val['order']:[];
                $disabledTimesArr[$i] = self::filterDisabledTimeLine($time,$scheduleInfo,$orderInfo,$disabledTimesArr[$i]);
            }
            if(count($isEmptyDisabledTime)==$timeLineLength){
                break;
            }
        }

        $workerTimeLine = self::generateTimeLine($disabledTimesArr,$serverDurationTime,$beginTime,$timeLineLength);
        return $workerTimeLine;
    }

    /**
     * 获取阿姨周期排班表
     * @param int $district_id 商圈id
     * @param int $serverDurationTime 服务时长
     * @param int $worker_id 阿姨id
     * @return array
     */
    public static function getWorkerCycleTimeLine($district_id,$serverDurationTime=2,$worker_id){
        if(empty($worker_id) || empty($district_id)){
            return false;
        }
        //周期订单默认已当前时间为开始时间
        $beginTime = strtotime(date('Y-m-d'));
        //周期订单默认处理35天阿姨排班表
        $timeLineLength = 35;
        $workerTimeLineResult = self::getWorkerTimeLine($district_id,$serverDurationTime,$beginTime,$timeLineLength,$worker_id);
        //获取当前周排版表
        $current = [];
        for($i=0;$i<7;$i++) {
            $current[] = [
                'weekday' => date('N', strtotime("+$i day", $beginTime)),
                'date' => date('Y-m-d', strtotime("+$i day", $beginTime)),
                'timeline' => $workerTimeLineResult[$i]['timeline']
            ];
        }

        //获取后四周的周期时间表
        $cycle = [];
        for($w=1;$w<=4;$w++){
            for($d=0;$d<7;$d++){
                $key = $w*7+$d;
                $data = $workerTimeLineResult[$key];
                $week = date('N',strtotime($data['date']));
                if(isset($cycle[$d])){
                    $timeline = self::compareTimeLine($cycle[$d]['timeline'],$data['timeline']);
                    $cycle[$d] = [
                        'week' => $week,
                        'date' => date('Y-m-d',strtotime('+'.(7+$d).'day')), //根据需求,周期date字段显示周期表中第一周的日期
                        'timeline'=> $timeline
                    ];
                }else{
                    $cycle[$d] = [
                        'week' => $week,
                        'date' => date('Y-m-d',strtotime('+'.(7+$d).'day')), //根据需求,周期date字段显示周期表中第一周的日期
                        'timeline'=> $data['timeline']
                    ];
                }

            }
        }

//        foreach ($workerTimeLineResult as $key=>$val) {
//            if($key<7){
//                continue;
//            }
//            $week = date('N',strtotime($val['date']));
//            if(isset($cycle[$week])){
//                $timeline = self::operateTimeLine($cycle[$week]['timeline'],$val['timeline']);
//                $cycle[$week] = [
//                    'week' => date('N',strtotime($val['date'])),
//                    'timeline'=> $timeline
//                ];
//            }else{
//                $cycle[$week] = [
//                    'week' => date('N',strtotime($val['date'])),
//                    'timeline'=>$val['timeline']
//                ];
//            }
//
//        }
        $workerCycleTimeLine = [
            'current'=>$current,
            'cycle'=>$cycle
        ];
        return $workerCycleTimeLine;

    }

    /**
     * 对比时间是否可用
     * @param $timeline1
     * @param $timeline2
     * @return mixed
     */
    protected static function compareTimeLine($timeline1,$timeline2){
        foreach($timeline1 as $key=>$val){
            if($timeline1[$key]['enable']==false){
                continue;
            }else{
                if($timeline2[$key]['enable']==false){
                    $timeline1[$key]['enable'] = false;
                }
            }
        }
        return $timeline1;
    }

    /**
     * 生成排版表
     * @param array $disabledTimeLine 不可用的时间点
     * @param int $serverDurationTime 预约时长
     * @param string $beginTime 排班表开始时间 默认当前时间
     * @param int $timeLineLength 排班表长度 默认返回7天的排班表
     * @return mixed
     */
    protected static function generateTimeLine($disabledTimeLine,$serverDurationTime,$beginTime,$timeLineLength){
        $dayTimes = self::getDayTimes();
        for($i=0;$i<$timeLineLength;$i++) {
            $time = strtotime("+$i day", $beginTime);
            $date = date('Y-m-d', $time);
            $disabledTime = $disabledTimeLine[$i];
            foreach ($dayTimes as $key => $val) {
                $endKey = $key + $serverDurationTime * 2;
                if ($endKey > count($dayTimes) - 1) {
                    break;
                }
                $isDisabled = 0;
                //获取当前时间段中是否含有不可用的时间
                foreach ($disabledTime as $d_val) {
                    $disabledKey = array_search($d_val, $dayTimes);
                    if ($key <= $disabledKey && $endKey > $disabledKey) {
                        $isDisabled = 1;
                        break;
                    }
                }
                if ($isDisabled == 1) {
                    $timeLineTmp[] = ['time'=>$val . '-' . $dayTimes[$endKey],'enable'=>false];
                } else {
                    $timeLineTmp[] = ['time'=>$val . '-' . $dayTimes[$endKey],'enable'=> true];
                }
            }
            $timeLine[] = ['date'=>$date,'timeline'=>$timeLineTmp];
            $timeLineTmp = [];
        }
        return $timeLine;

        //var_dump($timeLine);die;
    }

    /**
     * 过滤不可接单时间
     * @param int $time 日期时间戳
     * @param array $workerSchedule 阿姨后台排班表
     * @param array $workerOrderInfo 阿姨预约订单信息
     * @param array $disabledTime 不可用时间数组
     * @return array 过滤后的不可用时间 ['8:00','8:30']
     */
    protected static function filterDisabledTimeLine($time,$workerSchedule,$workerOrderInfo,$disabledTime){
        $workerScheduleTime = self::getWorkerEnabledTimeFromSchedule($time,$workerSchedule);
        if(empty($workerScheduleTime)){
            return $disabledTime;
        }
        $workerHaveBookedTime = self::getWorkerHaveBookedTimeFromOrder($time,$workerOrderInfo);
        //整理阿姨可用时间
        $workerEnableTime = array_diff($workerScheduleTime,$workerHaveBookedTime);
        //var_dump($workerEnableScheduleTime);
        //通过阿姨可用时间 过滤 单日不可用时间
        $disabledTime = array_diff($disabledTime,$workerEnableTime);
        //var_dump($disabledTime);
        return $disabledTime;
    }

    /**
     * 根据后台排班表获取阿姨单日所有可接活时间
     * @param $time 时间
     * @param $workerSchedule 阿姨排班表
     * @return array
     */
    protected static function getWorkerEnabledTimeFromSchedule($time,$workerSchedule){
        $new_enabledTime = [];
        $week  = date('N',$time);

        foreach ((array)$workerSchedule as $val) {
            if($time>=$val['worker_schedule_start_date'] && $time<=$val['worker_schedule_end_date']){
                $enabledTimeLineArr = $val['worker_schedule_timeline'];
                $enabledTimeLine = $enabledTimeLineArr[$week];
                foreach ($enabledTimeLine as $e_val) {
                    $new_enabledTime[] = $e_val;
                    $new_enabledTime[] = str_replace(':00',':30',$e_val);
                }

            }
        }
        return $new_enabledTime;
    }
    
    /**
     * 根据订单获取阿姨单已预约的时间
     */
    protected static function getWorkerHaveBookedTimeFromOrder($time,$workerOrderInfo){
        $workerHaveBookedTime = [];
        foreach ((array)$workerOrderInfo as $val) {
            if(date('Y-m-d',$time) == date('Y-m-d',$val['order_booked_begin_time'])){
                $beginTime = self::convertDateFormat($val['order_booked_begin_time']);
                //每个订单持续时间+2小时 阿姨连续订单之间留有空余的时间,避免没有空余时间赶到另外一个服务地点
                $orderDurationTime = $val['order_booked_count']+2;
                for($i=0;$i<$orderDurationTime*2;$i++){
                    $workerHaveBookedTime[] = date('G:i',strtotime('+'.(30*$i).' minute',$beginTime));
                }
            }
        }
        return $workerHaveBookedTime;
    }

    /**
     * 转换不规范时间格式 转换时间成整点时间或半点时间
     * @param $time
     * @return mixed
     */
    protected static function convertDateFormat($time){
        $date = date('G:i',$time);
        $dateArr = explode(':',$date);
        if($dateArr[1]=='00' || $dateArr[1]=='30'){
            return $time;
        }else{
            if($dateArr[1]<30){
                return $time+(30-$dateArr[1])*60;
            }else{
                return $time+(60-$dateArr[1])*60;
            }
        }
    }

    /**
     * 获取指定周期的所有时间点
     * @param int $cycle
     * @return array
     */
    protected static function getCycleTimes($cycle=7){
        $initTimes = [];
        for($w=0;$w<$cycle;$w++){
            $initTimes[$w] = self::getDayTimes();
        }
        return $initTimes;
    }

    /**
     * 获取单日所有时间点
     * @return array
     */
    protected static function getDayTimes(){

        $timeLine= [
            '8:00','8:30','9:00','9:30',
            '10:00','10:30','11:00','11:30','12:00',
            '12:30','13:00','13:30','14:00','14:30',
            '15:00','15:30','16:00','16:30','17:00',
            '17:30','18:00','18:30','19:00','19:30',
            '20:00','20:30','21:00','21:30','22:00'
        ];

        return $timeLine;
    }

    /**
     * 操作Redis中阿姨的订单信息
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
        $workerInfo =  Yii::$app->redis->executeCommand('get', [self::WORKER.'_'.$worker_id]);
        if(empty($workerInfo)){
            Yii::$app->redis->close();
            return false;
        }else{
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
                Yii::$app->redis->executeCommand('set', [self::WORKER.'_'.$worker_id,$workerInfo]);
                Yii::$app->redis->close();
                return true;
            //修改阿姨订单信息
            }elseif($type==2){
                $workerInfo = json_decode($workerInfo,1);
                foreach ($workerInfo['order'] as $key=>$val) {
                    if($val['order_id']==$order_id){
                        $workerInfo['order'][$key] = $orderInfo;
                    }
                }
                $workerInfo = json_encode($workerInfo);
                Yii::$app->redis->executeCommand('set', [self::WORKER.'_'.$worker_id,$workerInfo]);
                Yii::$app->redis->close();
                return true;
            }else{
                Yii::$app->redis->close();
                return false;
            }
        }
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

        $workerInfo =  Yii::$app->redis->executeCommand('get', [self::WORKER.'_'.$worker_id]);
        if(empty($workerInfo)){
            Yii::$app->redis->close();
            return false;
        }else{
            $workerInfo = json_decode($workerInfo,1);
            foreach ($workerInfo['order'] as $key=>$val) {
                if($val['order_id']==$order_id){
                    unset($workerInfo['order'][$key]);
                }
            }
            $workerInfo = json_encode($workerInfo);
            Yii::$app->redis->executeCommand('set', [self::WORKER.'_'.$worker_id,$workerInfo]);
            Yii::$app->redis->close();
            return true;
        }
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

        //添加新的商圈绑定阿姨关系 [1,3,4]
        foreach ((array)$districtIdsArr as $val) {
            //如果商圈不存在，默认添加商圈set，并存储阿姨id
            Yii::$app->redis->executeCommand('sadd', [self::DISTRICT.'_'.$val,$worker_id]);
        }
        Yii::$app->redis->close();
        return true;
    }

    public static function deleteDistrictWorkerRelationToRedis($worker_id){
        if(empty($worker_id) || empty($districtIdsArr)){
            return false;
        }
        //删除老的商圈绑定阿姨关系[['operation_shop_district_id'=>1]]
        $oldDistrictIdsArr = Worker::getWorkerDistrict($worker_id);
        foreach ((array)$oldDistrictIdsArr as $val) {
            Yii::$app->redis->executeCommand('srem', [self::DISTRICT.'_'.$val['operation_shop_district_id'],$worker_id]);
        }
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

        $workerInfo = Yii::$app->redis->executeCommand('get', [self::WORKER.'_'.$worker_id]);
        //如果有阿姨信息则更新阿姨的排班表信息 (不存在则代表阿姨当前状态为离职封号休假状态)
        if($workerInfo){
            $workerInfo = json_decode($workerInfo,1);
            $workerInfo['schedule'] = $scheduleInfo;
            $workerInfo = json_encode($workerInfo);
            Yii::$app->redis->executeCommand('set', [self::WORKER.'_'.$worker_id,$workerInfo]);
        }

        Yii::$app->redis->close();
        return true;
    }

    /**
     * 添加阿姨信息到redis
     * @param $worker_id
     * @param $worker_phone
     * @param $worker_type
     * @param $worker_identity_id 阿姨身份id 1全时2兼职
     * @return bool
     */
    public static function addWorkerInfoToRedis($worker_id,$worker_phone,$worker_type,$worker_identity_id){
        if(empty($worker_id) || empty($worker_phone) || empty($worker_type)){
            return false;
        }
        $workerInfo['info'] = [
            'worker_id'=>$worker_id,
            'worker_phone'=>$worker_phone,
            'worker_identity_id'=>$worker_type,
            'worker_type'=>$worker_identity_id
        ];
        $workerInfo['schedule'] = [];
        $workerInfo['order'] = [];
        $workerInfo = json_encode($workerInfo);
        Yii::$app->redis->executeCommand('set', [self::WORKER.'_'.$worker_id,$workerInfo]);

    }

    /**
     * 更新阿姨信息到redis
     * @param $worker_id
     * @param $worker_phone
     * @param $worker_type
     * @param $worker_identity_id 阿姨身份id 1全时2兼职
     * @return bool
     */
    public static function updateWorkerInfoToRedis($worker_id,$worker_phone,$worker_type,$worker_identity_id){
        if(empty($worker_id) || empty($worker_phone) || empty($worker_type)){
            return false;
        }
        $workerInfo = Yii::$app->redis->executeCommand('get',[self::WORKER.'_'.$worker_id]);
        if($workerInfo){
            $workerInfo = json_decode($workerInfo,1);
            $workerInfo['info'] = [
                'worker_id'=>$worker_id,
                'worker_phone'=>$worker_phone,
                'worker_type'=>$worker_type,
                'worker_identity_id'=>$worker_identity_id
            ];
            $workerInfo = json_encode($workerInfo);
            Yii::$app->redis->executeCommand('set', [self::WORKER.'_'.$worker_id,$workerInfo]);
        }
    }
    /**
     * 获取商圈中 所有可用阿姨
     * @param int $district_id 商圈id
     * @param int $worker_identity_id 阿姨身份 1全时2兼职
     * @param int $orderBookBeginTime 待指派订单预约开始时间
     * @param int $orderBookEndTime 待指派订单预约结束时间
     * @return array freeWorkerArr 所有可用阿姨列表
     */
    public static function getDistrictFreeWorker($district_id,$worker_identity_id=1,$orderBookBeginTime,$orderBookEndTime){

        $districtWorkerResult = self::getDistrictAllWorker($district_id);

        $orderBookTime = self::generateTimeUnit($orderBookBeginTime,$orderBookEndTime);
        //开始时间大于等于结束时间
        if($orderBookBeginTime>=$orderBookEndTime){
            return [];
        }
        $districtFreeWorkerIdsArr = [];
        foreach ($districtWorkerResult as $val) {

            $schedule = isset($val['schedule'])?$val['schedule']:[];
            $orderInfo = isset($val['order'])?$val['order']:[];

            if($val['info']['worker_identity_id']==$worker_identity_id){
                $workerEnabledTime = self::getWorkerEnabledTimeFromSchedule($orderBookBeginTime,$schedule);
                if(array_diff($orderBookTime,$workerEnabledTime)){
                    continue;
                }
                $workerHaveBookedTime = self::getWorkerHaveBookedTimeFromOrder($orderBookBeginTime,$orderInfo);
                if(array_intersect($orderBookTime,$workerHaveBookedTime)){
                    continue;
                }
                $districtFreeWorkerIdsArr[] = $val['info']['worker_id'];
            }
        }
        $districtFreeWorker = self::getWorkerDetailListByIds($districtFreeWorkerIdsArr);
        return $districtFreeWorker;
    }

    /**
     * 获取开始结束时间间隔内 包含的 最小时间单位
     * @param $beginTime 开始时间戳
     * @param $endTime 结束时间戳
     * @return array [8:00,8:30,9:00]
     */
    protected static function generateTimeUnit($beginTime,$endTime){
        $durationTime = ($endTime-$beginTime)/3600;
        $TimeArr = [];
        for($i=0;$i<$durationTime*2;$i++){
            $TimeArr[] = date('G:i',strtotime('+'.(30*$i).' minute',$beginTime));
        }
        return $TimeArr;
    }

    /**
     * 获取商圈中 周期订单 可用阿姨
     * @param int $district_id 商圈id
     * @param int $worker_identity_id 阿姨身份 1全时2兼职
     * @param array $orderBookTimeArr 待指派订单预约时间['orderBookBeginTime'=>'1490000000','orderBookEndTime'=>'1493200000']
     * @return array freeWorkerArr 所有可用阿姨列表
     * @throws ErrorException
     */

    public static function getDistrictCycleFreeWorker($district_id,$worker_identity_id=1,$orderBookTimeArr){

        $districtWorkerResult = self::getDistrictAllWorker($district_id);

        $districtFreeWorkerIdsArr = [];

        foreach ($districtWorkerResult as $val) {
            $schedule = isset($val['schedule'])?$val['schedule']:[];
            $orderInfo = isset($val['order'])?$val['order']:[];
            if($val['info']['worker_identity_id']==$worker_identity_id){
                $workerIsDisabled = 0;
                foreach ($orderBookTimeArr as $t_val) {
                    if($t_val['orderBookBeginTime']>=$t_val['orderBookEndTime']){
                        return [];
                    }
                    $orderBookTime = self::generateTimeUnit($t_val['orderBookBeginTime'],$t_val['orderBookEndTime']);

                    //根据排班表获取所有可用的时间 ['8:00','8:30','9:00','9:30','10:00','10:30'，...]
                    $workerEnabledTime = self::getWorkerEnabledTimeFromSchedule($t_val['orderBookBeginTime'],$schedule);
                    if(array_diff($orderBookTime,$workerEnabledTime)){
                        $workerIsDisabled = 1;
                        break;
                    }
                    //根据订单获取不可用时间 ['8:00','8:30','9:00','9:30']
                    $workerHaveBookedTime = self::getWorkerHaveBookedTimeFromOrder($t_val['orderBookBeginTime'],$orderInfo);
                    if(array_intersect($orderBookTime,$workerHaveBookedTime)){
                        $workerIsDisabled = 1;
                        break;
                    }
                }
                if($workerIsDisabled==0){
                    $districtFreeWorkerIdsArr[] = $val['info']['worker_id'];
                }
            }
        }
        $districtFreeWorker = self::getWorkerDetailListByIds($districtFreeWorkerIdsArr);
        return $districtFreeWorker;
    }


    /**
     * 检查阿姨指定时间段 是否可用
     * @param $district_id
     * @param $worker_id
     * @param $orderBookBeginTime 订单预约开始时间
     * @param $orderBookEndTime 订单
     * @return bool true可用|false不可用
     */
    public static function checkWorkerTimeIsDisabled($district_id,$worker_id,$orderBookBeginTime,$orderBookEndTime){
        $districtWorkerResult = self::getDistrictAllWorker($district_id,$worker_id);

        $serverDurationTime = ($orderBookEndTime-$orderBookBeginTime)/3600;

        for($i=0;$i<$serverDurationTime*2;$i++){
            $orderBookTime[] = date('G:i',strtotime('+'.(30*$i).' minute',$orderBookBeginTime));
        }
        if($districtWorkerResult){
            $districtWorker = $districtWorkerResult[0];
            $schedule = isset($districtWorker['schedule'])?$districtWorker['schedule']:[];
            $orderInfo = isset($districtWorker['order'])?$districtWorker['order']:[];
            $workerEnabledTime = self::getWorkerEnabledTimeFromSchedule($orderBookBeginTime,$schedule);
            if(array_diff($orderBookTime,$workerEnabledTime)){
                return false;
            }
            $workerHaveBookedTime = self::getWorkerHaveBookedTimeFromOrder($orderBookBeginTime,$orderInfo);
            if(array_intersect($orderBookTime,$workerHaveBookedTime)){
                return false;
            }
            return true;
        }else{
            return false;
        }

    }


    /**
     * 获取商圈中 所有可用阿姨
     * @param int $district_id 商圈id
     * @param int $worker_type 阿姨类型 1自营2非自营
     * @param int $orderBookBeginTime 待指派订单预约开始时间
     * @param int $orderBookEndTime 待指派订单预约结束时间
     * @return array freeWorkerArr 所有可用阿姨列表
     */
    public static function getDistrictFreeWorkerBak($district_id=1,$worker_type=1,$orderBookBeginTime,$orderBookEndTime){

        $workerIdentityConfigArr = WorkerIdentityConfig::getWorkerIdentityList();

        //获取商圈中所有阿姨
        $condition['worker_type'] = $worker_type;
        $districtWorkerResult = self::getDistrictAllWorkerFromMysql($district_id,$condition);

        if(empty($districtWorkerResult)){
            return [];
        }else{
            foreach ($districtWorkerResult as $val) {

                $districtWorkerIdsArr[] = $val['id'];

                $val['worker_id'] = $val['id'];
                $val['worker_type_description'] = self::getWorkerTypeShow($val['worker_type']);
                $val['worker_identity_description'] = $workerIdentityConfigArr[$val['worker_identity_id']];

                $val['shop_name'] = isset($val['shop_name'])?$val['shop_name']:'';
                $val['worker_stat_order_num'] = intval($val['worker_stat_order_num']);
                $val['worker_stat_order_refuse'] = intval($val['worker_stat_order_refuse']);
                if($val['worker_stat_order_num']!==0){
                    $val['worker_stat_order_refuse_percent'] = Yii::$app->formatter->asPercent($val['worker_stat_order_refuse']/$val['worker_stat_order_num']);
                }else{
                    $val['worker_stat_order_refuse_percent'] = '0%';
                }

                unset($val['workerStatRelation']);
                unset($val['workerDistrictRelation']);
                unset($val['shopRelation']);
                $districtWorkerArr[$val['id']] = $val;
            }
        }

        //获取在指派时间段内已被预约的阿姨
        //$condition = "(order_booked_begin_time>$orderBookEndTime or order_booked_end_time<$orderBookBeginTime)";
        $condition = "	(ejj_order.order_booked_begin_time<=$orderBookBeginTime AND ejj_order.order_booked_end_time>=$orderBookBeginTime )";
        $condition.= " OR ( ejj_order.order_booked_begin_time<=$orderBookEndTime AND ejj_order.order_booked_end_time>=$orderBookEndTime )";
        $condition.= " OR ( ejj_order.order_booked_begin_time>=$orderBookBeginTime AND ejj_order.order_booked_end_time<=$orderBookEndTime )";
        $orderWorkerResult = OrderExtWorker::find()
            ->innerJoinWith('order')
            ->onCondition($condition)
            ->asArray()
            ->all();
        if($orderWorkerResult){
            foreach ($orderWorkerResult as $val) {
                $busyWorkerIdsArr[] = $val['worker_id'];
            }
        }else{
            $busyWorkerIdsArr = [];
        }

        //排除被预约的阿姨
        $freeWorkerIdsArr = array_diff($districtWorkerIdsArr,$busyWorkerIdsArr);

        if(empty($freeWorkerIdsArr)){
            return [];
        }else{
            foreach ($freeWorkerIdsArr as $id) {
                $freeWorkerArr[] = $districtWorkerArr[$id];
            }
            return $freeWorkerArr;
        }

    }





    /**
     * 上传图片到七牛服务器
     * @param string $field 上传文件字段名
     * @return string $imgUrl 文件URL
     */
    public function uploadImgToQiniu($field){
        $qiniu = new Qiniu();
        $fileinfo = UploadedFile::getInstance($this, $field);
        if(!empty($fileinfo)){
            $key = time().mt_rand('1000', '9999').uniqid();
            $qiniu->uploadFile($fileinfo->tempName, $key);
            $imgUrl = $qiniu->getLink($key);
            $this->$field = $imgUrl;
        }
    }

    /*
     * 获取已开通城市列表
     * @return array [city_id=>city_name,...]
     */
    public static function getOnlineCityList(){
        $onlineCityList = OperationCity::getCityOnlineInfoList();
        return $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
    }

    /*
     * 获取已开通城市名称
     * @param int $city_d 城市id
     * @return sting $cityName 城市名称
     */
    public static function getOnlineCityName($city_id=0){
        if(empty($city_id)) return '';
        $onlineCity= OperationCity::find()->select('city_name')->where(['city_id'=>$city_id])->asArray()->one();
        return $onlineCity['city_name'];
    }

    /*
     * 获取门店名称
     * @param int $shop_id 店铺id
     * @return string $shopName 店铺名称
     */
    public static function getShopName($shop_id=0){
        if(empty($shop_id)) return '';
        $shop = Shop::findOne($shop_id);
        return $shop['name'];
    }

    /*
     * 获取已上线商圈列表
     * @return array [id=>operation_shop_district_name,...]
     */
    public static function getDistrictList(){
        $districtList = OperationShopDistrict::getCityShopDistrictList();
        return $districtList?ArrayHelper::map($districtList,'id','operation_shop_district_name'):[];
    }

    /*
     * 获取阿姨所属商圈
     */
    public static function getWorkerDistrict($worker_id){
        $workerDistrictResult = WorkerDistrict::find()->where(['worker_id'=>$worker_id])->select('operation_shop_district_id')->innerJoinWith('district')->asArray()->all();

        if($workerDistrictResult){

            foreach($workerDistrictResult as $key=>$val){
                $workerDistrictTmp['operation_shop_district_id'] = $val['operation_shop_district_id'];
                $workerDistrictTmp['operation_shop_district_name'] = $val['district'][0]['operation_shop_district_name'];
                $workerDistrictArr[] = $workerDistrictTmp;
            }

            return $workerDistrictArr;
        }else{
            return [];
        }
    }
    /**
     * 获取阿姨所属商圈名称
     */
    public static function getWorkerDistrictShow($worker_id){
        $workerDistrictArr = self::getWorkerDistrict($worker_id);
        if($workerDistrictArr){
            $workerDistrictNameArr = ArrayHelper::getColumn($workerDistrictArr,'operation_shop_district_name');
            return implode(' ',$workerDistrictNameArr);
        }else{
            return '';
        }
    }

    /**
     * 获取阿姨地址
     * @param $province_id
     * @param $city_id
     * @param $area_id
     * @param $street
     * @return string
     */
    public static function getWorkerPlaceShow($province_id,$city_id,$area_id,$street=''){
        $provinceName = self::getArea($province_id);
        $cityName = self::getArea($city_id);
        $areaName = self::getArea($area_id);
        $workerPlace = $provinceName.$cityName.$areaName.$street;
        return $workerPlace;
    }

    /**
     * 获取区域名称
     * @param $id
     * @return string
     */
    public static function getArea($id){
        if(empty($id)){
            return '';
        }
        $areaResult = OperationArea::getOneFromId($id);
        return isset($areaResult['area_name'])?$areaResult['area_name']:'';
    }

    /**
     * 获取地区列表
     * @param $parent_id 父级列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAreaListByParentId($parent_id){
        $condition = ['parent_id' => $parent_id];
        $areaList = OperationArea::getAllData($condition);
        return $areaList?ArrayHelper::map($areaList,'id','area_name'):[];
    }

    public static function getAreaListByLevel($level){
        $condition = ['level' => $level];
        $areaList = OperationArea::getAllData($condition);
        return $areaList?ArrayHelper::map($areaList,'id','area_name'):[];
    }


    /**
     * 获取阿姨性别名称
     */
    public static function getWorkerSexShow($worker_sex){
        if($worker_sex==0){
            return '女';
        }else{
            return '男';
        }
    }

    /**
     * 阿姨是否有健康证
     */
    public static function getWorkerIsHealthShow($worker_is_health){
        if($worker_is_health==1){
            return '有';
        }else{
            return '无';
        }
    }

    /**
     * 阿姨是否上保险
     */
    public static function getWorkerIsInsuranceShow($worker_is_insurance){
        if($worker_is_insurance==1){
            return '有';
        }else{
            return '无';
        }
    }


    /**
     * 获取阿姨类型列表
     * @return array
     */
    public static function getWorkerTypeList(){
        return [
            1=>'自有',
            2=>'非自有'
        ];
    }

    /**
     * 获取阿姨类型名称
     */
    public static function getWorkerTypeShow($worker_type){
        if($worker_type==1){
            return '自有';
        }else{
            return '非自有';
        }
    }

    /*
     * 获取是否黑名单
     */
    public static function getWorkerIsBlockShow($worker_is_block){
        if($worker_is_block==1){
            return '是';
        }else{
            return '否';
        }
    }

    /**
     * 获取是否请假
     * @param $worker_is_vacation
     * @return string
     */
    public static function getWorkerIsVacationShow($worker_is_vacation){
        if($worker_is_vacation==1){
            return '是';
        }else{
            return '否';
        }
    }

    /*
     * 获取是否封号
     */
    public static function getWorkerIsBlacListkShow($worker_is_blacklist){
        if($worker_is_blacklist==1){
            return '是';
        }else{
            return '否';
        }
    }

    /**
     * 获取是否离职
     * @param $worker_is_dimission
     * @return string
     */
    public static function getWorkerIsDimissionShow($worker_is_dimission){
        if($worker_is_dimission==1){
            return '是';
        }else{
            return '否';
        }
    }

    /*
     * 获取审核状态
     */
    public static function getWorkerAuthStatusShow($worker_auth_status){
        switch($worker_auth_status){
            case 0:
                return '新录入';
            case 1:
                return '已审核';
            case 2:
                return '通过基础培训';
            case 3:
                return '已试工';
            case 4:
                return '已上岗';
            case 5:
                return '通过晋升培训';
        }
       /* if($worker_auth_status==1){
            return '通过';
        }else{
            return '未通过';
        }*/
    }

    /*
     * 获取试工状态
     */
    public static function getWorkerOntrialStatusShow($worker_ontrial_status){
        if($worker_ontrial_status==1){
            return '通过';
        }else{
            return '未通过';
        }
    }

    /*
     * 获取上岗状态
     */
    public static function getWorkerOnboardStatusShow($worker_onboard_statuss){
        if($worker_onboard_statuss==1){
            return '通过';
        }else{
            return '未通过';
        }
    }

    /**
     * 加入黑名单
     * @param string $cause 原因
     * @return bool
     */
    public function joinBlacklist($cause='')
    {
        $this->worker_is_blacklist = 1;
        if($this->save()){
            return true;
        }
        return false;
    }

    /**
     * 移出黑名单
     * @param string $cause 原因
     * @return bool
     * @throws BadRequestHttpException
     */

    public function removeBlacklist($cause='')
    {
        $sm = Shop::find()->where(['id'=>$this->shop_id])->one();
        if($sm->worker_is_blacklist==1){
            throw new BadRequestHttpException('所在的门店还在黑名单中');
        }
        $this->is_blacklist = 0;
        if($this->save()){
            return true;
        }
        return false;
    }



    /**
     * 阿姨附属表连表方法
     */
    public function getWorkerExtRelation(){
        return $this->hasOne(WorkerExt::className(),['worker_id'=>'id']);
    }

    /**
     * 阿姨商圈表连表方法
     */
    public function getWorkerDistrictRelation(){
        return $this->hasOne(WorkerDistrict::className(),['worker_id'=>'id'])->select('worker_id,operation_shop_district_id');
    }

    /**
     * 阿姨统计表连表方法
     */
    public function getWorkerStatRelation(){
        return $this->hasOne(WorkerStat::className(),['worker_id'=>'id']);
    }

    /**
     * 商铺表连表方法
     */
    public function getShopRelation(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }

    /**
     * 阿姨排班表连表方法
     */
    public function getWorkerScheduleRelation(){
        return $this->hasMany(WorkerSchedule::className(),['worker_id'=>'id']);
    }

    /**
     * 阿姨技能关联表连表方法
     */
    public function getWorkerSkillRelation(){
        return $this->hasOne(WorkerSkill::className(),['worker_id'=>'id']);
    }

    /**
     * 设置worker_district属性
     */
    public function getworker_district(){
        $workerDistrictArr = self::getWorkerDistrict($this->id);
        return $workerDistrictArr?ArrayHelper::getColumn($workerDistrictArr,'operation_shop_district_id'):[];
    }

    public function getWorkerVacationApplicationRelation(){
        return $this->hasMany(WorkerVacationApplication::className(),['worker_id'=>'id']);
    }
    
     /**
     * 通过阿姨手机号验证阿姨是否可以登录（无任何例外状况、封号：可以登录；离职、加入黑名单、输入后台没有的手机号：不可以）
     *@param  number $phone 用户手机号
     *@return array  $login_info 登录信息
     * 
     */
    public static function checkWorkerLogin($phone){
        $login_info=array();
        $workerInfo = self::find()->where(['worker_phone'=>$phone])->one();
        if(empty($workerInfo)){
            //不存在该阿姨
            $login_info['can_login']=0;
            $login_info['login_type']=1;
        }else{
            if($workerInfo['worker_is_blacklist']=='1'){
                //阿姨已在黑名单
                $login_info['can_login']=0;
                $login_info['login_type']=2;
            }elseif($workerInfo['worker_is_dimission']=='1'){
                //阿姨已离职
                $login_info['can_login']=0;
                $login_info['login_type']=3;
            }elseif($workerInfo['isdel']=='1'){
                //阿姨已删除
                $login_info['can_login']=0;
                $login_info['login_type']=4;
            }elseif($workerInfo['worker_auth_status']=='0'){
                //阿姨未审核或审核未通过
                $login_info['can_login']=0;
                $login_info['login_type']=6;
            }else{
                //阿姨可以登录
                $login_info['can_login']=1;
                $login_info['login_type']=5;
            }
        }
        return $login_info;
    }

}
