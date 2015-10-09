<?php
namespace frontend\models;

use Yii;

class Answerlog extends \common\models\Answerlog{
    public static function saveLog($uid, $uname, $qid, $answer_option, $correct_options, $classify_id){
        $where = ['and', ['=', 'classify_id', $classify_id], ['=', 'answerer_id', $uid], ['=', 'question_id', $qid]];
        $model = Answerlog::find()->where($where)->one();
        if(empty($model)){
            $model = new Answerlog();
            $model->question_id = $qid;
            $model->classify_id = $classify_id;
            $model->answerer_id = $uid;
            $model->answerer_name = $uname;
        }
        $model->answer_options = $answer_option;
        $model->create_time = time();
        $model->is_correct = $correct_options;
        $model->save(false);
    }
}
