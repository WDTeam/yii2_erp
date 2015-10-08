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
 * @property integer $operation_area_id
 * @property integer $operation_city_id
 * @property integer $general_region_id
 * @property string $customer_live_address_detail
 * @property string $customer_balance
 * @property integer $customer_score
 * @property integer $customer_level
 * @property integer $customer_complaint_times
 * @property integer $customer_src
 * @property integer $channal_id
 * @property integer $platform_id
 * @property string $customer_login_ip
 * @property integer $customer_login_time
 * @property integer $customer_is_vip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 * @property string $customer_del_reason
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
            [['customer_sex', 'customer_birth', 'operation_area_id', 'operation_city_id', 'general_region_id', 'customer_score', 'customer_level', 'customer_complaint_times', 'customer_src', 'channal_id', 'platform_id', 'customer_login_time', 'customer_is_vip', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_balance'], 'number'],
            [['customer_del_reason', 'customer_platform_version', 'customer_app_version', 'customer_mac'], 'string'],
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
            'id' => Yii::t('boss', 'ID'),
            'customer_name' => Yii::t('boss', '用户名'),
            'customer_sex' => Yii::t('boss', '性别'),
            'customer_birth' => Yii::t('boss', '生日'),
            'customer_photo' => Yii::t('boss', '头像'),
            'customer_phone' => Yii::t('boss', '电话'),
            'customer_email' => Yii::t('boss', '邮箱'),
            'operation_area_id' => Yii::t('boss', '商圈'),
            'operation_city_id' => Yii::t('boss', '城市'),
            'general_region_id' => Yii::t('boss', '住址'),
            'customer_live_address_detail' => Yii::t('boss', '详细住址'),
            'customer_balance' => Yii::t('boss', '账户余额'),
            'customer_score' => Yii::t('boss', '积分'),
            'customer_level' => Yii::t('boss', '评级'),
            'customer_complaint_times' => Yii::t('boss', '投诉'),
            'customer_src' => Yii::t('boss', '来源，1为线下，2为线上'),
            'channal_id' => Yii::t('boss', '渠道'),
            'platform_id' => Yii::t('boss', '平台'),
            'customer_platform_version'=>Yii::t('boss', '操作系统版本号'),
            'customer_app_version'=>Yii::t('boss', 'app版本号'),
            'customer_mac'=>Yii::t('boss', 'mac地址'),
            'customer_login_ip' => Yii::t('boss', '登陆ip'),
            'customer_login_time' => Yii::t('boss', '登陆时间'),
            'customer_is_vip' => Yii::t('boss', '身份'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '加入黑名单'),
            'customer_del_reason' => Yii::t('boss', '加入黑名单原因'),
        ];
    }

    

    /**
     * 客户账户余额转入
     */
    static public function incBalance($customer_id, $cash)
    {
        // $customer_id = \Yii::$app->request->get('customer_id');
        // $cash = \Yii::$app->request->get('cash');
        // \Yii::$app->response->format = Response::FORMAT_JSON;

        $customer = Customer::find()->where(['id'=>$customer_id])->one();
        $balance = $customer->customer_balance;
        $customer->customer_balance = bcadd($balance, $cash);
        $customer->validate();
        if ($customer->hasErrors()) {
            return false;
        }
        $customer->save();
        return true;
    }

    /**
     * 客户账户余额转出
     */
    static public function decBalance($customer_id, $cash)
    {
        // $customer_id = \Yii::$app->request->get('customer_id');
        // $cash = \Yii::$app->request->get('cash');
        // \Yii::$app->response->format = Response::FORMAT_JSON;

        $customer = Customer::find()->where(['id'=>$customer_id])->one();
        $balance = $customer->customer_balance;
        $customer->customer_balance = bcsub($balance, $cash);
        $customer->validate();
        if ($customer->hasErrors()) {
            return false;
        }
        $customer->save();
        return true;
    }
}
