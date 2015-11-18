<?php

namespace dbbase\models\worker;
use yii\helpers\ArrayHelper;
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
 * @property integer $worker_is_block
 * @property integer $worker_is_blacklist
 * @property integer $worker_is_vacation
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
            [['worker_phone','worker_idcard'],'unique','message'=>'{attribute}{value}已被注册','filter'=>['isdel'=>0]],
            ['worker_phone','match','pattern'=>'/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/','message'=>'请填写正确格式的手机号码'],
            ['worker_idcard','match','pattern'=>'/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','message'=>'请填写正确格式的身份证号'],
            [['shop_id','worker_name','worker_work_city','worker_phone','worker_idcard','worker_type', 'worker_type','worker_identity_id'],'required'],
            [['shop_id', 'worker_level', 'worker_auth_status',  'worker_work_city', 'worker_work_area', 'worker_type', 'worker_rule_id', 'worker_is_block', 'worker_is_blacklist', 'worker_is_dimission','created_ad', 'updated_ad', 'isdel'], 'integer'],
            [['worker_work_lng', 'worker_work_lat'], 'number'],
            [['worker_name'], 'string', 'max' => 20],
            [['worker_idcard'], 'string', 'max' => 18],
            [['worker_phone'], 'string', 'max' => 11],
            [['worker_dimission_reason','worker_blacklist_reason'], 'string', 'max' => 150],
            [['worker_password', 'worker_work_street'], 'string', 'max' => 50],
            [['worker_blacklist_reason'], 'string', 'max' => 200],
            [['worker_dimission_reason'], 'string', 'max' => 200],
            [['worker_work_street'], 'string', 'max' => 50],
            [['worker_photo'], 'image', 'maxSize' => 1024 * 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨表id'),
            'shop_id' => Yii::t('app', '阿姨所属门店'),
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
            'worker_identity_id' => Yii::t('app', '阿姨身份 '),
            'worker_is_block' => Yii::t('app', '阿姨是否封号'),
            'worker_is_vacation' => Yii::t('app', '阿姨是否请假'),
            'worker_is_blacklist' => Yii::t('app', '黑名单状态'),
            'worker_blacklist_reason' => Yii::t('app', '黑名单原因'),
            'worker_blacklist_time' => Yii::t('app', '录入黑名单时间'),
            'worker_is_dimission' => Yii::t('app', '离职状态'),
            'worker_dimission_reason' => Yii::t('app', '离职原因'),
            'worker_dimission_time' => Yii::t('app', '离职时间'),
            'worker_star' => Yii::t('app', '阿姨星级'),
            'created_ad' => Yii::t('app', '阿姨录入时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'isdel' => Yii::t('app', '是否删号'),
            'worker_district' => Yii::t('app', '阿姨所属商圈'),
        ];
    }

}
