<?php

namespace boss\models\worker;

use Yii;

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
        }elseif($btnCate==2 && isset($workerParams['worker_auth_status']) && $workerParams['worker_auth_status']==1){
            return 'btn-success-selected';
        }elseif($btnCate==3 && isset($workerParams['worker_auth_status']) && $workerParams['worker_auth_status']==2){
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
}
