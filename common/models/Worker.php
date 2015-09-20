<?php

namespace common\models;

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
 * @property integer $worker_rule
 * @property integer $worker_identify_id
 * @property integer $worker_is_block
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $isdel
 */
class Worker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'worker_level', 'worker_auth_status', 'worker_ontrial_status', 'worker_onboard_status', 'worker_work_city', 'worker_work_area', 'worker_rule', 'worker_identify_id', 'worker_is_block', 'created_ad', 'updated_ad', 'isdel'], 'integer'],
            [['worker_work_lng', 'worker_work_lat'], 'number'],
            [['worker_name'], 'string', 'max' => 10],
            [['worker_phone', 'worker_idcard'], 'string', 'max' => 20],
            [['worker_password', 'worker_work_street'], 'string', 'max' => 50],
            [['worker_photo'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨id'),
            'shop_id' => Yii::t('app', '门店id'),
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
            'worker_rule' => Yii::t('app', '阿姨角色'),
            'worker_identify_id' => Yii::t('app', '阿姨身份id '),
            'worker_is_block' => Yii::t('app', '阿姨是否封号'),
            'created_ad' => Yii::t('app', '阿姨录入时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'isdel' => Yii::t('app', '是否删号'),
        ];
    }
}
