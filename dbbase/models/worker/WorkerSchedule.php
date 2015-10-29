<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_schedule}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_schedule_start_hour
 * @property integer $worker_schedule_end_hour
 * @property string $worker_schedule_date
 * @property integer $worker_schedule_week_day
 * @property integer $created_ad
 * @property integer $updated_ad
 */
class WorkerSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_schedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_schedule_start_date', 'worker_schedule_end_date', 'created_ad'], 'integer'],
            [['worker_schedule_timeline'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨工作安排表自增id'),
            'worker_id' => Yii::t('app', '主表阿姨id'),
            'worker_schedule_start_date' => Yii::t('app', '开始的小时'),
            'worker_schedule_end_date' => Yii::t('app', '结束的小时'),
            'worker_schedule_timeline' => Yii::t('app', '工作安排日期'),
            'created_ad' => Yii::t('app', '创建时间'),
        ];
    }
}
