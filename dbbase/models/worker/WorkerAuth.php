<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_auth}}".
 *
 * @property integer $worker_id
 * @property integer $worker_auth_status
 * @property string $worker_auth_failed_reason
 * @property integer $worker_basic_training_status
 * @property integer $worker_ontrial_status
 * @property string $worker_ontrial_failed_reason
 * @property integer $worker_onboard_status
 * @property string $worker_onboard_failed_reason
 * @property integer $worker_rising_training_status
 */
class WorkerAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_auth_status', 'worker_basic_training_status', 'worker_ontrial_status', 'worker_onboard_status', 'worker_upgrade_training_status'], 'integer'],
            [['worker_auth_failed_reason', 'worker_ontrial_failed_reason', 'worker_onboard_failed_reason'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'worker_id' => Yii::t('app', 'Worker ID'),
            'worker_auth_status' => Yii::t('app', '阿姨审核状态(0审核中,1审核通过2审核不通过)'),
            'worker_auth_failed_reason' => Yii::t('app', '审核不通过原因'),
            'worker_basic_training_status' => Yii::t('app', '阿姨基础培训(0培训中1培训通过2培训不通过)'),
            'worker_ontrial_status' => Yii::t('app', '阿姨试工状态(0试工中1试工通过2试工不通过)'),
            'worker_ontrial_failed_reason' => Yii::t('app', '试工不通过原因'),
            'worker_onboard_status' => Yii::t('app', '阿姨上岗状态(0上岗)'),
            'worker_onboard_failed_reason' => Yii::t('app', 'Worker Onboard Failed Reason'),
            'worker_upgrade_training_status' => Yii::t('app', '阿姨晋升培训状态(0培训中1培训通过2培训不通过)'),
        ];
    }
}
