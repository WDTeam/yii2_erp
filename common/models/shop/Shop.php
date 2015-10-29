<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "{{%shop}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $shop_manager_id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $county_id
 * @property string $street
 * @property string $principal
 * @property string $tel
 * @property string $other_contact
 * @property string $bankcard_number
 * @property string $account_person
 * @property string $opening_bank
 * @property string $sub_branch
 * @property string $opening_address
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_blacklist
 * @property integer $blacklist_time
 * @property string $blacklist_cause
 * @property integer $audit_status
 * @property integer $worker_count
 * @property integer $complain_coutn
 * @property string $level
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'street', 'principal', 'tel'], 'required'],
            [['shop_manager_id', 'province_id', 'city_id', 'county_id', 'operation_shop_district_id', 'created_at', 'updated_at', 'is_blacklist', 'audit_status', 'worker_count', 'complain_coutn'], 'integer'],
            [['name', 'account_person', 'account'], 'string', 'max' => 100],
            [['street', 'opening_address'], 'string', 'max' => 255],
            [['principal', 'tel', 'bankcard_number', 'level', 'password_hash'], 'string', 'max' => 50],
            [['other_contact', 'opening_bank', 'sub_branch'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '店名'),
            'shop_manager_id' => Yii::t('app', '归属家政ID'),
            'province_id' => Yii::t('app', '省份ID'),
            'city_id' => Yii::t('app', '城市ID'),
            'county_id' => Yii::t('app', '区县ID'),
            'street' => Yii::t('app', '办公街道'),
            'operation_shop_district_id' => Yii::t('app', '商圈'),
            'principal' => Yii::t('app', '负责人'),
            'tel' => Yii::t('app', '电话'),
            'other_contact' => Yii::t('app', '其他联系方式'),
            'bankcard_number' => Yii::t('app', '银行卡号'),
            'account_person' => Yii::t('app', '开户人'),
            'opening_bank' => Yii::t('app', '开户行'),
            'sub_branch' => Yii::t('app', '支行名称'),
            'opening_address' => Yii::t('app', '开户地址'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'is_blacklist' => Yii::t('app', '是否是黑名单：0正常，1黑名单'),
            // 'blacklist_time' => Yii::t('app', '加入黑名单时间'),
            // 'blacklist_cause' => Yii::t('app', '黑名单原因'),
            'audit_status' => Yii::t('app', '审核状态：0未审核，1通过，2不通过'),
            'worker_count' => Yii::t('app', '阿姨数量'),
            'complain_coutn' => Yii::t('app', '投诉数量'),
            'level' => Yii::t('app', '评级'),
        ];
    }

    /**
     * @inheritdoc
     * @return ShopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopQuery(get_called_class());
    }
}
