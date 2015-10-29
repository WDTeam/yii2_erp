<?php

namespace boss\controllers\system;

use Yii;
use boss\models\Signed;
use boss\models\Interview;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SignedController implements the CRUD actions for Signed model.
 */
class SignedController extends BaseAuthController
{
    public function behaviors()
    {
        return [
                'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Signed models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Signed::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Signed model.
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
     * Creates a new Signed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Signed();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Signed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new Signed();
        $data= Yii::$app->request->post();
        //Interview::findOne($id);
        $user = Interview::findOne($id);
        if ($model->load($data)) {
            $url = 'http://test.web.1jiajie.com/v2/add_worker.php';
            // var_dump($model, $model->getErrors());exit;
            $d = $this->processUser($user, $model, $data['Signed']);
            $res = $this->postData($url, $d);
            if($res && $res->code=='ok'){
                $model->sendSome = serialize($model->sendSome);
                $model->contract_time = strtotime($model->contract_time);
                $model->save();
            }
            
            $user->sign_status = 1;
            $user->save(false);
            return $this->redirect('/interview/index/signed'); 
        } else {
            return $this->render('update', [
                'model' => $model,
                'user' => $user,
            ]);
        }
    }
    
    private function processUser($user, $model, $d){
        $data = [];
        $data['worker_name'] = $d['uname'];
        $data['gender'] = '1';
        $data['home_town'] = '中国';
        $data['admin_id'] = '1';
        $data['telephone'] = $d['mobile'];
        $data['worker_type'] = $user->whatodo;
        $data['city_name'] = $user->city;
        $data['live_place'] = $d['address'];
        $data['id_card'] = $d['identity_number'];
        $data['birthday'] = $user->birthday;
        $data['worker_age'] = $user->age;
        $data['emergency_phone'] = $d['emergency_contact'];
        $data['relatives'] = $d['emergency_person'];
        $data['deposit'] = empty($d['deposit']) ? '0' : '1';
        $data['worker_tools'] = serialize($d['sendSome']);
        $data['upload_face'] = empty($d['picture'])? 1 : 2;
        $data['is_agency'] = $user->from_type;
        return $data;
    }

    private function postData($url, $data){
        $ch = curl_init ();
        // 查询用户是否存在
        // http://test.web.1jiajie.com/v2/worker_info.php?worker_tel=11111
        // $url = $url.'?'.http_build_query($data);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
//        var_dump($url, $data, $return, json_decode($return));exit;
        curl_close ( $ch );
        return json_decode($return);
    }

    /**
     * Deletes an existing Signed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {   
        $user = Interview::findOne($id);
        $user->sign_status = 2;
        $user->save(false);    
            $model = Signed::findOne($id);
            if($model != null){
                $model->delete();
                echo 'success';
            }else{
                echo 'no_record';
            }
//        return $this->redirect(['index']);
    }

    /**
     * Finds the Signed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Signed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Signed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
