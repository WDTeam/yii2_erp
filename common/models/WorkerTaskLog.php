<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%worker_task_log}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_task_id
 * @property string $worker_task_name
 * @property integer $worker_task_start
 * @property integer $worker_task_end
 * @property integer $worker_task_is_done
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerTaskLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_task_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_task_id', 'worker_task_start', 'worker_task_end', 'worker_task_is_done', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['worker_task_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'worker_id' => Yii::t('app', '阿姨ID'),
            'worker_task_id' => Yii::t('app', '任务ID'),
            'worker_task_name' => Yii::t('app', '任务名称'),
            'worker_task_start' => Yii::t('app', '任务开始时间'),
            'worker_task_end' => Yii::t('app', '任务结束时间'),
            'worker_task_is_done' => Yii::t('app', '任务结束时间'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '是否逻辑删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return WorkerTaskLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkerTaskLogQuery(get_called_class());
    }
}
