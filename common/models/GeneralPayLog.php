<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%general_pay_log}}".
 *
 * @property integer $id
 * @property string $general_pay_log_price
 * @property string $general_pay_log_shop_name
 * @property string $general_pay_log_eo_order_id
 * @property string $general_pay_log_transaction_id
 * @property string $general_pay_log_status
 * @property integer $pay_channel_id
 * @property string $general_pay_log_json_aggregation
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class GeneralPayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%general_pay_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['general_pay_log_price', 'general_pay_log_eo_order_id', 'general_pay_log_transaction_id', 'general_pay_log_status', 'general_pay_log_json_aggregation', 'created_at', 'updated_at'], 'required'],
            [['general_pay_log_price'], 'number'],
            [['pay_channel_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['general_pay_log_json_aggregation'], 'string'],
            [['general_pay_log_shop_name'], 'string', 'max' => 50],
            [['general_pay_log_eo_order_id', 'general_pay_log_status'], 'string', 'max' => 30],
            [['general_pay_log_transaction_id'], 'string', 'max' => 40]
        ];
    }

    /**
     * 插入交易记录
     * @param array $post
     */
    public function insertLog($post){

        //记录数据库日志
        $this->general_pay_log_price = $post['general_pay_log_price'];   //支付金额
        $this->general_pay_log_shop_name = $post['general_pay_log_shop_name'];   //商品名称
        $this->general_pay_log_eo_order_id = $post['general_pay_log_eo_order_id'];   //订单ID
        $this->general_pay_log_transaction_id = $post['general_pay_log_transaction_id'];   //交易流水号
        $this->general_pay_log_status_bool = $this->statusBool($post['general_pay_log_status_bool']);   //支付状态
        $this->general_pay_log_status = $post['general_pay_log_status'];   //支付状态
        $this->general_pay_log_json_aggregation = json_encode($post);
        $this->save(false);
    }

    /**
     * 写入日志
     * @param $path 目录
     * @param $filename 文件名称
     */
    public function writeLog($data,$path='/tmp/pay/',$filename=null){
        //创建目录
        is_dir($path) || mkdir($path,0777,true);
        //文件名称
        if(is_null($filename)) $filename = date('Y-m-d',time()).'_pay.log';
        //写入数据
        $fullFileName = $path.$filename;
        file_put_contents($fullFileName,json_encode($data).'||',FILE_APPEND);
    }

    /**
     * 判断支付状态
     * @param $statusString 状态类型
     * @return int  1/支付成功 ， 2/支付失败
     */
    private function statusBool($statusString){
        $statusArr = [
            'TRADE_FINISHED',   //支付宝
            'TRADE_SUCCESS',    //支付宝
            '1',    //百付宝
            'SUCCESS',    //微信
            '验签成功',    //银联
        ];
        return in_array($statusString,$statusArr) ? 1 : 0 ;
    }

    /**
     * 自动处理时间
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className()
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
            'general_pay_log_price' => Yii::t('app', '支付金额'),
            'general_pay_log_shop_name' => Yii::t('app', '商品名称'),
            'general_pay_log_eo_order_id' => Yii::t('app', '第三方订单ID'),
            'general_pay_log_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'general_pay_log_status' => Yii::t('app', '状态'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'general_pay_log_json_aggregation' => Yii::t('app', '记录数据集合'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '删除'),
        ];
    }
}
