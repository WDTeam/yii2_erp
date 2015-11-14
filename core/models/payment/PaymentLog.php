<?php

namespace core\models\payment;

use core\models\operation\OperationPayChannel;

use Yii;
use yii\base\Exception;
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
class PaymentLog extends \dbbase\models\payment\PaymentLog
{

    /**
     * 日志记录
     * @param array $param
     */
    public function insertLog($data)
    {
        //转换中文数据
        $data['payment_log_status_bool'] = $this->statusBool($data['payment_log_status']);
        try{
        $data['pay_channel_name'] = !empty($data['pay_channel_id']) ? OperationPayChannel::get_post_name($data['pay_channel_id']) : '未找到渠道';
        }catch(Exception $e){}
        //写入文本日志
        $writeLog = array(
            'data' => $data
        );
        $this->writeTextLog($writeLog);

        //写入mongo数据库日志
        $this->payment_log_data = $data;
        $this->trigger(self::EVENT_MONGO_INSERT);
    }

    /**
     * 写入日志
     * @param $path 目录
     * @param $filename 文件名称
     * @param $data 写入数据
     */
    public function writeTextLog($data)
    {
        //创建目录
        $path = !empty($data['path']) ? $data['path'] : '/tmp/boss_log/pay/'.date('Ym',time()).'/';
        is_dir($path) || mkdir($path,0777,true);

        //文件名称
        $filename = !empty($data['filename']) ? $data['filename'] : date('Y-m-d',time()).'.log';
        //写入数据
        $fullFileName = rtrim($path,'/').'/'.$filename;
        file_put_contents($fullFileName,serialize($data['data']).'||',FILE_APPEND);
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
        return in_array($statusString,$statusArr) ? 1 : 0 ;
    }

}
