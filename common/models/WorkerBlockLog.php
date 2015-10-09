<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%worker_block_log}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_block_id
 * @property integer $worker_block_operate_type
 * @property integer $worker_block_operate_id
 * @property string $worker_block_operate_bak
 * @property integer $worker_block_operate_time
 */
class WorkerBlockLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_block_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_block_id', 'worker_id','worker_block_operate_type', 'worker_block_operate_id', 'worker_block_operate_time'], 'integer'],
            [['worker_block_operate_bak'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'worker_id'=>Yii::t('app', 'Worker ID'),
            'worker_block_id' => Yii::t('app', 'Worker Block ID'),
            'worker_block_operate_type' => Yii::t('app', 'Worker Block Operate Type'),
            'worker_block_operate_id' => Yii::t('app', 'Worker Block Operate ID'),
            'worker_block_operate_bak' => Yii::t('app', 'Worker Block Operate Bak'),
            'worker_block_operate_time' => Yii::t('app', 'Worker Block Operate Time'),
        ];
    }
}
