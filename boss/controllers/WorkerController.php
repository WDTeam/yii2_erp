<?php

namespace boss\controllers;


use boss\models\Shop;
use Yii;
use yii\db\Query;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use boss\components\BaseAuthController;

use common\models\WorkerBlock;
use common\models\WorkerVacation;
use common\models\WorkerBlockLog;
use core\models\worker\Worker;
use core\models\worker\WorkerExt;
use core\models\worker\WorkerDistrict;
use boss\models\worker\WorkerSearch;
use boss\models\worker\WorkerDetail;
use boss\models\Operation;



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
     * Finds the Worker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Worker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id,$hasExt=false)
    {
        if($hasExt==true){
            $model= Worker::find()->joinWith('workerExtRelation')->where(['id'=>$id])->one();
            $workerExtModel = new WorkerExt();
        }else{
            $model= Worker::findOne($id);
        }
        if ($model!== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Lists all Worker models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->request->get('getData')==1){
            $this->actionGetDataFromOldDataBase();
            $this->redirect(['worker/index']);
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
        $workerModel = $this->findModel($id,true);
        $workerBlockData = new ActiveDataProvider([
            'query' => WorkerBlock::find()->where(['worker_id'=>$id])->orderBy('id desc'),
        ]);
        $workerVacationData = new ActiveDataProvider([
            'query' => WorkerVacation::find()->where(['worker_id'=>$id])->orderBy('id desc'),
        ]);
        $workerBlockLogData = new ActiveDataProvider([
            'query' => WorkerBlockLog::find()->where(['worker_id'=>$id])->orderBy('id desc'),
        ]);
        if ($workerModel->load(Yii::$app->request->post()) && $workerModel->save()) {
            return $this->redirect(['view', 'id' => $workerModel->id]);
        } else {
            return $this->render('view', ['workerModel' => $workerModel,'workerBlockData'=>$workerBlockData,'workerVacationData'=>$workerVacationData,'workerBlockLogData'=>$workerBlockLogData]);
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
        $worker_district = new WorkerDistrict;
        $worker->created_ad = time();
        //$worker->link('workerExt',$worker_ext);
        if ($worker->load(Yii::$app->request->post()) && $worker->save()) {
            $worker_ext->load(Yii::$app->request->post());
            $worker_ext->worker_id = $worker->id;
            $worker_ext->save();
            $workerDistrictArr = Yii::$app->request->post('Worker');
            if($workerDistrictArr['worker_district']){
                foreach($workerDistrictArr['worker_district'] as $val){
                    $worker_district->created_ad = time();
                    $worker_district->worker_id = $worker->id;
                    $worker_district->operation_shop_district_id = $val;
                }
            }
            return $this->redirect(['view', 'id' => $worker->id]);
        } else {
            $worker_ext->province_id = $worker_ext->worker_live_province;
            $worker_ext->city_id = $worker_ext->worker_live_city;
            $worker_ext->county_id = $worker_ext->worker_live_area;
            $worker_ext->town_id = $worker_ext->worker_live_street;
            return $this->render('create', [
                'worker' => $worker,
                'worker_ext' => $worker_ext,
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




    /*
     * ajax通过搜索关键字获取门店信息
     * @param q string 关键字
     * @return result array 门店信息
     */
    public function actionShowShop($q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new Query;
        $query->select('id, name AS text')
            ->from('ejj_shop')
            ->where('name LIKE "%' . $q .'%"')
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        //$out['results'] = [['id' => '1', 'text' => '门店'], ['id' => '2', 'text' => '门店2'], ['id' => '2', 'text' => '门店3']];
        return $out;
    }


    /*
     * 创建阿姨请假信息
     * @param workerId 阿姨Id
     * @return mixed
     */
    public function actionCreateVacation($workerId){
        $workerModel = $this->findModel($workerId);
        $workerVacationModel = new WorkerVacation();
        $post = \Yii::$app->request->post();
        if(\Yii::$app->request->post()){
            $workerVacationModel->worker_id = $workerId;
            $workerVacationModel->worker_vacation_start_time = strtotime($post['WorkerVacation']['worker_vacation_start_time']);
            $workerVacationModel->worker_vacation_finish_time = strtotime($post['WorkerVacation']['worker_vacation_finish_time']);
            $workerVacationModel->worker_vacation_type = intval($post['WorkerVacation']['worker_vacation_type']);
            $workerVacationModel->worker_vacation_extend = trim($post['WorkerVacation']['worker_vacation_extend']);
            $workerVacationModel->created_ad = time();
            $workerVacationModel->admin_id = Yii::$app->user->identity->id;
            $saveStatus = $workerVacationModel->save();
            if($saveStatus){
                $workerModel->worker_is_vacation = 1;
                $workerModel->save();
            }
            return $this->redirect(['index']);
        }else{
            return $this->renderAjax('create_vacation',['workerModel'=>$workerModel,'workerVacationModel'=>$workerVacationModel]);
        }
    }

    /*
     * 创建阿姨封号信息
     * @param workerId 阿姨Id
     * @return empty
     */
    public function actionCreateBlock($workerId){
        $workerModel = $this->findModel($workerId);
        $workerBlockmodel = new WorkerBlock();
        $post = \Yii::$app->request->post();
        if($post){
            $workerBlockmodel->worker_id = $workerId;
            $workerBlockmodel->worker_block_start_time = strtotime($post['WorkerBlock']['worker_block_start_time']);
            $workerBlockmodel->worker_block_finish_time = strtotime($post['WorkerBlock']['worker_block_finish_time']);
            $workerBlockmodel->worker_block_reason = $post['WorkerBlock']['worker_block_reason'];
            $workerBlockmodel->worker_block_status = 0;
            $saveStatus = $workerBlockmodel->save();
            if($saveStatus){
                $workerModel->worker_is_block = 1;
                $workerModel->save();
            }
            return $this->redirect(['index']);
        }else{
            return $this->renderAjax('create_block',['workerModel'=>$workerModel,'workerBlockmodel'=>$workerBlockmodel]);
        }
    }


    /*
     * 更改阿姨封号信息，并同时记录到封号操作日志
     *
     */
    public function actionUpdateWorkerBlock(){
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $post = Yii::$app->request->post();
            $blockId = $post['editableKey'];
            $workerBlockArr = $post['WorkerBlock'][$post['editableIndex']];

            $workerBlockModel = WorkerBlock::findOne($blockId);
            $oldFinishTime = $workerBlockModel->worker_block_finish_time;
            $workerId = $workerBlockModel->worker_id;
            if(empty($workerId)){
                throw new ForbiddenHttpException('获取封号信息失败');
            }
            //更改封号结束时间
            if(isset($workerBlockArr['worker_block_finish_time'])){
                $finishTime = strtotime($workerBlockArr['worker_block_finish_time']);
                if($oldFinishTime>$finishTime){
                    //缩短封号时间
                    $this->CreateBlockLog($workerId,$blockId,2);
                }else{
                    //延长封号时间
                    $this->CreateBlockLog($workerId,$blockId,3);
                }
                $workerBlockModel->worker_block_finish_time = $finishTime;
            //更改封号状态
            }elseif(isset($workerBlockArr['worker_block_status']) && $workerBlockArr['worker_block_status']==1){
                $workerBlockModel->worker_block_status = $workerBlockArr['worker_block_status'];
                $this->CreateBlockLog($workerId,$blockId,4);
            }

            $workerBlockModel->save();
            //$model->getErrors();
            $out = json_encode(['output'=>array_values($param['WorkerBlock'] = $post['WorkerBlock'][$post['editableIndex']]), 'message'=>'']);
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
    }

    /*
     * 创建封号日志记录
     * @param workerId 阿姨Id
     * @param blockId 封号表主键id
     * @param type 封号类型 [1]创建操作
     * @return empty
     */
    protected function CreateBlockLog($workerId,$blocdId,$type){
        $logArr['worker_id'] = $workerId;
        $logArr['worker_block_id'] = $blocdId;
        $logArr['worker_block_operate_time'] = time();
        $logArr['worker_block_operate_type'] = $type;
        $logArr['worker_block_operate_id'] = Yii::$app->user->identity->id;
        if($type==1){
            $logArr['worker_block_operate_bak'] = '创建封号操作';
        }elseif($type==2){
            $logArr['worker_block_operate_bak'] = '缩短封号时间操作';
        }elseif($type==3){
            $logArr['worker_block_operate_bak'] = '延长封号时间操作';
        }elseif($type==4){
            $logArr['worker_block_operate_bak'] = '关闭操作';
        }else{
            $logArr['worker_block_operate_type'] = 5;
            $logArr['worker_block_operate_bak'] = '其他操作';
        }

        $workerBlockModel = new WorkerBlockLog();
        $workerBlockModel->load(['WorkerBlockLog'=>$logArr]);
        $workerBlockModel->save();
    }

    /*
     * 从老数据库中 导入阿姨数据 到新系统数据库中
     * 默认每次导入40条数据
     */
    public function actionGetDataFromOldDataBase(){

        $operationArea = new Operation\OperationArea();

        $connectionNew =  \Yii::$app->db;
        $command = $connectionNew->createCommand('select id from {{%worker}} ORDER by id asc limit 1');
        $lastWorkerArr = $command->queryAll();
        if($lastWorkerArr){
            $lastWorkerId = $lastWorkerArr[0]['id'];
        }else{
            $lastWorkerId = 99999;
        }

        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=sq_ejiajie_v2',
            'username' => 'sq_ejiajie',
            'password' => 'test_sq_ejiajie',
        ]);
        $connection->open();
        $command = $connection->createCommand("SELECT * FROM worker_info where id<$lastWorkerId order by id desc limit 40");
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
                    $workerExtArr['worker_live_province'] = 0;
                    $workerExtArr['worker_live_city'] = 0;
                    $workerExtArr['worker_live_area'] = 0;
                    $workerExtArr['worker_live_street']=$val['live_place'];
                }
                //获取状态
                //$workerArr[''] = $val['status'];
                //头像地址 static.1jiajie.com/{worker_id}.jpg
                //$workerArr['worker_photo'] = $val[''];
                $workerArr['worker_work_city'] = '';
                if(in_array($val['city_name'],$cityConfigArr)){
                    $workerArr['worker_work_city'] = $cityConfigArr[$val['city_name']];
                }

                $workerExtArr['worker_id'] = $val['id'];
                $workerExtArr['worker_age'] = $val['age'];
                $workerExtArr['worker_live_lng'] = $val['home_lng'];
                $workerExtArr['worker_live_lat'] = $val['home_lat'];
                $workerExtArr['worker_sex'] = $val['gender'];
                $workerExtArr['worker_is_health'] = $val['is_health'];
                $workerExtArr['worker_is_insurance'] = $val['is_insurance'];
                $workerEduConfig = [1=>'小学',2=>'初中',3=>'高中',4=>'大学'];
                if($val['education']){
                    $workerExtArr['worker_edu'] = $workerEduConfig[$val['education']];
                }else{
                    $workerExtArr['worker_edu'] = '';
                }

                $workerExtArr['worker_bank_card'] = $val['bank_card'];
                $workerExtArr['worker_bank_name'] = $val['bank_name'];
                $workerExtArr['worker_bank_from'] = $val['bank_from'];

                $workerDeviceArr['worker_id'] = $val['id'];
                $workerDeviceArr['worker_device_login_time'] = $val['last_login_time'];
                $workerDeviceArr['worker_device_login_ip'] = $val['last_login_ip'];
                $workerDeviceArr['worker_device_client_version'] = $val['client_version'];
                $workerDeviceArr['worker_device_version_name'] = $val['version_name'];
                $workerDeviceArr['worker_device_token'] = $val['device_token'];
                $workerDeviceArr['worker_device_mac_addr'] = $val['mac_add'];
                $workerDeviceArr['worker_device_curr_lng'] = $val['cur_lng'];
                $workerDeviceArr['worker_device_curr_lat'] = $val['cur_lat'];

                $workerStatArr['worker_id'] = $val['id'];
                $workerStatArr['worker_stat_order_num'] = $val['order_num'];
                $workerStatArr['worker_stat_sale_cards'] = $val['sale_card'];

                $batchWorker[] = $workerArr;
                $batchWorkerExt[] = $workerExtArr;
                $batchWorkerDevice[] = $workerDeviceArr;
                $batchWorkerStat[] = $workerStatArr;
            }
            $workerColumns = array_keys($workerArr);
            $connectionNew->createCommand()->batchInsert('{{%worker}}',$workerColumns, $batchWorker)->execute();
            $workerExtColumns = array_keys($workerExtArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_ext}}',$workerExtColumns, $batchWorkerExt)->execute();
            $workerDeviceColumns = array_keys($workerDeviceArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_device}}',$workerDeviceColumns, $batchWorkerDevice)->execute();
            $workerStatColumns = array_keys($workerStatArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_stat}}',$workerStatColumns, $batchWorkerStat)->execute();
        }

        //die;
    }





    public function actionTest(){
        echo '<pre>';
        var_dump(Worker::getDistrictFreeWorker(11,1));
        die;

        $a = Worker::getWorkerInfo(16351);
        var_dump($a);
        die;
//        Yii::$app->redis->sadd('district_1','16684','16683','16685','16686','16687','16688','16689','16682');
//        Yii::$app->redis->sadd('district_2','16694','16693','16695','16696','16697','16698','16699','16692');
//        Yii::$app->redis->sadd('worker_16694','10','11','9','8','7');
//        Yii::$app->redis->sadd('worker_16693','10','11');
        $workers = Yii::$app->redis->smembers('district_1');
        $time = date('H');
        foreach($workers as $val){
            $workerKey = 'worker_'.$val;
            $workerIsBusy = Yii::$app->redis->sismember($workerKey,$time);
            if(empty($workerIsBusy)){
                $workerFreeArr[] = $val;
            }
        }
        var_dump($workerFreeArr);
        var_dump(Yii::$app->redis->sinter('worker_16983','worker_16694'));
    }
}
