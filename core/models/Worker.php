<?php

namespace core\models;

use Yii;
use core\models\WorkerExt;
use core\models\WorkerRuleConfig;
use yii\web\BadRequestHttpException;
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
    public function getWorkerRuleShow(){

        $workerRuleArr = WorkerRuleConfig::find()->where(['id'=>$this->worker_rule_id,'isdel'=>0])->asArray()->one();

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
    /**
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
    /**
     * 移出黑名单
     * @param string $cause 原因
     */
    public function removeBlacklist($cause='')
    {
        $sm = Shop::find()->where(['id'=>$this->shop_id])->one();
        if($sm->worker_is_blacklist==1){
            throw new BadRequestHttpException('所在的门店未移出黑名单');
        }
        $this->is_blacklist = 0;
        if($this->save()){
            return true;
        }
        return false;
    }
}
