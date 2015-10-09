<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%studylog}}".
 *
 * @property string $id
 * @property integer $student_id
 * @property integer $courseware_id
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 */
class Studylog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%studylog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'courseware_id', 'status'], 'integer'],
            [['start_time', 'end_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', '用户ID'),
            'courseware_id' => Yii::t('app', '课件ID'),
            'start_time' => Yii::t('app', '开始时间'),
            'end_time' => Yii::t('app', '结束时间'),
            'status' => Yii::t('app', '状态 0:未开始 1:学习中 2:考试未通过 3:考试通过'),
        ];
    }
    
    public static $statuses = [
        '未开始 ',
        '学习中 ',
        '考试未通过 ',
        '考试通过'
    ];
    /**
     * 状态文本
     */
    public function getStatusLabel()
    {
        return self::$statuses[$this->status];
    }
}
