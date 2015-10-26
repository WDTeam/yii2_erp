<?php

namespace core\models\worker;


use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

use common\models\order\OrderExtWorker;
use core\models\shop\ShopManager;
use core\models\shop\Shop;
use core\models\worker\WorkerStat;
use core\models\worker\WorkerExt;
use core\models\worker\WorkerIdentityConfig;
use core\models\worker\WorkerRuleConfig;
use core\models\worker\WorkerSkill;
use core\models\worker\WorkerSkillConfig;
use core\models\worker\WorkerSchedule;
use core\models\operation\CoreOperationShopDistrict;
use core\models\operation\CoreOperationCity;
use core\models\operation\CoreOperationArea;
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
 * @property integer $worker_ontrial_status
 * @property integer $worker_onboard_status
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
class Worker extends \common\models\worker\Worker
{

    /**
     * 获取阿姨基本信息
     * @param integer $worker_id  阿姨id
     * @return array 阿姨信息
     */
    public static function getWorkerInfo($worker_id){

        $workerInfo = self::find()->where((['id'=>$worker_id]))->select('id,shop_id,worker_name,worker_phone,worker_idcard,worker_type,worker_photo,worker_identity_id,worker_star,created_ad')->asArray()->one();
        if($workerInfo){
            //门店名称,家政公司名称
            $shopInfo = Shop::findone($workerInfo['shop_id']);
            if($shopInfo){
                $shopManagerInfo = ShopManager::findOne($shopInfo['shop_manager_id']);
            }
            $workerInfo['shop_name'] = isset($shopInfo['name'])?$shopInfo['name']:'';
            $workerInfo['shop_manager_id'] = isset($shopInfo['shop_manager_id'])?$shopInfo['shop_manager_id']:'';
            $workerInfo['shop_manager_name'] = isset($shopManagerInfo['name'])?$shopManagerInfo['name']:'';
            $workerInfo['worker_type_description'] = self::getWorkerTypeShow($workerInfo['worker_type']);
            $workerInfo['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($workerInfo['worker_identity_id']);
        }else{
            $workerInfo = [];
        }
        return $workerInfo;
    }



    /**
     * 获取阿姨详细信息
     * @param $worker_id 阿姨id
     * @return array 阿姨详细信息
     */
    public static function getWorkerDetailInfo($worker_id){
        $workerDetailResult = self::find()
            ->where(['id'=>$worker_id])
            ->select('id,shop_id,worker_name,worker_phone,worker_photo,worker_age,worker_type,worker_identity_id,worker_star,worker_sex,worker_edu,worker_stat_order_num,worker_stat_order_refuse,worker_stat_order_complaint,worker_stat_order_money,worker_live_province,worker_live_city,worker_live_area,worker_live_street')
            ->joinWith('workerExtRelation')
            ->joinWith('workerStatRelation')
            ->asArray()
            ->one();

        if($workerDetailResult){
            $workerDetailResult['worker_sex'] = Worker::getWorkerSexShow($workerDetailResult['worker_sex']);
            $workerDetailResult['worker_type_description'] = self::getWorkerTypeShow($workerDetailResult['worker_type']);
            $workerDetailResult['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($workerDetailResult['worker_identity_id']);
            $workerDetailResult['worker_live_place'] = self::getWorkerPlaceShow($workerDetailResult['worker_live_province'],$workerDetailResult['worker_live_city'],$workerDetailResult['worker_live_area'],$workerDetailResult['worker_live_street']);
            $workerDetailResult['worker_skill'] = WorkerSkill::getWorkerSkill($worker_id);
            unset($workerDetailResult['workerStatRelation']);
            unset($workerDetailResult['workerExtRelation']);
        }else{
            return [];
        }
        return $workerDetailResult;
    }


    /*
     * 通过电话获取阿姨信息
     * @param string $phone 阿姨电话
     * @return array 阿姨详细信息(阿姨id，阿姨姓名)
     */
    public static function getWorkerInfoByPhone($phone){

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

    /**
     * 批量获取阿姨id
     * @param  int $type 阿姨类型 1自营 2非自营
     * @param  int $identity_id 阿姨身份id
     * @return array 阿姨id列表
     */
    public static function getWorkerIds($type,$identity_id){
        $condition['worker_type'] = $type;
        $condition['worker_identity_id'] = $identity_id;
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
                if($val['worker_stat_order_num']!==0){
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
     * 获取商圈中所有阿姨
     * @param $district_id
     * @param array $filterCondition 阿姨筛选条件
     * @return array 阿姨列表
     */
     protected static function getDistrictAllWorker($district_id,$filterCondition=[]){
         if(empty($districtId) || !is_array($filterCondition)){
             return [];
         }
         $defaultCondition['worker_is_block'] = 0;
         $defaultCondition['worker_is_vacation'] = 0;
         $defaultCondition['worker_is_blacklist'] = 0;
         $condition = array_merge($defaultCondition,$filterCondition);
         //获取所属商圈中所有阿姨
         $districtWorkerResult = Worker::find()
             ->select('{{%worker}}.id,shop_id,worker_name,worker_phone,worker_idcard,worker_identity_id,worker_type,name as shop_name,worker_stat_order_num,worker_stat_order_refuse')
             ->innerJoinWith('workerDistrictRelation') //关联worker workerDistrictRelation方法
             ->andOnCondition(['operation_shop_district_id'=>$district_id])
             ->innerjoinWith('shopRelation') //关联worker shopRelation方法
             ->joinWith('workerStatRelation') //关联worker WorkerStatRelation方法
             ->joinWith('workerScheduleRelation') //关联WorkerScheduleRelation方法
             ->where($condition)
             ->asArray()
             ->all();

         return $districtWorkerResult;
     }


    /**
     * 获取商圈中 所有可用阿姨
     * @param int district_d 商圈id
     * @param int worker_type 阿姨类型 1自营2非自营
     * @param int orderBookBeginTime 待指派订单预约开始时间
     * @param int orderBookEndTime 待指派订单预约结束时间
     * @return array freeWorkerArr 所有可用阿姨列表
     */
    public static function getDistrictFreeWorker($district_id=1,$worker_type=1,$orderBookBeginTime,$orderBookEndTime){

        $workerIdentityConfigArr = WorkerIdentityConfig::getWorkerIdentityList();

        //获取商圈中所有阿姨
        $condition['worker_type'] = $worker_type;
        $districtWorkerResult = self::getDistrictAllWorker($district_id,$condition);

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
     *
     * @param $district_id 商圈id
     * @param $time 服务时长
     * @return array
     */
    public static function getWorkerTimeLine($district_id,$time){
        $districtWorkerResult = self::getDistrictAllWorker($district_id);
        return $districtWorkerResult;
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
        $onlineCityList = CoreOperationCity::getCityOnlineInfoList();
        return $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
    }

    /*
     * 获取已开通城市名称
     * @param int $city_d 城市id
     * @return sting $cityName 城市名称
     */
    public static function getOnlineCityName($city_id=0){
        if(empty($city_id)) return '';
        $onlineCity= CoreOperationCity::find()->select('city_name')->where(['city_id'=>$city_id])->asArray()->one();
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
        $districtList = CoreOperationShopDistrict::getCityShopDistrictList();
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
        $areaResult = CoreOperationArea::getOneFromId($id);
        return isset($areaResult['area_name'])?$areaResult['area_name']:'';
    }

    /*
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

    /*
     * 获取阿姨性别名称
     */
    public static function getWorkerSexShow($worker_sex){
        if($worker_sex==0){
            return '女';
        }else{
            return '男';
        }
    }

    /*
     * 阿姨是否有健康证
     */
    public static function getWorkerIsHealthShow($worker_is_health){
        if($worker_is_health==1){
            return '有';
        }else{
            return '无';
        }
    }

    /*
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
        return [1=>'自有',2=>'非自有'];
    }

    /*
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
                return '已上岗';
            case 4:
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
        return $this->hasOne(WorkerSchedule::className(),['worker_id'=>'id']);
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



}
