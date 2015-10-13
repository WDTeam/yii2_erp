<?php

namespace core\models\worker;


use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;

use common\models\OrderExtWorker;
use common\models\ShopManager;
use core\models\worker\WorkerStat;
use core\models\worker\WorkerExt;
use core\models\worker\WorkerRuleConfig;
use core\models\Operation\CoreOperationShopDistrict;
use core\models\Operation\CoreOperationCity;
use boss\models\Shop;

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
 * @property integer $worker_is_block
 * @property integer $worker_is_blacklist
 * @property integer $worker_is_vacation
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $isdel
 */
class Worker extends \common\models\Worker
{


    /*
     * 获取阿姨列表
     * @param  int $type 阿姨类型 1自营 2非自营
     * @param  int $rule_id 阿姨身份id
     * @return array 阿姨ID列表
     */
    public static function getWorkerIds($type,$rule_id){
        $condition['worker_type'] = $type;
        $condition['worker_rule_id'] = $rule_id;
        $workerList = self::find()->select('id')->where($condition)->asArray()->all();
        return $workerList?ArrayHelper::getColumn($workerList,'id'):[];
    }

    /*
     * 获取单个阿姨详细信息
     * @param worker_id int 阿姨id
     * @return  单个阿姨详细信息
     */
    public static function getWorkerInfo($worker_id){

        $workerInfo = self::find()->where((['id'=>$worker_id]))->select('id,shop_id,worker_name,worker_phone,worker_idcard,worker_type,worker_rule_id,created_ad')->asArray()->one();
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
            $workerInfo['worker_rule_description'] = WorkerRuleConfig::getWorkerRuleShow($workerInfo['worker_rule_id']);
        }else{
            $workerInfo = [];
        }
        return $workerInfo;
    }


    /*
     * 通过电话获取可用阿姨信息
     * @param string $phone 阿姨电话
     * @return array $workerInfo 阿姨详细信息(阿姨id，阿姨姓名)
     */
     public static function getWorkerInfoByPhone($phone){

        $condition = ['worker_phone'=>$phone,'isdel'=>0,'worker_is_block'=>0,'worker_is_vacation'=>0,'worker_is_blacklist'=>0];
        $workerInfo = worker::find()->where($condition)->select('id,worker_name')->asArray()->one();
        return $workerInfo;
    }

    /*
     * 获取商圈中 所有可用阿姨id
     * @param int districtId 商圈id
     * @param int worker_type 阿姨类型 1自营2非自营
     * @param int orderBookBeginTime 待指派订单预约开始时间
     * @param int orderBookeEndTime 待指派订单预约结束时间
     * @return array freeWorkerArr 所有可用阿姨id
     */
    public static function getDistrictFreeWorker($districtId=1,$workerType=1,$orderBookBeginTime,$orderBookeEndTime){


        //获取所属商圈中所有阿姨
        $districtWorkerResult = Worker::find()
            ->select('{{%worker}}.id,shop_id,worker_name,worker_phone,worker_idcard,worker_rule_id,worker_type,name as shop_name,worker_stat_order_num,worker_stat_order_refuse')
            ->innerJoinWith('workerDistrictRelation') //关联worker workerDistrictRelation方法
            ->andOnCondition(['operation_shop_district_id'=>$districtId])
            ->joinWith('workerStatRelation') //关联worker WorkerStatRelation方法
            ->joinWith('shopRelation') //关联worker shopRelation方法
            ->where(['worker_is_block'=>0,'worker_is_blacklist'=>0,'worker_is_vacation'=>0,'worker_type'=>$workerType])
            ->asArray()
            ->all();
//var_dump($districtWorkerResult);
        $workerRuleConfigArr = WorkerRuleConfig::getWorkerRuleList();
        if(empty($districtWorkerResult)){
            return [];
        }else{
            foreach ($districtWorkerResult as $val) {

                $districtWorkerIdsArr[] = $val['id'];

                $val['worker_id'] = $val['id'];
                $val['worker_type_description'] = self::getWorkerTypeShow($val['worker_type']);
                $val['worker_rule_description'] = $workerRuleConfigArr[$val['worker_rule_id']];

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
        //$condition = "(order_booked_begin_time>$orderBookeEndTime or order_booked_end_time<$orderBookBeginTime)";
        $condition = "	(ejj_order.order_booked_begin_time<=$orderBookBeginTime AND ejj_order.order_booked_end_time>=$orderBookBeginTime )";
        $condition.= " OR ( ejj_order.order_booked_begin_time<=$orderBookeEndTime AND ejj_order.order_booked_end_time>=$orderBookeEndTime )";
        $condition.= " OR ( ejj_order.order_booked_begin_time>=$orderBookBeginTime AND ejj_order.order_booked_end_time<=$orderBookeEndTime )";
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


    /*
     * 获取已开通城市列表
     * @return array [city_id=>city_name,...]
     */
    public static function getOnlineCityList(){
        $onlineCityList= CoreOperationCity::find()->select('city_id,city_name')->where(['operation_city_is_online'=>1])->asArray()->all();
        return $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
    }

    /*
     * 获取已开通城市名称
     * @param int $city_d 城市id
     * @return sting $cityName 城市名称
     */
    public static function getOnlineCityName($city_d=0){
        if(empty($city_d)) return '';
        $onlineCity= CoreOperationCity::find()->select('city_name')->where(['city_id'=>$city_d])->asArray()->one();
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
        $districtList =CoreOperationShopDistrict::find()->select('id,operation_shop_district_name')->where(['operation_shop_district_status'=>2])->asArray()->all();
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
                return '已试工';
            case 3:
                return '已上岗';
            case 4:
                return '已离职';
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



    /*
     * 统计被列入黑名单的阿姨的数量
     */
    public static function CountBlockWorker(){
        return self::find()->where(['worker_is_block'=>1,'isdel'=>0])->count();
    }

    /*
     * 统计被封号的阿姨的数量
     */
    public static function CountBlackListWorker(){
        return self::find()->where(['worker_is_blacklist'=>1,'isdel'=>0])->count();
    }

    /*
     * 统计请假的阿姨数量的数量
     */
    public static function CountVacationWorker(){
        return self::find()->where(['worker_is_vacation'=>1,'isdel'=>0])->count();
    }

    /*
     * 统计各个身份的阿姨数量
     */
    public static function CountRuleWorker($workerRuleId){
        return self::find()->where(['worker_rule_id'=>$workerRuleId,'isdel'=>0])->count();
    }

    public function getAuthStatusCount(){
        return $this->find()->where(['worker_auth_status'=>0,'worker_ontrial_status'=>0,'worker_onboard_status'=>0])->count();
    }

    public function getOntrialStatusCount(){
        return $this->find()->where(['worker_auth_status'=>1,'worker_ontrial_status'=>0,'worker_onboard_status'=>0])->count();
    }

    public function getOnboardStatusCount(){
        return $this->find()->where(['worker_auth_status'=>1,'worker_ontrial_status'=>1,'worker_onboard_status'=>0])->count();
    }

    /*
     * 加入黑名单
     * @param string $cause 原因
     */
    public function joinBlacklist($cause='')
    {
        $this->worker_is_blacklist = 1;
        if($this->save()){
            return true;
        }
        return false;
    }

    /*
     * 移出黑名单
     * @param string $cause 原因
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

   /*
   * 获取阿姨首页按钮css样式class
   * @param int $btnCate 按钮所属类型 0-10
   * @return string 按钮css样式class   btn-success-selected(按钮被选中) or btn-success(按钮未选中)
   */
    public static function getSearchBtnCss($btnCate){
        $searchParams = Yii::$app->request->getQueryParams();
        $workerSearchParams = array_key_exists('WorkerSearch',$searchParams)?$searchParams['WorkerSearch']:[];
        if($btnCate==0 && !array_key_exists('WorkerSearch',$searchParams)){
            return 'btn-success-selected';
        }elseif($btnCate==1 && array_key_exists('worker_auth_status',$workerSearchParams)){
            return 'btn-success-selected';
        }elseif($btnCate==2 && array_key_exists('worker_ontrial_status',$workerSearchParams)){
            return 'btn-success-selected';
        }elseif($btnCate==3 && array_key_exists('worker_onboard_status',$workerSearchParams)){
            return 'btn-success-selected';
        }elseif($btnCate==4 && array_key_exists('worker_rule_id',$workerSearchParams) && $workerSearchParams['worker_rule_id']==1){
            return 'btn-success-selected';
        }elseif($btnCate==5 && array_key_exists('worker_rule_id',$workerSearchParams) && $workerSearchParams['worker_rule_id']==2){
            return 'btn-success-selected';
        }elseif($btnCate==6 && array_key_exists('worker_rule_id',$workerSearchParams) && $workerSearchParams['worker_rule_id']==3){
            return 'btn-success-selected';
        }elseif($btnCate==7 && array_key_exists('worker_rule_id',$workerSearchParams) && $workerSearchParams['worker_rule_id']==4){
            return 'btn-success-selected';
        }elseif($btnCate==8 && array_key_exists('worker_is_vacation',$workerSearchParams)){
            return 'btn-success-selected';
        }elseif($btnCate==9 && array_key_exists('worker_is_block',$workerSearchParams)){
            return 'btn-success-selected';
        }elseif($btnCate==10 && array_key_exists('worker_is_blacklist',$workerSearchParams)){
            return 'btn-success-selected';
        }else{
            return 'btn-success';
        }
    }

    /*
     * 阿姨附属表连表方法
     */
    public function getWorkerExtRelation(){
        return $this->hasOne(WorkerExt::className(),['worker_id'=>'id']);
    }

    /*
     * 阿姨商圈表连表方法
     */
    public function getWorkerDistrictRelation(){
        return $this->hasOne(WorkerDistrict::className(),['worker_id'=>'id'])->select('worker_id,operation_shop_district_id');
    }

    /*
     * 阿姨统计表连表方法
     */
    public function getWorkerStatRelation(){
        return $this->hasOne(WorkerStat::className(),['worker_id'=>'id']);
    }

    /*
     * 商铺表连表方法
     */
    public function getShopRelation(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }

    /*
     * 赋值worker_district属性
     */
    public function getworker_district(){
        $workerDistrictArr = self::getWorkerDistrict($this->id);
//        var_dump(ArrayHelper::getColumn($workerDistrictArr,'operation_shop_district_id'));die;
        return $workerDistrictArr?ArrayHelper::getColumn($workerDistrictArr,'operation_shop_district_id'):[];
    }



}
