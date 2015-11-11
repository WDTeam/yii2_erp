<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_task_logmeta}}".
 *
 * @property integer $id
 * @property integer $worker_task_id
 * @property integer $worker_tasklog_id
 * @property integer $worker_id
 * @property integer $worker_tasklog_condition
 * @property integer $worker_tasklog_value
 */
class WorkerTaskLogmeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_task_logmeta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_task_id', 'worker_tasklog_id', 'worker_id', 'worker_tasklog_condition'], 'integer'],
            [['worker_tasklog_value'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'worker_task_id' => Yii::t('app', '任务ID'),
            'worker_tasklog_id' => Yii::t('app', '任务记录ID'),
            'worker_id' => Yii::t('app', '阿姨ID'),
            'worker_tasklog_condition' => Yii::t('app', '条件索引'),
            'worker_tasklog_value' => Yii::t('app', '条件值'),
        ];
    }

    /**
     * @inheritdoc
     * @return WorkerTaskLogmetaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkerTaskLogmetaQuery(get_called_class());
    }
}
