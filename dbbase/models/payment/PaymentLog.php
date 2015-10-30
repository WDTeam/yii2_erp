<?php

namespace dbbase\models\payment;

use dbbase\models\finance\FinancePayChannel;
use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%payment_log}}".
 *
 * @property integer $id
 * @property string $payment_log_price
 * @property string $payment_log_shop_name
 * @property string $payment_log_eo_order_id
 * @property string $payment_log_transaction_id
 * @property string $payment_log_status
 * @property integer $pay_channel_id
 * @property string $payment_log_json_aggregation
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_reconciliation
 */
class PaymentLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_log_price', 'payment_log_eo_order_id', 'payment_log_transaction_id', 'payment_log_status', 'payment_log_json_aggregation', 'created_at', 'updated_at'], 'required'],
            [['payment_log_price'], 'number'],
            [['payment_log_status_bool','pay_channel_id', 'created_at', 'updated_at', 'is_reconciliation'], 'integer'],
            [['payment_log_json_aggregation'], 'string'],
            [['payment_log_shop_name'], 'string', 'max' => 50],
            [['payment_log_eo_order_id', 'payment_log_status'], 'string', 'max' => 30],
            [['payment_log_transaction_id'], 'string', 'max' => 40],
            [['pay_channel_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * 日志记录
     * @param array $param
     */
    public function insertLog($param)
    {
        //写入文本日志
        $writeLog = array(
            'data' => $param->data['data']
        );

        $this->on('writeTextLog',[$this,'writeTextLog'],$writeLog);
        $this->trigger('writeTextLog');

        //渠道名称
        $param->data['pay_channel_name'] = FinancePayChannel::getPayChannelByName($param->data['pay_channel_id']);
        //写入数据库日志
        $this->attributes = $param->data;
        $this->insert(false);
    }

    /**
     * 写入日志
     * @param $path 目录
     * @param $filename 文件名称
     * @param $data 写入数据
     */
    public function writeTextLog($param)
    {

        //创建目录
        $path = !empty($param->data['path']) ? $param->data['path'] : '/tmp/pay/';
        is_dir($path) || mkdir($path,0777,true);

        //文件名称
        $filename = !empty($param->data['filename']) ? $param->data['filename'] : date('Y-m-d',time()).'_pay.log';
        //写入数据
        $fullFileName = $path.$filename;
        file_put_contents($fullFileName,serialize($param->data['data']).'||',FILE_APPEND);
    }

    /**
     * 判断支付状态
     * @param $statusString 状态类型
     * @return int  1/支付成功 ， 0/支付失败
     */
    public function statusBool($statusString){
        $statusArr = [
            'TRADE_FINISHED',   //支付宝
            'TRADE_SUCCESS',    //支付宝
            '1',    //百付宝
            '0',    //微信APP
            'Success!',   //银联
            'SUCCESS',//微信
        ];
        $status = in_array($statusString,$statusArr) ? 1 : 0 ;
        return $status;
    }

    /**
     * 自动处理创建时间和修改时间
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_log_price' => Yii::t('app', '支付金额'),
            'payment_log_shop_name' => Yii::t('app', '商品名称'),
            'payment_log_eo_order_id' => Yii::t('app', '第三方订单ID'),
            'payment_log_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'payment_log_status_bool' => Yii::t('app', '状态数'),
            'payment_log_status' => Yii::t('app', '状态'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'pay_channel_name' => Yii::t('app', '支付渠道名称'),
            'payment_log_json_aggregation' => Yii::t('app', '记录数据集合'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_reconciliation' => Yii::t('app', '是否对账'),
        ];
    }
}
