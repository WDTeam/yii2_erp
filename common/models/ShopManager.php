<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shop_manager}}".
 *
 * @property string $id
 * @property string $name
 * @property string $province_name
 * @property string $city_name
 * @property string $county_name
 * @property string $street
 * @property string $principal
 * @property string $tel
 * @property string $other_contact
 * @property string $bankcard_number
 * @property string $account_person
 * @property string $opening_bank
 * @property string $sub_branch
 * @property string $opening_address
 * @property string $bl_name
 * @property integer $bl_type
 * @property string $bl_number
 * @property string $bl_person
 * @property string $bl_address
 * @property integer $bl_create_time
 * @property string $bl_photo_url
 * @property integer $bl_audit
 * @property integer $bl_expiry_start
 * @property integer $bl_expiry_end
 * @property string $bl_business
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $is_blacklist
 * @property integer $blacklist_time
 * @property string $blacklist_cause
 * @property integer $audit_status
 * @property integer $shop_count
 * @property integer $worker_count
 * @property integer $complain_coutn
 * @property string $level
 */
class ShopManager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_manager}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province_name', 'city_name', 'street', 'principal', 'tel'], 'required'],
            [['bl_type', 'bl_create_time', 'bl_audit', 'bl_expiry_start', 'bl_expiry_end', 'create_at', 'update_at', 'is_blacklist', 'blacklist_time', 'audit_status', 'shop_count', 'worker_count', 'complain_coutn'], 'integer'],
            [['bl_business'], 'string'],
            [['name', 'street', 'opening_address', 'bl_name', 'bl_address', 'bl_photo_url', 'blacklist_cause'], 'string', 'max' => 255],
            [['province_name', 'city_name', 'county_name', 'principal', 'tel', 'bankcard_number', 'bl_person', 'level'], 'string', 'max' => 50],
            [['other_contact', 'opening_bank', 'sub_branch', 'bl_number'], 'string', 'max' => 200],
            [['account_person'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '家政名称'),
            'province_name' => Yii::t('app', '省份'),
            'city_name' => Yii::t('app', '城市'),
            'county_name' => Yii::t('app', '县'),
            'street' => Yii::t('app', '办公街道'),
            'principal' => Yii::t('app', '负责人'),
            'tel' => Yii::t('app', '电话'),
            'other_contact' => Yii::t('app', '其他联系方式'),
            'bankcard_number' => Yii::t('app', '银行卡号'),
            'account_person' => Yii::t('app', '开户人'),
            'opening_bank' => Yii::t('app', '开户行'),
            'sub_branch' => Yii::t('app', '支行名称'),
            'opening_address' => Yii::t('app', '开户地址'),
            'bl_name' => Yii::t('app', '营业执照名称'),
            'bl_type' => Yii::t('app', '注册类型:1,个体户'),
            'bl_number' => Yii::t('app', '注册号'),
            'bl_person' => Yii::t('app', '法人代表'),
            'bl_address' => Yii::t('app', '营业地址'),
            'bl_create_time' => Yii::t('app', '注册时间'),
            'bl_photo_url' => Yii::t('app', '营业执照URL'),
            'bl_audit' => Yii::t('app', '注册资本'),
            'bl_expiry_start' => Yii::t('app', '有效期起始时间'),
            'bl_expiry_end' => Yii::t('app', '有效期结束时间'),
            'bl_business' => Yii::t('app', '营业范围'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
            'is_blacklist' => Yii::t('app', '是否是黑名单：0正常，1黑名单'),
            'blacklist_time' => Yii::t('app', '加入黑名单时间'),
            'blacklist_cause' => Yii::t('app', '黑名单原因'),
            'audit_status' => Yii::t('app', '审核状态：0未审核，1通过，2不通过'),
            'shop_count' => Yii::t('app', '门店数量'),
            'worker_count' => Yii::t('app', '阿姨数量'),
            'complain_coutn' => Yii::t('app', '投诉数量'),
            'level' => Yii::t('app', '评级'),
        ];
    }

    /**
     * @inheritdoc
     * @return ShopManagerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopManagerQuery(get_called_class());
    }
}
