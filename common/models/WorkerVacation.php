<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%worker_vacation}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_vacation_start
 * @property integer $worker_vacation_finish
 * @property integer $worker_vacation_type
 * @property string $worker_vacation_extend
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 */
class WorkerVacation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_vacation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_vacation_start', 'worker_vacation_finish', 'worker_vacation_type', 'created_ad', 'updated_ad', 'admin_id'], 'integer'],
            [['worker_vacation_extend'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨请假时间表自增id'),
            'worker_id' => Yii::t('app', '主表阿姨id'),
            'worker_vacation_start' => Yii::t('app', '请假开始时间'),
            'worker_vacation_finish' => Yii::t('app', '请假结束时间'),
            'worker_vacation_type' => Yii::t('app', '阿姨请假类型 1休假 2事假'),
            'worker_vacation_extend' => Yii::t('app', '阿姨请假备注'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'admin_id' => Yii::t('app', '操作管理员id'),
        ];
    }
}
