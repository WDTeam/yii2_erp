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
    public function insertLog($param)
    {
        $param->data['payment_log_status'] = $this->statusBool($param->data['payment_log_status']);
        //写入文本日志
        $writeLog = array(
            'data' => $param->data['data']
        );

        $this->on('writeTextLog',[$this,'writeTextLog'],$writeLog);
        $this->trigger('writeTextLog');

        //渠道名称
        try{
            $param->data['pay_channel_name'] = OperationPayChannel::get_post_name($param->data['pay_channel_id']);
        }catch(Exception $e){
            $param->data['pay_channel_name'] = '未找到渠道';
        }
        //写入mongo数据库日志
        $this->mogonInsert($param->data);
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
        $path = !empty($param->data['path']) ? $param->data['path'] : '/tmp/boss_log/pay/'.date('Ym',time()).'/';
        is_dir($path) || mkdir($path,0777,true);

        //文件名称
        $filename = !empty($param->data['filename']) ? $param->data['filename'] : date('Y-m-d',time()).'.log';
        //写入数据
        $fullFileName = rtrim($path,'/').'/'.$filename;
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

}
