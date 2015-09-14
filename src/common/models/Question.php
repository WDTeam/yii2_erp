<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%question}}".
 *
 * @property string $id
 * @property string $title
 * @property integer $courseware_id
 * @property string $options
 * @property integer $is_multi
 * @property string $correct_options
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%question}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'options', 'correct_options'], 'required'],
            [['courseware_id', 'is_multi'], 'integer'],
            [['options'], 'string'],
            [['title', 'correct_options'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '题目'),
            'courseware_id' => Yii::t('app', '课件ID'),
            'options' => Yii::t('app', '答案选项集.JSON'),
            'is_multi' => Yii::t('app', '是否多选。0单选，1多选'),
            'correct_options' => Yii::t('app', '正确答案，逗号分隔。eg:A,B'),
        ];
    }
    /**
     * 打乱选项列表
     */
    public function question_shuffle($array)
    {
        $ary_keys = array_keys($array);
        $ary_values = array_values($array);
        shuffle($ary_keys);
        $new_args = [];
        foreach($ary_keys as $key) {
            $new_args[$key] = $array[$key];
        }
        return $new_args;
    }
    public function getOptions()
    {
        $nitem = [];
        $items = explode("\n", $this->options);
        foreach ($items as $item){
            $key = substr($item, 0, 1);
            $value = mb_substr($item, 2, null, 'utf8');
            $nitem[$key] = $value;
        }
        return $nitem? $nitem:[];
    }
}
