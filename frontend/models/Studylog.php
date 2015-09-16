<?php

namespace frontend\models;

use Yii;
//use frontend\models\User;

class Studylog extends \common\models\Studylog
{
    public static function saveLog($uid, $vid, $status = 1, $classify_id = 1){
        $studyLog = Studylog::find()->where(['and',['=', 'student_id', $uid],['=', 'courseware_id', $vid]])->one();
        if(empty($studyLog)){
            $studyLog = new Studylog();
            $studyLog->student_id = $uid;
            $studyLog->courseware_id = $vid;
        }
        $studyLog->start_time = time();
        $studyLog->status = $status;
        $studyLog->classify_id = $classify_id;
        return $studyLog->save(false);
    }
}
