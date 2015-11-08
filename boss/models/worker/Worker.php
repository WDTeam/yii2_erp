<?php

namespace boss\models\worker;

use Yii;
use yii\helpers\ArrayHelper;
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
 * @property integer $worker_is_vacation
 * @property integer $worker_is_blacklist
 * @property string $worker_blacklist_reason
 * @property integer $worker_blacklist_time
 * @property integer $worker_is_dimission
 * @property string $worker_dimission_reason
 * @property integer $worker_dimission_time
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $isdel
 */
class Worker extends \core\models\worker\Worker
{
    public $worker_district;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['worker_district','worker_photo'], 'required','on'=>['create','update']], //只有在后台保存和更新阿姨信息时验证
        ];
        return array_merge(parent::rules(),$rules);
    }

    /**
    * 获取阿姨首页按钮css样式class
    * @param int $btnCate 按钮所属类型 0-10
    * @return string 按钮css样式class   btn-success-selected(按钮被选中) or btn-success(按钮未选中)
    */
    public static function setBtnCss($btnCate){
        $params = Yii::$app->request->getQueryParams();
        $workerParams = isset($params['WorkerSearch'])?$params['WorkerSearch']:[];
        if($btnCate==0 && !isset($params['WorkerSearch'])){
            return 'btn-success-selected';
        }elseif($btnCate==1 && isset($workerParams['worker_auth_status']) && $workerParams['worker_auth_status']==0){
            return 'btn-success-selected';
        }elseif($btnCate==2 && isset($workerParams['worker_auth_status']) && $workerParams['worker_auth_status']==2){
            return 'btn-success-selected';
        }elseif($btnCate==3 && isset($workerParams['worker_auth_status']) && $workerParams['worker_auth_status']==3){
            return 'btn-success-selected';
        }elseif($btnCate==4 && isset($workerParams['worker_identity_id']) && $workerParams['worker_identity_id']==1){
            return 'btn-success-selected';
        }elseif($btnCate==5 && isset($workerParams['worker_identity_id']) && $workerParams['worker_identity_id']==2){
            return 'btn-success-selected';
        }elseif($btnCate==6 && isset($workerParams['worker_identity_id']) && $workerParams['worker_identity_id']==3){
            return 'btn-success-selected';
        }elseif($btnCate==7 && isset($workerParams['worker_identity_id']) && $workerParams['worker_identity_id']==4){
            return 'btn-success-selected';
        }elseif($btnCate==8 && isset($workerParams['worker_is_vacation'])){
            return 'btn-success-selected';
        }elseif($btnCate==9 && isset($workerParams['worker_is_block'])){
            return 'btn-success-selected';
        }elseif($btnCate==10 && isset($workerParams['worker_is_blacklist'])){
            return 'btn-success-selected';
        }elseif($btnCate==11 && isset($workerParams['worker_is_dimission'])){
            return 'btn-success-selected';
        }elseif($btnCate==12 && isset($workerParams['worker_vacation_application_approve_status'])){
            return 'btn-success-selected';
        }else{
            return 'btn-success';
        }
    }

    /**
     * 是否显示某一列
     * @param $columnName
     * @return bool
     */
    public static function columnsIsHidden($columnName){
        $params = Yii::$app->request->getQueryParams();
        if($columnName=='blacklist' && !empty($params['WorkerSearch']['worker_is_blacklist'])){
            return false;
        }elseif($columnName=='dimission' && !empty($params['WorkerSearch']['worker_is_dimission'])){
            return false;
        }elseif($columnName=='other' && empty($params['WorkerSearch']['worker_is_blacklist']) && empty($params['WorkerSearch']['worker_is_dimission'])){
            return false;
        }else{
            return true;
        }
    }

    public static function getWorkerPhotoShow($worker_photo){
        if($worker_photo){
            return \yii\helpers\Html::img($worker_photo, ['class'=>'file-preview-image']);
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
     * 统计各个审核状态的阿姨数量
     */
    public static function CountDimissionWorker(){
        return self::find()->where(['worker_is_dimission'=>1])->count();
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
    public static function CountWorkerIdentity($workerIdentityId){
        return self::find()->where(['worker_identity_id'=>$workerIdentityId,'isdel'=>0])->count();
    }

    /*
     * 统计各个审核状态的阿姨数量
     */
    public static function CountWorkerStatus($workerStatus){
        return self::find()->where(['worker_auth_status'=>$workerStatus,'isdel'=>0])->count();
    }

    public static function CountWorker(){
        return self::find()->where(['isdel'=>0])->count();
    }

    /**
     * 设置worker_district属性
     */
//    public function getworker_district(){
//        $workerDistrictArr = self::getWorkerDistrict($this->id);
//        return $workerDistrictArr?ArrayHelper::getColumn($workerDistrictArr,'operation_shop_district_id'):[];
//    }
    /**
     * 设置worker_district属性
     */
//    public function setworker_district(){
//        return 1;
//    }

}
