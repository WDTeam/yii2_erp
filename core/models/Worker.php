<?php

namespace core\models;

use Yii;
use core\models\WorkerExt;
use core\models\WorkerRuleConfig;
use yii\web\ForbiddenHttpException;
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
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $isdel
 */
class Worker extends \common\models\Worker
{
    /*
     * 获取阿姨列表
     * @param type int 阿姨类型 1自营2非自营
     * @param field string 返回字段名
     * @return 阿姨列表数据
     */
    public function getWorkerList($type=0,$field='id'){
        $condition = [];
        if(!empty($type)){
            $condition = ['worker_type'=>$type];
        }
        return $this->find()->select($field)->where($condition)->asArray()->all();
    }

    /*
     * 获取单个阿姨详细信息
     * @param worker_id int 阿姨id
     * @return  单个阿姨详细信息
     *
     */
        public function getWorkerInfo($worker_id){

        $workerInfo = $this->find()->where((['id'=>$worker_id]))->select('id,shop_id,worker_name,worker_phone,worker_idcard,worker_type,worker_rule_id')->asArray()->one();
        if($workerInfo){
            //店铺名称
            $shopInfo = Shop::findone($workerInfo['shop_id']);
            $workerInfo['shop_name'] = isset($shopInfo['name'])?$shopInfo['name']:'';
            //获取阿姨身份描述信息
            $workerType = $workerInfo['worker_type']==1?'自营':'非自营';
            $workerRule = $this->getWorkerRuleShow($workerInfo['worker_rule_id']);
            $workerInfo['worker_type_description'] = $workerType.$workerRule;
        }
        return $workerInfo;
    }



    /*
     * 获取阿姨类型名称
     * return String worker_type_name
     */
    public function getWorkerTypeShow(){
        if($this->worker_type==1){
            return '自有';
        }else{
            return '非自有';
        }
    }

    /*
     * 获取阿姨身份名称
     * @return String worker_rule_name
     */
    public function getWorkerRuleShow($worker_rule_id){

        $workerRuleArr = WorkerRuleConfig::find()->where(['id'=>$worker_rule_id,'isdel'=>0])->asArray()->one();

        return $workerRuleArr['worker_rule_name'];
    }

    /*
     * 关联阿姨基本信息
     */
    public function getExt(){
        return $this->hasOne(WorkerExt::className(),['worker_id'=>'id']);
    }


    /*
     * 获取是否黑名单
     * @return String 是 or 否
     */
    public function getWorkerIsBlockShow(){
        if($this->worker_is_block==1){
            return '是';
        }else{
            return '否';
        }
    }

    /*
     * 获取是否封号
     * @return String 是 or 否
     */
    public function getWorkerIsBlacListkShow(){
        if($this->worker_is_blacklist==1){
            return '是';
        }else{
            return '否';
        }
    }

    /*
     * 获取审核状态
     * @return String 通过 or 未通过
     */
    public function getWorkerAuthStatusShow(){
        if($this->worker_auth_status==1){
            return '通过';
        }else{
            return '未通过';
        }
    }

    /*
     * 获取试工状态
     * @return String 通过 or 未通过
     */
    public function getWorkerOntrialStatusShow(){
        if($this->worker_ontrial_status==1){
            return '通过';
        }else{
            return '未通过';
        }
    }

    /*
     * 获取上岗状态
     * @return String 通过 or 未通过
     */
    public function getWorkerOnboardStatusShow(){
        if($this->worker_onboard_status==1){
            return '通过';
        }else{
            return '未通过';
        }
    }

    public function getWorkerAllRules(){

        $workerRulesArr = WorkerRuleConfig::find()->where(['isdel'=>0])->select('id,worker_rule_name')->asArray()->all();

        return $workerRulesArr;
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
    public function getBlockCount(){
        return $this->find()->where(['worker_is_block'=>1])->count();
    }
    public function getBlackListCount(){
        return $this->find()->where(['worker_is_blacklist'=>1])->count();
    }
    public function getVacationCount(){
        return $this->find()->where(['worker_auth_status'=>1,'worker_ontrial_status'=>1,'worker_onboard_status'=>0])->count();
    }

    public function getQCount(){
        return $this->find()->where(['worker_rule_id'=>1])->count();
    }
    public function getJCount(){
        return $this->find()->where(['worker_rule_id'=>2])->count();
    }
    public function getSCount(){
        return $this->find()->where(['worker_rule_id'=>3])->count();
    }
    public function getGCount(){
        return $this->find()->where(['worker_rule_id'=>4])->count();
    }
}
