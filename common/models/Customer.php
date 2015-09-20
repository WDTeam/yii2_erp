<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property integer $id
 * @property string $customer_name
 * @property integer $customer_sex
 * @property integer $customer_birth
 * @property string $customer_photo
 * @property string $customer_phone
 * @property string $customer_email
 * @property integer $region_id
 * @property string $customer_live_address_detail
 * @property integer $customer_score
 * @property integer $customer_level
 * @property integer $customer_src
 * @property integer $channal_id
 * @property integer $platform_id
 * @property string $customer_login_ip
 * @property integer $customer_login_time
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_name', 'customer_sex', 'customer_phone', 'customer_score', 'created_at', 'updated_at'], 'required'],
            [['customer_sex', 'customer_birth', 'region_id', 'customer_score', 'customer_level', 'customer_src', 'channal_id', 'platform_id', 'customer_login_time', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_name', 'customer_login_ip'], 'string', 'max' => 16],
            [['customer_photo', 'customer_email'], 'string', 'max' => 32],
            [['customer_phone'], 'string', 'max' => 11],
            [['customer_live_address_detail'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'customer_name' => Yii::t('boss', '客户名称'),
            'customer_sex' => Yii::t('boss', '客户性别'),
            'customer_birth' => Yii::t('boss', '客户生日'),
            'customer_photo' => Yii::t('boss', '客户头像'),
            'customer_phone' => Yii::t('boss', '客户手机号'),
            'customer_email' => Yii::t('boss', '客户邮箱'),
            'region_id' => Yii::t('boss', '居住区域关联'),
            'customer_live_address_detail' => Yii::t('boss', 'Customer Live Address Detail'),
            'customer_score' => Yii::t('boss', '用户积分'),
            'customer_level' => Yii::t('boss', '客户会员级别'),
            'customer_src' => Yii::t('boss', '客户来源，1为线下，2为线上'),
            'channal_id' => Yii::t('boss', '关联渠道'),
            'platform_id' => Yii::t('boss', '关联平台'),
            'customer_login_ip' => Yii::t('boss', '登陆ip'),
            'customer_login_time' => Yii::t('boss', '登陆 时间'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }
}
