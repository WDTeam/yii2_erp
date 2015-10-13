<?php

namespace core\models\GeneralPay;

use Yii;

/**
 * This is the model class for table "{{%general_pay}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property string $general_pay_money
 * @property string $general_pay_actual_money
 * @property integer $general_pay_source
 * @property string $general_pay_source_name
 * @property integer $general_pay_mode
 * @property integer $general_pay_status
 * @property string $general_pay_transaction_id
 * @property string $general_pay_eo_order_id
 * @property string $general_pay_memo
 * @property integer $general_pay_is_coupon
 * @property integer $admin_id
 * @property string $general_pay_admin_name
 * @property integer $worker_id
 * @property integer $handle_admin_id
 * @property string $general_pay_handle_admin_id
 * @property string $general_pay_verify
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class GeneralPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%general_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'general_pay_source_name'], 'required'],
            [['customer_id', 'order_id', 'general_pay_source', 'general_pay_mode', 'general_pay_status', 'general_pay_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['general_pay_money', 'general_pay_actual_money'], 'number'],
            [['general_pay_source_name'], 'string', 'max' => 20],
            [['general_pay_transaction_id'], 'string', 'max' => 40],
            [['general_pay_eo_order_id', 'general_pay_admin_name', 'general_pay_handle_admin_id'], 'string', 'max' => 30],
            [['general_pay_memo'], 'string', 'max' => 255],
            [['general_pay_verify'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('GeneralPay', 'ID'),
            'customer_id' => Yii::t('GeneralPay', '用户ID'),
            'order_id' => Yii::t('GeneralPay', '订单ID'),
            'general_pay_money' => Yii::t('GeneralPay', '发起充值/交易金额'),
            'general_pay_actual_money' => Yii::t('GeneralPay', '实际充值/交易金额'),
            'general_pay_source' => Yii::t('GeneralPay', '数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡'),
            'general_pay_source_name' => Yii::t('GeneralPay', '数据来源名称'),
            'general_pay_mode' => Yii::t('GeneralPay', '交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿'),
            'general_pay_status' => Yii::t('GeneralPay', '状态：0=失败,1=成功'),
            'general_pay_transaction_id' => Yii::t('GeneralPay', '第三方交易流水号'),
            'general_pay_eo_order_id' => Yii::t('GeneralPay', '商户ID(第三方交易)'),
            'general_pay_memo' => Yii::t('GeneralPay', '备注'),
            'general_pay_is_coupon' => Yii::t('GeneralPay', '是否返券'),
            'admin_id' => Yii::t('GeneralPay', '管理员ID'),
            'general_pay_admin_name' => Yii::t('GeneralPay', '管理员名称'),
            'worker_id' => Yii::t('GeneralPay', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('GeneralPay', '办卡人ID'),
            'general_pay_handle_admin_id' => Yii::t('GeneralPay', '办卡人名称'),
            'general_pay_verify' => Yii::t('GeneralPay', '支付验证'),
            'created_at' => Yii::t('GeneralPay', '创建时间'),
            'updated_at' => Yii::t('GeneralPay', '更新时间'),
            'is_del' => Yii::t('GeneralPay', '删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return GeneralPayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeneralPayQuery(get_called_class());
    }

    /**
     * @param $condition
     * @param $fileds
     * @return array|GeneralPay|null
     */
    public static function getGeneralPayByInfo($condition,$fileds = '*')
    {
        return GeneralPay::find()->select($fileds)->where($condition)->asArray()->one();
    }

}
