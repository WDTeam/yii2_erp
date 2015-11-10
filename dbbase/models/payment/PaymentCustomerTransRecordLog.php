<?php

namespace dbbase\models\payment;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%payment_customer_trans_record_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property integer $order_channel_id
 * @property integer $payment_customer_trans_record_order_channel
 * @property integer $pay_channel_id
 * @property integer $payment_customer_trans_record_pay_channel
 * @property integer $payment_customer_trans_record_mode
 * @property integer $payment_customer_trans_record_mode_name
 * @property string $payment_customer_trans_record_promo_code_money
 * @property string $payment_customer_trans_record_coupon_money
 * @property string $payment_customer_trans_record_cash
 * @property string $payment_customer_trans_record_pre_pay
 * @property string $payment_customer_trans_record_online_pay
 * @property string $payment_customer_trans_record_online_balance_pay
 * @property string $payment_customer_trans_record_service_card_on
 * @property string $payment_customer_trans_record_service_card_pay
 * @property string $payment_customer_trans_record_service_card_current_balance
 * @property string $payment_customer_trans_record_service_card_befor_balance
 * @property string $payment_customer_trans_record_compensate_money
 * @property string $payment_customer_trans_record_refund_money
 * @property string $payment_customer_trans_record_order_total_money
 * @property string $payment_customer_trans_record_total_money
 * @property string $payment_customer_trans_record_current_balance
 * @property string $payment_customer_trans_record_befor_balance
 * @property string $payment_customer_trans_record_transaction_id
 * @property string $payment_customer_trans_record_remark
 * @property string $payment_customer_trans_record_verify
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class PaymentCustomerTransRecordLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_customer_trans_record_log}}';
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
     * 日志记录
     * @param array $param
     */
    public function insertLog($param)
    {
        //写入文本日志
        $writeLog = array(
            'path' => '/tmp/log/transaction_record/'.date('Ym',time()),
            'data' => $param->data,
            'filename' => date('Y-m-d',time()).'.log'
        );

        $this->on('writeTextLog',[$this,'writeTextLog'],$writeLog);
        $this->trigger('writeTextLog');

        //makeSign
        $param->data['payment_customer_trans_record_verify'] = self::sign($param->data);

        //写入数据库日志
        try{
            $mongo = \Yii::$app->mongodb;
            $collection = $mongo->getCollection('payment_log');
            $data = $param->data;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['create_time'] = time();
            return $collection->insert($data);
        }catch(Exception $e){}
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
        $path = !empty($param->data['path']) ? $param->data['path'] : '/tmp/boss_log/trans_record/'.date('Ym',time());
        is_dir($path) || mkdir($path,0777,true);

        //文件名称
        $filename = !empty($param->data['filename']) ? $param->data['filename'] : date('Y-m-d',time()).'.log';
        //写入数据
        $fullFileName = rtrim($path,'/').'/'.$filename;
        file_put_contents($fullFileName,serialize($param->data['data']).'||',FILE_APPEND);
    }

    /**
     * 签名
     */
    public static function sign($data)
    {
        ksort($data);
        //加密字符串
        $str='1jiajie.com';
        //排除的字段
        $notArray = ['id','payment_customer_trans_record_verify','created_at','updated_at'];
        //加密签名
        foreach( $data as $name=>$val )
        {
            $value = is_numeric($val) ? (int)$val : $val;
            if( !empty($value) && !in_array($name,$notArray))
            {
                if(is_numeric($value) && $value < 1) continue;
                $str .= $value;
            }
        }
        //return $str;
        return md5(md5($str).'1jiajie.com');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','customer_id', 'payment_customer_trans_record_order_channel', 'pay_channel_id', 'payment_customer_trans_record_pay_channel', 'payment_customer_trans_record_mode_name', 'payment_customer_trans_record_refund_money', 'payment_customer_trans_record_verify'], 'required'],
            [['customer_id', 'order_channel_id',  'pay_channel_id', 'payment_customer_trans_record_mode',  'created_at', 'updated_at'], 'integer'],
            [['payment_customer_trans_record_coupon_money', 'payment_customer_trans_record_cash', 'payment_customer_trans_record_pre_pay', 'payment_customer_trans_record_online_pay', 'payment_customer_trans_record_online_balance_pay', 'payment_customer_trans_record_service_card_pay','payment_customer_trans_record_service_card_current_balance','payment_customer_trans_record_service_card_befor_balance', 'payment_customer_trans_record_refund_money',  'payment_customer_trans_record_order_total_money', 'payment_customer_trans_record_total_money', 'payment_customer_trans_record_current_balance', 'payment_customer_trans_record_befor_balance','payment_customer_trans_record_compensate_money'], 'number'],
            [['payment_customer_trans_record_service_card_on'], 'string', 'max' => 30],
            [['payment_customer_trans_record_transaction_id'], 'string', 'max' => 40],
            [['order_code','order_batch_code'], 'string', 'max' => 64],
            [['payment_customer_trans_record_remark'], 'string', 'max' => 255],
            [['payment_customer_trans_record_verify'], 'string', 'max' => 320],
            [['customer_id','order_channel_id','pay_channel_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', '用户ID'),
            'order_id' => Yii::t('app', '订单ID'),
            'order_channel_id' => Yii::t('app', '订单渠道'),
            'payment_customer_trans_record_order_channel' => Yii::t('app', '订单渠道名称'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'payment_customer_trans_record_pay_channel' => Yii::t('app', '支付渠道名称'),
            'payment_customer_trans_record_mode' => Yii::t('app', '交易方式:1消费,2=充值,3=退款,4=赔偿'),
            'payment_customer_trans_record_mode_name' => Yii::t('app', '交易方式名称'),
            'payment_customer_trans_record_coupon_money' => Yii::t('app', '优惠券金额'),
            'payment_customer_trans_record_cash' => Yii::t('app', '现金支付'),
            'payment_customer_trans_record_pre_pay' => Yii::t('app', '预付费金额（第三方）'),
            'payment_customer_trans_record_online_pay' => Yii::t('app', '在线支付'),
            'payment_customer_trans_record_online_balance_pay' => Yii::t('app', '在线余额支付'),
            'payment_customer_trans_record_service_card_on' => Yii::t('app', '服务卡号'),
            'payment_customer_trans_record_service_card_pay' => Yii::t('app', '服务卡支付'),
            'payment_customer_trans_record_service_card_current_balance' => Yii::t('app', '服务卡当前余额'),
            'payment_customer_trans_record_service_card_befor_balance' => Yii::t('app', '服务卡之前余额'),
            'payment_customer_trans_record_compensate_money' => Yii::t('app', '补偿金额'),
            'payment_customer_trans_record_refund_money' => Yii::t('app', '退款金额'),
            'payment_customer_trans_record_order_total_money' => Yii::t('app', '订单总额'),
            'payment_customer_trans_record_total_money' => Yii::t('app', '交易总额'),
            'payment_customer_trans_record_current_balance' => Yii::t('app', '当前余额'),
            'payment_customer_trans_record_befor_balance' => Yii::t('app', '之前余额'),
            'payment_customer_trans_record_transaction_id' => Yii::t('app', '交易流水号'),
            'payment_customer_trans_record_remark' => Yii::t('app', '备注'),
            'payment_customer_trans_record_verify' => Yii::t('app', '验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '删除'),
        ];
    }

}
