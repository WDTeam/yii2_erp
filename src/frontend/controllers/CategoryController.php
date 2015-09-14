<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Category;
use yii\web\Controller;
//use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Question;
use frontend\models\Answerlog;
//use common\models\Studylog;
use common\models\Courseware;
use frontend\models\User;
use frontend\models\Studylog;
//use yii\base\Object;
//use yii\filters\AccessControl;
//use yii\web\HttpException;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
   


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
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
 
    /**
     * 服务列表
     * @return mixed
     */
    public function actionIndex()
    {
        $sql = 'SELECT cateid,catename,description,
                (SELECT count(1) FROM et_studylog s WHERE s.classify_id=c.cateid AND s.student_id='.Yii::$app->user->identity->id.' AND s.status=3) as passnum,   
                (SELECT count(1) FROM et_studylog s WHERE s.classify_id=c.cateid AND s.student_id='.Yii::$app->user->identity->id.' AND s.status!=3) as nopassnum,
                (SELECT count(1) FROM et_courseware c WHERE c.classify_id=c.cateid) as cnum
                FROM et_category c WHERE cateid > 1';
//                (SELECT count(1) FROM et_answerlog a WHERE c.cateid=a.classify_id AND answerer_id='.Yii::$app->user->identity->id.' AND is_correct=0) as studystatus, 
//                (SELECT count(1) FROM et_answerlog a1 WHERE c.cateid=a1.classify_id AND answerer_id='.Yii::$app->user->identity->id.') as studynum 
                
//        echo $sql;exit;
        $dataProvider = Category::findBySql($sql)->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
        /*
         *播放视频
        */
    public function actionService($id, $order_number = 0)
    {
        $category = Category::findOne($id);
        $is_mobile = Yii::$app->devicedetect->isMobile();
//        $studylog = Studylog::find()->where([])
        // 保存学习状态为：学习中
//        User::findOne(Yii::$app->user->identity->id)->saveStudyStatus(2);
//        
        $sql = 'SELECT c.* FROM et_courseware c 
                LEFT JOIN et_studylog s ON s.courseware_id=c.id 
                WHERE c.classify_id=\''.$category->cateid.'\' AND c.order_number>='.$order_number.' AND (s.status IS NULL OR s.status != 3) 
                ORDER BY c.order_number ASC LIMIT 0,1';
//        echo $sql;
        $courseware = Courseware::findBySql($sql)->one();
        if(empty($courseware)){
            $courseware = Courseware::find()->where(['classify_id'=>$category->cateid])->andWhere(['>=','order_number', $order_number])->orderby('order_number ASC')->one();
        }
//        echo $courseware->id;
        if(empty($courseware)){
            return $this->render(
                    'notservice',
                    ['category' => $category]);
        }
        Studylog::saveLog(Yii::$app->user->identity->id, $courseware->id, 1, $id);
        return $this->render('service',[
            'category' => $category,
            'courseware'=>$courseware,
            'is_mobile'=>$is_mobile
        ]);
    }
    
    /**
     * 岗前培训答题
     */
    public function actionPreServiceAnswer($courseware_id)
    {   
        $courseware = Courseware::findOne($courseware_id);
        //课件的问题列表
        $questions = Question::find()->where(['courseware_id'=>$courseware_id])->all();
        if(empty($questions)){
            return $this->render('noanswer',['courseware' => $courseware]);
        }
        return $this->render('pre_service_answer',[
            'questions'=>$questions,
            'courseware' => $courseware
        ]);
    }
    
    /**
     * 提交用户试题答案，并判断是否正确，以及得分
     */
    public function actionSaveServiceAnswer(){ 
        $p = Yii::$app->request->post();
        $courseware_id = $p['courseware_id'];
        $answer_ids = isset($p['answer_ids'])?$p['answer_ids']:[];
        $courseware = Courseware::findOne($courseware_id);
        $order_number = $courseware->order_number;
        $classify_id = $courseware->classify_id;
        $category = Category::findOne($classify_id);
        $answer_options = isset($p['answer_options'])?$p['answer_options']:[];
        $questions = Question::find()->where(['courseware_id'=>$courseware_id])->all();
        $studylog = Studylog::findOne(['student_id'=>\Yii::$app->user->identity->id,'courseware_id'=>$courseware_id,'classify_id' => $courseware->classify_id]);
        $errorNum = 0;
        
        foreach ($answer_ids as $key=>$id){
            $question = $questions[$key];
            $answer_option = $this->pAnswer(isset($answer_options[$key])?$answer_options[$key]:null);
            $is_correct = $question->correct_options==$answer_option;
//            $is_correct = $question->correct_options==$answer_option?1:0;
            $errorNum += !$is_correct;
            Answerlog::saveLog(Yii::$app->user->identity->id, Yii::$app->user->identity->username, $question->id, $answer_option, $is_correct, $classify_id);
        }
        // 考试不通过
        if($errorNum > 0){
            $studylog->status = 2;
            $studylog->save(false);
            // 保存学习状态为：不通过
            User::findOne(\Yii::$app->user->identity->id)->saveStudyStatus(3);
//            \Yii::$app->session->setFlash('default', '考试不通过，请重新学习');
            return $this->redirect(['category/exampass', 'id'=>$courseware_id, 'errorNum' => $errorNum]);
        }
        $studylog->status = 3;
        $studylog->save(false);
        // 下一个课件
        $nextCourseware = Courseware::find()->andWhere(['>','order_number',$order_number])->andWhere(['=','classify_id',$classify_id])->one();
        if(empty($nextCourseware)){
            if(empty($studylog->end_time)){
                $studylog->end_time = time();
                $studylog->save(false);
            }
            return $this->redirect(['category/exampass', 'errorNum' => 'completed', 'id'=>$courseware_id]);
//            
        }else{
            return $this->redirect(['category/exampass', 'id'=>$nextCourseware->id]);
//            return $this->redirect(['category/service','courseware_id'=>$courseware->id]);
        }
         // 提交答案
        
    }
    
    private function pAnswer($answer_option){
        if(is_array($answer_option)){
            sort($answer_option);
            $answer_option = str_replace(' ','',implode(',', $answer_option));
            $answer_option = str_replace('　','',$answer_option);
        }
        return $answer_option;
    }

    public function actionExampass($id=0){
        $courseware = Courseware::findOne($id);
        $category = Category::findOne($courseware->classify_id);
        return $this->render('exampass',['errorNum' => Yii::$app->request->get('errorNum'), 'courseware' => $courseware, 'category' => $category]);
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

   public function actionAnswer($courseware_id, $order_number)
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
                    //User::findOne(\Yii::$app->user->identity->id)->saveStudyStatus(3);
                    //\Yii::$app->session->setFlash('default', '考试不通过，请重新学习');
                    return $this->redirect([
                        'study/pre-service',
                        'order_number'=>$order_number
                    ]);
                }
            }
            $studylog->status = 3;
            $studylog->save();
            // 下一个课件
//            $nextCourseware = Courseware::find()
//            ->andWhere(['>','order_number',$order_number])
//            ->andWhere(['=','classify_id',1])
//            ->one();
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
                    'category/pre-service',
                    'courseware_id'=>$nextCourseware,
                    'order_number'=>$nextCourseware->order_number
                ]);
            }
        }
//        elseif(empty($studylog->end_time)){
//            // 学习结束时间
//            $studylog->end_time = time();
//            $studylog->save();
//        }
        return $this->render('answer',[
            'questions'=>$questions
        ]);
    }
   
}
