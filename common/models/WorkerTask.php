<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%worker_task}}".
 *
 * @property integer $id
 * @property string $worker_task_name
 * @property integer $worker_task_start
 * @property integer $worker_task_end
 * @property integer $worker_type
 * @property integer $worker_rule_id
 * @property integer $worker_task_city_id
 * @property string $worker_task_description
 * @property string $worker_task_description_url
 * @property string $conditions
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_task_start', 'worker_task_end', 'worker_type', 'worker_rule_id', 'worker_task_city_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['conditions'], 'string'],
            [['worker_task_name', 'worker_task_description', 'worker_task_description_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'worker_task_name' => Yii::t('app', '任务名称'),
            'worker_task_start' => Yii::t('app', '任务开始时间'),
            'worker_task_end' => Yii::t('app', '任务结束时间'),
            'worker_type' => Yii::t('app', '阿姨角色'),
            'worker_rule_id' => Yii::t('app', '阿姨身份'),
            'worker_task_city_id' => Yii::t('app', '适用城市'),
            'worker_task_description' => Yii::t('app', '任务描述'),
            'worker_task_description_url' => Yii::t('app', '任务描述URL'),
            'conditions' => Yii::t('app', '任务条件(JSON)'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '是否逻辑删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return WorkerTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkerTaskQuery(get_called_class());
    }
}
