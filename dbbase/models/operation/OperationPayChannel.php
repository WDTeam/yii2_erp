<?php
/**
* 支付渠道数据映射
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace dbbase\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_pay_channel}}".
 *
 * @property integer $id
 * @property string $operation_pay_channel_name
 * @property integer $operation_pay_channel_type
 * @property string $operation_pay_channel_rate
 * @property integer $system_user_id
 * @property string $system_user_name
 * @property integer $create_time
 * @property integer $is_del
 */
class OperationPayChannel extends \yii\db\ActiveRecord
{
	const PAY_CHANNEL_SERVICE= 1; //服务卡支付
	const PAY_CHANNEL_CASH= 2; //现金支付
 	const PAY_CHANNEL_ALIPAY= 7; //支付宝支付
	const PAY_CHANNEL_BAIDU = 8; //百度钱包支付
	const PAY_CHANNEL_POP_PLAN = 9; //第三方团购预收
	const PAY_CHANNEL_WEIXI = 10; //微信支付
	const PAY_CHANNEL_UNIONPAY=12; //银联支付
	const PAY_CHANNEL_TENPAY=13; //财付通支付
	const PAY_CHANNEL_SURPLUS=20; //余额支付
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_pay_channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_pay_channel_type', 'system_user_id', 'create_time', 'is_del'], 'integer'],
            [['operation_pay_channel_name'], 'string', 'max' => 50],
            [['operation_pay_channel_rate'], 'string', 'max' => 6],
            [['system_user_name'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'operation_pay_channel_name' => Yii::t('app', '支付渠道名称'),
            'operation_pay_channel_type' => Yii::t('app', '支付渠道类别'),
            'operation_pay_channel_rate' => Yii::t('app', '比率'),
            'system_user_id' => Yii::t('app', '添加人id'),
            'system_user_name' => Yii::t('app', '添加人名称'),
            'create_time' => Yii::t('app', '增加时间'),
            'is_del' => Yii::t('app', '0 正常 1 删除'),
        ];
    }

  
   
}
