<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%worker_block}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_block_type
 * @property string $worker_block_reason
 * @property integer $worker_block_start
 * @property integer $worker_block_finish
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
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
            [['worker_id', 'worker_block_start_time', 'worker_block_finish_time', 'created_ad', 'updated_ad', 'admin_id'], 'integer'],
            [['worker_block_reason'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨封号id'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'worker_block_reason' => Yii::t('app', '阿姨封号原因'),
            'worker_block_start_time' => Yii::t('app', '封号开始时间'),
            'worker_block_finish_time' => Yii::t('app', '封号结束时间'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'admin_id' => Yii::t('app', '管理员id'),
        ];
    }
}
