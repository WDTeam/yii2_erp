<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%answerlog}}".
 *
 * @property string $id
 * @property integer $answerer_id
 * @property string $answerer_name
 * @property integer $question_id
 * @property string $answer_options
 * @property string $create_time
 * @property integer $is_correct
 */
class Answerlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%answerlog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answerer_id', 'question_id', 'is_correct'], 'integer'],
            [['create_time'], 'required'],
            [['create_time'], 'safe'],
            [['answerer_name'], 'string', 'max' => 100],
            [['answer_options'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'answerer_id' => Yii::t('app', '回答者ID'),
            'answerer_name' => Yii::t('app', '回答者名字'),
            'question_id' => Yii::t('app', '题目ID'),
            'answer_options' => Yii::t('app', '用户答案'),
            'create_time' => Yii::t('app', '回答时间'),
            'is_correct' => Yii::t('app', '是否正确？0不正确，1正确'),
        ];
    }
}
