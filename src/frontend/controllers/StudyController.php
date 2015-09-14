<?php
namespace frontend\controllers;

use Yii;
use common\models\Question;
use common\models\Answerlog;
use common\models\Studylog;
use common\models\Courseware;
use frontend\models\User;
use yii\base\Object;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\HttpException;

class StudyController extends \yii\web\Controller
{
    /**
     * 进入前验证权限
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(\Yii::$app->user->loginUrl);
        }
        $user = User::findOne(\Yii::$app->user->identity->id);
        if(empty($user->username) || empty($user->idnumber)){
            return $this->redirect(['user/information']);
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex($type=0)
    {
        return $this->render('index',[
            'type'=>$type
        ]);
    }
    /**
     * 岗前学习
     */
    public function actionPreService($order_number=0)
    {
        $is_mobile = \Yii::$app->devicedetect->isMobile();
        // 保存学习状态为：学习中
        User::findOne(\Yii::$app->user->identity->id)->saveStudyStatus(2);
        $courseware = Courseware::find()->where([
            'classify_id'=>1
        ])->andWhere(['>=','order_number', $order_number])
        ->orderBy('order_number ASC')->one();
        $studylog = Studylog::findOne([
            'student_id'=>\Yii::$app->user->identity->id,
            'courseware_id'=>$courseware->id
        ]);
        if(empty($studylog)){
            $studylog = new Studylog();
            $studylog->student_id = \Yii::$app->user->identity->id;
            $studylog->courseware_id = $courseware->id;
            $studylog->start_time = time();
            $studylog->status = 1;
            $studylog->save();
        }
        return $this->render('pre_service',[
            'order_number'=>$order_number,
            'courseware'=>$courseware,
            'is_mobile'=>$is_mobile
        ]);
    }
    /**
     * 不认同，放弃
     */
    public function actionAbandon($name)
    {
        return $this->render('abandon',[
            'name'=>$name
        ]);
    }
    /**
     * 岗前培训答题
     */
    public function actionPreServiceAnswer($courseware_id, $order_number)
    {   
        $studylog = Studylog::findOne([
            'student_id'=>\Yii::$app->user->identity->id,
            'courseware_id'=>$courseware_id
        ]);
        //课件的问题列表
        $questions = Question::find()->where([
            'courseware_id'=>$courseware_id
        ])->all();
        // 提交答案
        if(isset($_POST['answer_ids'])){
            $answer_options = isset($_POST['answer_options'])?$_POST['answer_options']:[];
            foreach ($_POST['answer_ids'] as $key=>$id){
                $question = $questions[$key];
                $answer_option = isset($answer_options[$key])?$answer_options[$key]:null;
                $model = new Answerlog([
                    'answerer_id' => \Yii::$app->user->identity->id,
                    'answerer_name' => \Yii::$app->user->identity->username,
                    'question_id' => $question->id,
                    'answer_options' => $answer_option,
                    'create_time' => time(),
                    'is_correct' => $question->correct_options==$answer_option?1:0
                ]);
                $model->save();
                // 考试不通过
                if($model->is_correct<1){
                    $studylog->status = 2;
                    $studylog->save();
                    // 保存学习状态为：不通过
                    User::findOne(\Yii::$app->user->identity->id)->saveStudyStatus(3);
                    \Yii::$app->session->setFlash('default', '考试不通过，请重新学习');
                    return $this->redirect([
                        'study/pre-service',
                        'order_number'=>$order_number
                    ]);
                }
            }
            $studylog->status = 3;
            $studylog->save();
            // 下一个课件
            $nextCourseware = Courseware::find()
            ->andWhere(['>','order_number',$order_number])
            ->andWhere(['=','classify_id',1])
            ->one();
            if(empty($nextCourseware)){
                // 保存学习状态为：通过
                User::findOne(\Yii::$app->user->identity->id)->saveStudyStatus(4);
                // 计算学习时长
                User::findOne(\Yii::$app->user->identity->id)->computeStudyTimeTotal();
                return $this->redirect([
                    'study/pass'
                ]);
            }else{
                return $this->redirect([
                    'study/pre-service',
                    'courseware_id'=>$nextCourseware,
                    'order_number'=>$nextCourseware->order_number
                ]);
            }
        }elseif(empty($studylog->end_time)){
            // 学习结束时间
            $studylog->end_time = time();
            $studylog->save();
        }
        return $this->render('pre_service_answer',[
            'questions'=>$questions
        ]);
    }
    /**
     * 考试通过
     */
    public function actionPass($isExam=false)
    {
        return $this->render('pass',['isExam'=>$isExam]);
    }
    /**
     * 现场考试,template借用 actionPreServiceAnswer
     */
    public function actionExamAnswer()
    {

        \Yii::$app->user->setReturnUrl(['study/exam-answer']);

        $user = User::findOne(\Yii::$app->user->identity->id);
        if(isset($_POST['answer_ids'])){
            $models = Question::find()->where(['id'=>$_POST['answer_ids']])->all();
            $correct_number = 0;
            $answer_options = isset($_POST['answer_options'])?$_POST['answer_options']:[];
            foreach ($_POST['answer_ids'] as $key=>$id){
                $question = $models[$key];
                $answer_option = isset($answer_options[$key])?$answer_options[$key]:null;
                // 题目通过
                if($question->correct_options==$answer_option){
                    $correct_number++;
                }
            }
            // 算分
            $online_exam_score = ($correct_number/count($_POST['answer_ids']))*100;
            $user->online_exam_time = time();
            $user->online_exam_score = $online_exam_score;
            $is_mobile = \Yii::$app->devicedetect->isMobile();
            $user->online_exam_mode = $is_mobile?1:2;
            $user->exam_result = $online_exam_score<80?2:1;
            if($user->save(false)==false){
                throw new HttpException(500, '保存学习成绩出错');
            }
            if($online_exam_score<50){
                \Yii::$app->session->setFlash('default', "成绩:{$online_exam_score};考试不通过，请重新学习");
                return $this->redirect([
                    'study/pre-service',
                    'order_number'=>0
                ]);
            }elseif ($online_exam_score<80){
                \Yii::$app->session->setFlash('default', "成绩:{$online_exam_score};考试不通过，请重新考试");
                return $this->refresh();
            }else{
                return $this->redirect([
                    'study/pass',
                    'isExam'=>1
                ]);
            }
        }
        $models = Question::find()
        ->leftjoin('{{%courseware}} as c', '{{%question}}.courseware_id=c.id && c.classify_id=1')
        ->where(['c.classify_id'=>1])->all();
        $question_keys = array_rand($models, 10);
        foreach ($question_keys as $key) {
            $questions[] = $models[$key];
        }
        return $this->render('pre_service_answer',[
            'questions'=>$questions,
            'title'=>'现场考试'
        ]);
    }
}