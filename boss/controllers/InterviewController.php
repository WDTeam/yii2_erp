<?php

namespace boss\controllers;

use Yii;
use boss\models\Interview;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use boss\models\Auth;
use boss\models\AuthSearch;
use common\models\Sms;

/**
 * InterviewController implements the CRUD actions for Interview model.
 */
class InterviewController extends Controller
{
        /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'complaint'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','exam','oprexam','test','signed','view','create','update','delete','sendsms','examxc','testwork','savetest'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionComplaint($id){
        die($id);
        //return $this->render('');
    }
    
    /**
     * 岗前学习
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $id = empty($id) ? 'index' : $id;
        $m = Yii::$app->request->post();
        $fd = !empty($m['fd']) ? trim($m['fd']) : '';
        $conditions = !empty($m['fd']) ? ['or', ['like', 'username', $m['fd']],['like', 'mobile', $m['fd']],['like', 'idnumber', $m['fd']]] : null;
        if($id == 'exam'){
            if($conditions == null){
                $conditions = ['study_status'=>4];
            }else{
                $conditions = ['and', ['study_status'=>4], $conditions];
            }
        }
        if($id == 'oprexam'){
            if($conditions == null){
                $conditions = ['exam_result'=>1];
            }else{
                $conditions = ['and', ['exam_result'=>1], $conditions];
            }
        }
        if($id == 'test'){ 
            if($conditions == null){
                $conditions = ['operation_score'=>1];
            }else{
                $conditions = ['and', ['operation_score'=>1], $conditions];
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Interview::_find($conditions),
        ]);
        
        
        return $this->render($id, [
            'dataProvider' => $dataProvider,
            'fd' => $fd,
        ]);
    }
    
    /**
     * 岗前学习
     * @return mixed
     */
    public function actionSearch($id = null)
    {
        empty($id) ? $id = 'index' : '';
        $m = Yii::$app->request->post();
        $fd = !empty($m['fd']) ? trim($m['fd']) : '';
        $conditions = !empty($m['fd']) ? ['or', ['like', 'username', $m['fd']],['like', 'mobile', $m['fd']],['like', 'idnumber', $m['fd']]] : null;
        if($id == 'exam'){
            if($conditions == null){
                $conditions = ['study_status'=>4];
            }else{
                $conditions = ['and', 'study_status'=>4, $conditions];
            }
        }
        if($id == 'oprexam'){
            if($conditions == null){
                $conditions = ['exam_result'=>1];
            }else{
                $conditions = ['and', 'exam_result'=>1, $conditions];
            }
        }
        if($id == 'test'){
            if($conditions == null){
                $conditions = ['operation_score'=>1];
            }else{
                $conditions = ['and', 'operation_score'=>1, $conditions];
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Interview::_find($conditions),
        ]);
        return $this->render($id, [
            'dataProvider' => $dataProvider,
            'fd' => $fd,
        ]);
    }
    
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Interview();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionSendsms($id){
        $m = Yii::$app->request->post();
        $model = $this->findModel($id);
        $fed = explode('-', $m['fed']);
        $fed = $fed[0].'年'.$fed[1].'月'.$fed[2].'日';
        $txt = '亲爱的'.$model->username.'阿姨您好，恭喜您通过岗前学习，请您于'.$fed.$m['fet'].',前往'.$m['fep'].'进行面试。前来面试时请准备好身份证、健康证、智能手机。如有疑问请联系客服：400-6767636。';
        $tel = $model->mobile;
        if(!empty($tel)){
            $result = Sms::sendSms($tel,$txt);
            if($result){
                $model->notice_status = '1';
                $model->save(false);
                echo 'success';
            }else{
                echo 'fail';
            }
        }else{
            echo 'fail';
        }
    }


    public function actionExamxc($id){
        $m = Yii::$app->request->post();
        $model = $this->findModel($id);
        if($m['mode'] == '1'){
            $txt = '亲爱的'.$model->username.'阿姨您好，欢迎您进行现场在线考试，点击链接进入考试，http://test.train.1jiajie.com/study/exam-answer，祝您顺利通过。如有疑问请联系客服：400-6767636。';
            $tel = $model->mobile;
            $result = Sms::sendSms($tel,$txt);
            if($result){
                $model->online_exam_mode = $m['mode'];
                $model->save(false);
                echo 'success';
            }else{
                echo 'fail';
            }
        }else{
            $model->online_exam_mode = $m['mode'];
            $model->save(false);
            echo 'success';
        }
    }
    
    public function actionTestwork($id){
        $m = Yii::$app->request->post();
        $model = $this->findModel($id);
        $model->test_status = $m['tw'];
        $model->operation_score = $m['ep'];
        $model->operation_time = time();
        if($model->test_status == '1'){
            $model->test_time = time();
        }else{
            $model->test_time = 0;
        }
        $model->save(false);
        echo 'success';
    }
    
    public function actionSavetest($id){
        $m = Yii::$app->request->post();
        $model = $this->findModel($id);
        $model->test_situation = $m['ts'];
        $model->test_result = $m['tr'];
        $model->save(false);
        echo 'success';
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interview::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
//    protected function sendSms($tel, $data, $num = 0, $is = 0,$type = 3){
//        $url = 'http://test.web.1jiajie.com/mobileapinew8/packageSmsSendSms?type='.$type.'&tel='.$tel.'&num='.$num.'&param[0]='.$data.'&is='.$is;
//        
//        $d = file_get_contents($url);
//        return $d;
//    }
}
