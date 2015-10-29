<?php

namespace common\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_device}}".
 *
 * @property integer $worker_id
 * @property double $worker_device_curr_lng
 * @property double $worker_device_curr_lat
 * @property string $worker_device_client_version
 * @property string $worker_device_version_name
 * @property string $worker_device_token
 * @property string $worker_device_mac_addr
 * @property string $worker_device_login_ip
 * @property integer $worker_device_login_time
 * @property integer $created_ad
 * @property integer $updated_ad
 */
class WorkerDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_device}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_device_curr_lng', 'worker_device_curr_lat'], 'number'],
            [['worker_device_login_time', 'created_ad', 'updated_ad'], 'integer'],
            [['worker_device_client_version', 'worker_device_version_name', 'worker_device_login_ip'], 'string', 'max' => 20],
            [['worker_device_token'], 'string', 'max' => 100],
            [['worker_device_mac_addr'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'worker_id' => Yii::t('app', '阿姨Id'),
            'worker_device_curr_lng' => Yii::t('app', '阿姨客户端当前经度'),
            'worker_device_curr_lat' => Yii::t('app', '阿姨客户端当前纬度'),
            'worker_device_client_version' => Yii::t('app', 'Worker Device Client Version'),
            'worker_device_version_name' => Yii::t('app', 'Worker Device Version Name'),
            'worker_device_token' => Yii::t('app', '阿姨客户端设备号'),
            'worker_device_mac_addr' => Yii::t('app', '阿姨登录客户端mac地址'),
            'worker_device_login_ip' => Yii::t('app', '阿姨最新登录ip地址'),
            'worker_device_login_time' => Yii::t('app', '阿姨登录客户端最新时间'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
        ];
    }
}
