<?php

namespace boss\models\worker;

use core\models\worker\WorkerExt;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\worker\Worker;

/**
 * WorkerSearch represents the model behind the search form about `common\models\Worker`.
 */
class WorkerDetail extends Worker
{





    public function rules()
    {
        return [
            [['id', 'shop_id', 'worker_level', 'worker_auth_status', 'worker_ontrial_status', 'worker_onboard_status', 'worker_work_city', 'worker_work_area', 'worker_type', 'worker_rule_id', 'worker_is_block', 'worker_is_blacklist', 'worker_is_vacation','created_ad', 'updated_ad', 'isdel'], 'integer'],
            [['worker_name', 'worker_phone', 'worker_idcard', 'worker_password', 'worker_photo', 'worker_work_street'], 'safe'],
            [['worker_work_lng', 'worker_work_lat'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨表id'),
            'shop_id' => Yii::t('app', '门店名称'),
            'worker_name' => Yii::t('app', '阿姨姓名'),
            'worker_phone' => Yii::t('app', '阿姨手机'),
            'worker_idcard' => Yii::t('app', '阿姨身份证号'),
            'worker_password' => Yii::t('app', '阿姨端登录密码'),
            'worker_photo' => Yii::t('app', '阿姨头像地址'),
            'worker_level' => Yii::t('app', '阿姨等级'),
            'worker_auth_status' => Yii::t('app', '阿姨审核状态'),
            'worker_ontrial_status' => Yii::t('app', '阿姨试工状态'),
            'worker_onboard_status' => Yii::t('app', '阿姨上岗状态'),
            'worker_work_city' => Yii::t('app', '阿姨工作城市'),
            'worker_work_area' => Yii::t('app', '阿姨工作区县'),
            'worker_work_street' => Yii::t('app', '阿姨常用工作地址'),
            'worker_work_lng' => Yii::t('app', '阿姨常用工作经度'),
            'worker_work_lat' => Yii::t('app', '阿姨常用工作纬度'),
            'worker_type' => Yii::t('app', '阿姨类型'),
            'worker_rule_id' => Yii::t('app', '阿姨身份 '),
            'worker_is_block' => Yii::t('app', '阿姨是否封号'),
            'worker_is_blacklist' => Yii::t('app', '阿姨是否黑名单'),
            'worker_is_vacation' => Yii::t('app', '阿姨是否请假'),
            'created_ad' => Yii::t('app', '阿姨录入时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'isdel' => Yii::t('app', '是否删号'),
            'worker_age' => Yii::t('app', '阿姨年龄'),
            'worker_sex' => Yii::t('app', '阿姨性别'),
            'worker_birth' => Yii::t('app', '阿姨生日'),
            'worker_edu' => Yii::t('app', '阿姨教育程度'),
            'worker_hometown' => Yii::t('app', '阿姨籍贯'),
            'worker_is_health' => Yii::t('app', '阿姨是否有健康证'),
            'worker_is_insurance' => Yii::t('app', '阿姨是否缴纳保险'),
            'worker_source' => Yii::t('app', '阿姨来源'),
            'worker_bank_from' => Yii::t('app', '银行开户网店'),
            'worker_bank_name' => Yii::t('app', '银行名称'),
            'worker_bank_card' => Yii::t('app', '银行卡号'),
            'worker_live_province' => Yii::t('app', '阿姨居住地(省份)'),
            'worker_live_city' => Yii::t('app', '阿姨居住地(城市)'),
            'worker_live_area' => Yii::t('app', '阿姨居住地(区县)'),
            'worker_live_street' => Yii::t('app', '阿姨居住地(街道)'),
        ];
    }


    public function  getworker_age(){
        return $this->workerExt->worker_age;
    }

    public function getworker_sex(){
        return $this->workerExt->worker_sex;
    }
    public function getworker_birth(){
        return $this->workerExt->worker_birth;
    }
    public function getworker_edu(){
        return $this->workerExt->worker_edu;
    }
    public function getworker_hometown(){
        return $this->workerExt->worker_hometown;
    }
    public function getworker_is_health(){
        return $this->workerExt->worker_is_health;
    }
    public function getworker_is_insurance(){
        return $this->workerExt->worker_is_insurance;
    }
    public function getworker_source(){
        return $this->workerExt->worker_source;
    }
    public function getworker_bank_name(){
        return $this->workerExt->worker_bank_name;
    }
    public function getworker_bank_from(){
        return $this->workerExt->worker_bank_from;
    }
    public function getworker_bank_card(){
        return $this->workerExt->worker_bank_card;
    }
    public function getworker_live_province(){
        return $this->workerExt->worker_live_province;
    }
    public function getworker_live_city(){
        return $this->workerExt->worker_live_city;
    }
    public function getworker_live_area(){
        return $this->workerExt->worker_live_area;
    }
    public function getworker_live_street(){
        return $this->workerExt->worker_live_street;
    }
    public function getworker_district(){
        return $this->workerExt->worker_district;
    }

}
