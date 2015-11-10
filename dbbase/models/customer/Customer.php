<?php

namespace dbbase\models\customer;

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
 * @property integer $operation_area_id
 * @property string $operation_area_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $customer_level
 * @property integer $customer_complaint_times
 * @property string $customer_platform_version
 * @property string $customer_app_version
 * @property string $customer_mac
 * @property string $customer_login_ip
 * @property integer $customer_login_time
 * @property integer $customer_is_vip
 * @property integer $customer_is_weixin
 * @property string $weixin_id
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
            [['customer_sex', 'customer_birth', 'operation_area_id', 'operation_city_id', 'customer_level', 'customer_complaint_times', 'customer_login_time', 'customer_is_vip', 'customer_is_weixin', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_name', 'customer_platform_version', 'customer_app_version', 'customer_mac', 'customer_login_ip'], 'string', 'max' => 16],
            [['customer_photo', 'customer_email'], 'string', 'max' => 32],
            [['customer_phone'], 'string', 'max' => 11],
            [['operation_area_name', 'operation_city_name', 'weixin_id'], 'string', 'max' => 255],
            [['customer_phone'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dbbase', 'ID'),
            'customer_name' => Yii::t('dbbase', '用户名'),
            'customer_sex' => Yii::t('dbbase', '性别'),
            'customer_birth' => Yii::t('dbbase', '生日'),
            'customer_photo' => Yii::t('dbbase', '头像'),
            'customer_phone' => Yii::t('dbbase', '电话'),
            'customer_email' => Yii::t('dbbase', '邮箱'),
            'operation_area_id' => Yii::t('dbbase', '商圈'),
            'operation_area_name' => Yii::t('dbbase', '商圈'),
            'operation_city_id' => Yii::t('dbbase', '城市'),
            'operation_city_name' => Yii::t('dbbase', '城市'),
            'customer_level' => Yii::t('dbbase', '评级'),
            'customer_complaint_times' => Yii::t('dbbase', '投诉'),
            'customer_platform_version' => Yii::t('dbbase', '操作系统版本号'),
            'customer_app_version' => Yii::t('dbbase', 'app版本号'),
            'customer_mac' => Yii::t('dbbase', 'mac地址'),
            'customer_login_ip' => Yii::t('dbbase', '登陆ip'),
            'customer_login_time' => Yii::t('dbbase', '登陆时间'),
            'customer_is_vip' => Yii::t('dbbase', '身份'),
            'customer_is_weixin' => Yii::t('dbbase', '是否微信客户'),
            'weixin_id' => Yii::t('dbbase', '微信id'),
            'created_at' => Yii::t('dbbase', '创建时间'),
            'updated_at' => Yii::t('dbbase', '更新时间'),
            'is_del' => Yii::t('dbbase', '加入黑名单'),
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerQuery(get_called_class());
    }
}
