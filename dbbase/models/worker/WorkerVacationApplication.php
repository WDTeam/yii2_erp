<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_vacation_application}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_vacation_application_start_time
 * @property integer $worker_vacation_application_end_time
 * @property integer $worker_vacation_application_type
 * @property integer $worker_vacation_application_approve_status
 * @property integer $worker_vacation_application_approve_time
 * @property integer $created_ad
 */
class WorkerVacationApplication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_vacation_application}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_vacation_application_start_time', 'worker_vacation_application_end_time', 'worker_vacation_application_approve_status', 'worker_vacation_application_approve_time', 'created_ad'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨请假申请表自增id'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'worker_vacation_application_start_time' => Yii::t('app', '阿姨申请请假开始时间'),
            'worker_vacation_application_end_time' => Yii::t('app', '阿姨申请请假结束时间'),
            'worker_vacation_application_approve_status' => Yii::t('app', '阿姨申请审核状态 0待审核1审核通过2审核不通过'),
            'worker_vacation_application_approve_time' => Yii::t('app', '审核操作时间'),
            'created_ad' => Yii::t('app', '创建时间'),
        ];
    }
}
