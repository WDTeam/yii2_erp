<?php

namespace boss\controllers;

use common\models\WorkerBlock;
use common\models\WorkerVacation;
use Yii;
use yii\db\Query;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use core\models\Worker;
use core\models\WorkerExt;
use boss\models\WorkerSearch;
use boss\models\Operation;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use common\models\WorkerBlockLog;

/**
 * WorkerController implements the CRUD actions for Worker model.
 */
class WorkerController extends BaseAuthController
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
     * Lists all Worker models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->request->get('getData')==1){
            $this->actionGetDataFromOldDataBase();
            header('worker/index?getData');
        }
        $searchModel = new WorkerSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Worker model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $workerModel = $this->findModel($id);
        $workerBlockModel = new WorkerBlock();
        $workerBlockData = new ActiveDataProvider([
            'query' => $workerBlockModel->find()->where(['worker_id'=>$id]),
        ]);
        $workerVacationModel = new WorkerVacation();
        $workerVacationData = new ActiveDataProvider([
            'query' => $workerVacationModel->find()->where(['worker_id'=>$id]),
        ]);
        if ($workerModel->load(Yii::$app->request->post()) && $workerModel->save()) {
            return $this->redirect(['view', 'id' => $workerModel->id]);
        } else {
            return $this->render('view', ['workerModel' => $workerModel,'workerBlockData'=>$workerBlockData,'workerVacationData'=>$workerVacationData]);
        }
    }

    /**
     * Creates a new Worker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $worker = new Worker;
        $worker_ext = new WorkerExt;
        $worker->created_ad = time();
        if ($worker->load(Yii::$app->request->post()) && $worker->save()) {

            return $this->redirect(['view', 'id' => $worker->id]);
        } else {
            $worker_ext->province_id = $worker_ext->worker_live_province;
            $worker_ext->city_id = $worker_ext->worker_live_city;
            $worker_ext->county_id = $worker_ext->worker_live_area;
            $worker_ext->town_id = $worker_ext->worker_live_street;
            return $this->render('create', [
                'worker' => $worker,
                'worker_ext' => $worker_ext
            ]);
        }
    }

    /**
     * Updates an existing Worker model.
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

    public function actionUpdateWorkerBlock(){
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $post = Yii::$app->request->post();
            $id = $post['editableKey'];
            $param['WorkerBlock'] = $post['WorkerBlock'][$post['editableIndex']];
            $model = WorkerBlock::findOne($id);
            $oldFinishTime = $model->worker_block_finish_time;
            if(isset($param['WorkerBlock']['worker_block_finish_time'])){
                if($oldFinishTime>$param['WorkerBlock']['worker_block_finish_time']){
                    $this->CreateBlockLog($id,2);
                }else{
                    $this->CreateBlockLog($id,3);
                }
                $model->worker_block_finish_time = strtotime($param['WorkerBlock']['worker_block_finish_time']);
            }
            if(isset($param['WorkerBlock']['worker_block_status']) && isset($param['WorkerBlock']['worker_block_status'])==1){
                $model->worker_block_status = $param['WorkerBlock']['worker_block_status'];
            }
            $model->save();
            //$model->getErrors();
            $out = json_encode(['output'=>array_values($param['WorkerBlock'] = $post['WorkerBlock'][$post['editableIndex']]), 'message'=>'']);
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
    }


    protected function CreateBlockLog($blocdId,$type){
            $log['worker_block_operate_type'] = $type;
            $log['worker_block_id'] = $blocdId;
            $log['worker_block_operate_time'] = time();
            $log['worker_block_operate_id'] = Yii::$app->user->identity->id;

            if($type==1){
                $log['worker_block_operate_bak'] = '创建封号操作';
            }elseif($type==2){
                $log['worker_block_operate_bak'] = '缩短封号时间操作';
            }elseif($type==3){
                $log['worker_block_operate_bak'] = '延长封号时间操作';
            }elseif($type==4){
                $log['worker_block_operate_bak'] = '关闭操作';
            }else{
                $log['worker_block_operate_type'] = 5;
                $log['worker_block_operate_bak'] = '其他操作';
            }

            $workerBlockModel = new WorkerBlockLog();
            $workerBlockModel->load(['WorkerBlockLog'=>$log]);
            $workerBlockModel->save();
    }

    /**
     * Deletes an existing Worker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Worker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Worker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Worker::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionShowShop($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('ejj_shop')
                ->where('name LIKE "%' . $q .'%"')
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            //$out['results'] = [['id' => '1', 'text' => '门店'], ['id' => '2', 'text' => '门店2'], ['id' => '2', 'text' => '门店3']];
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Worker::findone(array('id' => 1))->worker_name];
        }
        return $out;
    }


    /*
     * 创建阿姨请假信息
     *
     */
    public function actionVacationCreate($id){
        $workerModel = $this->findModel($id);
        $workerVacationmodel = new WorkerVacation();
        if(\Yii::$app->request->post()){

            return $this->redirect(['index']);
        }else{
            return $this->renderAjax('vacation_create',['workerModel'=>$workerModel,'workerVacationModel'=>$workerVacationmodel]);
        }

    }

    /*
     * 创建阿姨封号信息
     *
     */
    public function actionBlockCreate($id){
        $workerModel = $this->findModel($id);
        $workerBlockmodel = new WorkerBlock();
        $post = \Yii::$app->request->post();
        if($post){
            $workerBlockmodel->worker_id = $id;
            $workerBlockmodel->worker_block_start_time = strtotime($post['WorkerBlock']['worker_block_start_time']);
            $workerBlockmodel->worker_block_finish_time = strtotime($post['WorkerBlock']['worker_block_finish_time']);
            $workerBlockmodel->worker_block_reason = $post['WorkerBlock']['worker_block_reason'];
            $workerBlockmodel->worker_block_status = 0;
            $workerBlockmodel->save();
            return $this->redirect(['index']);
        }else{
            return $this->renderAjax('block_create',['workerModel'=>$workerModel,'workerBlockmodel'=>$workerBlockmodel]);
        }
    }

    public function actionGetDataFromOldDataBase(){

        $operationArea = new Operation\OperationArea();

        $connectionNew =  \Yii::$app->db;
        $command = $connectionNew->createCommand('select id from ejj_worker ORDER by id desc limit 1');
        $lastWorkerArr = $command->queryAll();
        if($lastWorkerArr){
            $lastWorkerId = $lastWorkerArr[0]['id'];
        }else{
            $lastWorkerId = 0;
        }

        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=sq_ejiajie_v2',
            'username' => 'sq_ejiajie',
            'password' => 'test_sq_ejiajie',
        ]);
        $connection->open();
        $command = $connection->createCommand("SELECT * FROM worker_info where id>$lastWorkerId order by id asc limit 40");
        $workerInfo = $command->queryAll();
        $cityConfigArr=['北京'=>110100,'上海'=>310100,'广州'=>440100,'深圳'=>440300,'成都'=>510100,'南京'=>320100,'合肥'=>340100,'武汉'=>420100,'杭州'=>330100,'哈尔滨'=>230100,'青岛'=>370200,'太原'=>140100,'天津'=>120100,'长沙'=>430100,'沈阳'=>210100,'济南'=>370100,'石家庄'=>130100];

        if($workerInfo){
            foreach($workerInfo as $val){
                $workerArr['id'] = intval($val['id']);
                $workerArr['shop_id'] = $val['shop_id'];
                $workerArr['worker_name'] = $val['name'];
                $workerArr['worker_phone'] = $val['telephone'];
                $workerArr['worker_idcard'] = $val['idcard'];
                $workerArr['worker_level'] = $val['star'];
                $workerArr['worker_password'] = $val['app_pass'];
                $workerArr['worker_type'] = $val['is_agency']==0?1:2;
                $workerArr['created_ad'] = strtotime($val['create_time']);
                //原有阿姨身份太凌乱，暂时只取兼职和全职
                if(strpos($val['is_fulltime'],'兼职')){
                    $workerArr['worker_rule_id']=2;
                }else{
                    $workerArr['worker_rule_id']=1;
                }

                //获取城市
                if(strpos($val['live_place'],':#:')!==false){
                    $liveArr = explode(':#:',$val['live_place']);

                    $streetName = '';
                    if(strpos($liveArr[0],'市')!==false){
                        $provinceName = mb_substr($liveArr[0],0,-3);
                        $cityName = $liveArr[1];
                        $areaName = $liveArr[2];
                        $streetName =$liveArr[3];
                        $where = "(area_name='$provinceName' and level=1) or (area_name='$cityName' and level=2) or  (area_name='$areaName' and level=3)";
                    }else{
                        $provinceName = $liveArr[1];
                        $cityName = $liveArr[2];
                        $areaName = $liveArr[3];
                        $where = "(area_name='$provinceName' and level=1) or (area_name='$cityName' and level=2) or  (area_name='$areaName' and level=3)";
                    }
                    $result = $operationArea->find()->select('id,area_name,level,parent_id')->where($where)->asArray()->all();


                    $result = ArrayHelper::map($result,'area_name','id');

                    $workerExtArr['worker_live_province'] = array_key_exists($provinceName,$result)?$result[$provinceName]:0;
                    $workerExtArr['worker_live_city'] = array_key_exists($cityName,$result)?$result[$cityName]:0;
                    $workerExtArr['worker_live_area'] = array_key_exists($areaName,$result)?$result[$areaName]:0;
                    $workerExtArr['worker_live_street'] = $streetName;

                }else{
                    $workerArr['worker_work_street']=$val['live_place'];
                }
                //获取状态
                //$workerArr[''] = $val['status'];
                //头像地址 static.1jiajie.com/{worker_id}.jpg
                //$workerArr['worker_photo'] = $val[''];
                if(in_array($val['city_name'],$cityConfigArr)){
                    $workerArr['worker_work_city'] = $cityConfigArr[$val['city_name']];
                }

                $workerExtArr['worker_id'] = $val['id'];
                $workerExtArr['worker_age'] = $val['age'];
                $workerExtArr['worker_hometown'] = $val['home_town'];
                $workerExtArr['worker_live_lng'] = $val['home_lng'];
                $workerExtArr['worker_live_lat'] = $val['home_lat'];
                $workerExtArr['worker_sex'] = $val['gender'];
                $workerExtArr['worker_is_health'] = $val['is_health'];
                $workerExtArr['worker_birth'] = strtotime($val['birthday']);
                $workerExtArr['worker_is_insurance'] = $val['is_insurance'];
                $workerExtArr['worker_edu'] = $val['education'];
                $workerExtArr['worker_bank_card'] = $val['bank_card'];
                $workerExtArr['worker_bank_name'] = $val['bank_name'];
                $workerExtArr['worker_bank_from'] = $val['bank_from'];

                $workerDevice['worker_id'] = $val['id'];
                $workerDevice['worker_device_login_time'] = $val['last_login_time'];
                $workerDevice['worker_device_login_ip'] = $val['last_login_ip'];
                $workerDevice['worker_device_client_version'] = $val['client_version'];
                $workerDevice['worker_device_version_name'] = $val['version_name'];
                $workerDevice['worker_device_token'] = $val['device_token'];
                $workerDevice['worker_device_mac_addr'] = $val['mac_add'];
                $workerDevice['worker_device_curr_lng'] = $val['cur_lng'];
                $workerDevice['worker_device_curr_lat'] = $val['cur_lat'];

                $workerStatArr['worker_id'] = $val['id'];
                $workerStatArr['worker_stat_order_num'] = $val['order_num'];
                $workerStatArr['worker_stat_sale_card'] = $val['sale_card'];


                $connectionNew->createCommand()->insert('ejj_worker', $workerArr)->execute();
                $connectionNew->createCommand()->insert('ejj_worker_ext', $workerExtArr)->execute();

            }

        }

    }

    public function actionTest(){
        $worker = new Worker();
        $worker = $worker->getWorkerInfo(43);
        echo '<pre>';
        var_dump($worker);die;
    }
}
