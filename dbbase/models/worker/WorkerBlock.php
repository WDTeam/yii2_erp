<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_block}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_block_start_time
 * @property integer $worker_block_finish_time
 * @property string $worker_block_reason
 * @property integer $worker_block_status
 * @property integer $created_ad
 * @property integer $updated_ad
 */
class WorkerBlock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_block}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id',  'worker_block_status', 'created_ad', 'updated_ad'], 'integer'],
            [['worker_id',   'worker_block_start_time', 'worker_block_finish_time'], 'required'],
            [['worker_block_reason'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'worker_id' => Yii::t('app', '阿姨ID'),
            'daterange' => Yii::t('app', '阿姨封号时间'),
            'worker_block_start_time' => Yii::t('app', '阿姨封号开始时间'),
            'worker_block_finish_time' => Yii::t('app', '阿姨封号结束时间'),
            'worker_block_reason' => Yii::t('app', '阿姨封号原因'),
            'worker_block_status' => Yii::t('app', '阿姨封号状态'),
            'created_ad' => Yii::t('app', 'Created Ad'),
            'updated_ad' => Yii::t('app', 'Updated Ad'),
        ];
    }

    public function getStartTime(){
        return date('Y-m-d',$this->worker_block_start_time);
    }
    public function getFinishTime(){
        return date('Y-m-d',$this->worker_block_finish_time);
    }
}
