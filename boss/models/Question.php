<?php
namespace boss\models;
use yii;
class Question extends \common\models\Question
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '题目'),
            'courseware_id' => Yii::t('app', '课件ID'),
            'options' => Yii::t('app', '答案选项'),
            'is_multi' => Yii::t('app', '试题类型，请注意：0，是单选；1，是多选'),
            'correct_options' => Yii::t('app', '正确答案'),
        ];
    }
     public function rules()
    {
        return [
            [['title', 'options', 'correct_options'], 'required'],
            [['courseware_id', 'is_multi'], 'integer'],
            [['options'], 'string'],
            [['title', 'correct_options'], 'string', 'max' => 255],
            //[['correct_options'],'match','pattern'=>"/^[a-zA-Z!@#\$%\^&\*\(\)\{\}\[\]';\:\.,\?\/\+]$/"],
        ];
    }
}