<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ivrlog}}".
 *
 * @property integer $id
 * @property string $ivrlog_req_tel
 * @property string $ivrlog_req_app_id
 * @property string $ivrlog_req_sign
 * @property integer $ivrlog_req_timestamp
 * @property string $ivrlog_req_order_message
 * @property string $ivrlog_req_order_id
 * @property integer $ivrlog_res_result
 * @property string $ivrlog_res_unique_id
 * @property string $ivrlog_res_clid
 * @property string $ivrlog_res_description
 * @property string $ivrlog_cb_telephone
 * @property string $ivrlog_cb_order_id
 * @property integer $ivrlog_cb_press
 * @property integer $ivrlog_cb_result
 * @property integer $ivrlog_cb_post_type
 * @property string $ivrlog_cb_webcall_request_unique_id
 * @property integer $ivrlog_cb_time
 */
class Ivrlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ivrlog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ivrlog_req_timestamp', 'ivrlog_res_result', 'ivrlog_cb_press', 'ivrlog_cb_result', 'ivrlog_cb_post_type', 'ivrlog_cb_time'], 'integer'],
            [['ivrlog_req_order_message'], 'string'],
            [['ivrlog_req_tel'], 'string', 'max' => 100],
            [['ivrlog_req_app_id'], 'string', 'max' => 11],
            [['ivrlog_req_sign', 'ivrlog_req_order_id', 'ivrlog_res_unique_id', 'ivrlog_res_clid', 'ivrlog_res_description', 'ivrlog_cb_order_id'], 'string', 'max' => 255],
            [['ivrlog_cb_telephone'], 'string', 'max' => 30],
            [['ivrlog_cb_webcall_request_unique_id'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ivrlog_req_tel' => Yii::t('app', '电话号码'),
            'ivrlog_req_app_id' => Yii::t('app', 'app_id'),
            'ivrlog_req_sign' => Yii::t('app', '签名'),
            'ivrlog_req_timestamp' => Yii::t('app', '请求时间'),
            'ivrlog_req_order_message' => Yii::t('app', '消息'),
            'ivrlog_req_order_id' => Yii::t('app', '订单号'),
            'ivrlog_res_result' => Yii::t('app', '结果'),
            'ivrlog_res_unique_id' => Yii::t('app', '唯一标识'),
            'ivrlog_res_clid' => Yii::t('app', '回显号码'),
            'ivrlog_res_description' => Yii::t('app', '描述'),
            'ivrlog_cb_telephone' => Yii::t('app', '回调电话号码'),
            'ivrlog_cb_order_id' => Yii::t('app', '回调订单ID'),
            'ivrlog_cb_press' => Yii::t('app', '回调按键'),
            'ivrlog_cb_result' => Yii::t('app', '回调反馈码'),
            'ivrlog_cb_post_type' => Yii::t('app', '回调类型'),
            'ivrlog_cb_webcall_request_unique_id' => Yii::t('app', 'Ivrlog Cb Webcall Request Unique ID'),
            'ivrlog_cb_time' => Yii::t('app', '回调时间'),
        ];
    }
}
