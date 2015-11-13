<?php

namespace boss\controllers\worker;



use boss\models\operation\OperationCity;
use boss\models\worker\WorkerExport;
use Composer\Package\Loader\ValidatingArrayLoader;
use core\models\worker\WorkerForRedis;
use core\models\worker\WorkerIdentityConfig;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use boss\components\BaseAuthController;

use dbbase\models\worker\WorkerBlockLog;
use boss\models\worker\WorkerBlock;
use boss\models\worker\WorkerVacation;
use boss\models\worker\Worker;
use boss\models\worker\WorkerExt;
use boss\models\worker\WorkerDistrict;
use boss\models\worker\WorkerAuth;
use boss\models\worker\WorkerSchedule;
use boss\models\worker\WorkerSearch;
use boss\models\Operation;
use core\models\shop\Shop;
use core\models\customer\CustomerWorker;
use core\models\operation\OperationArea;
use core\models\worker\WorkerSkill;
use core\models\operation\OperationShopDistrict;
use core\models\worker\WorkerStat;
use core\models\worker\WorkerVacationApplication;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

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
     * 首页阿姨列表
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkerSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 阿姨详情
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $workerModel = Worker::findModel($id,true);
        $workerExtModel = WorkerExt::findOne($id);
        if ($workerModel->load(Yii::$app->request->post()) && $workerExtModel->load(Yii::$app->request->post())) {
            unset($workerModel->worker_photo);
            $workerModel->uploadImgToQiniu('worker_photo');
            $workerModel->save();
            //更新阿姨附属信息
            $workerExtModel->worker_id = $workerModel->id;
            $workerExtModel->save();
            //更新阿姨商圈信息
            $workerDistrictModel = new WorkerDistrict;
            $workerParam = Yii::$app->request->post('Worker');
            $workerDistrictModel->deleteAll(['worker_id'=>$id]);
            WorkerForRedis::deleteDistrictWorkerRelationToRedis($workerModel->id);
            WorkerForRedis::updateWorkerInfoToRedis($workerModel->id,$workerModel->shop_id,$workerModel->worker_phone,$workerModel->worker_name,$workerModel->worker_type,$workerModel->worker_identity_id);
            if($workerParam['worker_district']){
                foreach($workerParam['worker_district'] as $val){
                    $workerDistrictModel = new WorkerDistrict;
                    $workerDistrictModel->created_ad = time();
                    $workerDistrictModel->worker_id = $id;
                    $workerDistrictModel->operation_shop_district_id = $val;
                    $workerDistrictModel->save();
                }
                //更新商圈绑定阿姨到redis
                 WorkerForRedis::operateDistrictWorkerRelationToRedis($id,$workerParam['worker_district']);
            }
            return $this->redirect(['view', 'id' => $workerModel->id]);
        } else {
            $workerVacationData = new ActiveDataProvider([
                'query' => WorkerVacation::find()->where(['worker_id'=>$id])->orderBy('id desc'),
            ]);
            $workerBlockLogData = new ActiveDataProvider([
                'query' => WorkerBlockLog::find()->where(['worker_id'=>$id])->orderBy('id desc'),
            ]);
            $schedule = WorkerSchedule::find()->where(['worker_id'=>$workerModel->id])->asArray()->all();
            $scheduleFromRedis = WorkerForRedis::getWorkerSchedule($id);
            return $this->render('view', ['workerModel' => $workerModel,'worker_id'=>$id,'workerVacationData'=>$workerVacationData,'workerBlockLogData'=>$workerBlockLogData,'schedule'=>$schedule,'schedule_from_redis'=>$scheduleFromRedis]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionOpeationSchedule($id){
        $scheduleModel = new WorkerSchedule();
        if(Yii::$app->request->post()){
              $scheduleParam = Yii::$app->request->post('schedule_data');
              $scheduleParam = json_decode($scheduleParam,1);
              $scheduleModel->deleteAll(['worker_id'=>$id]);
              foreach ($scheduleParam as $key=>$val) {
                  $scheduleModel = new WorkerSchedule();
                  $dateRange = explode('至',$key);
                  $scheduleModel->worker_schedule_start_date = strtotime(trim($dateRange[0]));
                  $scheduleModel->worker_schedule_end_date = strtotime(trim($dateRange[1]));
                  $scheduleModel->worker_id = $id;
                  $scheduleModel->worker_schedule_timeline = json_encode($val);
                  $scheduleModel->created_ad = time();
                  $scheduleModel->save();
                  //var_dump($scheduleModel->getErrors());
              }
            WorkerForRedis::updateWorkerScheduleInfoToRedis($id);
          }
        return $this->redirect(['view', 'id' => $id,'tab'=>2]);
    }

    /**
     * 操作阿姨黑名单
     * @param $id
     * @return \yii\web\Response
     */
    public function actionOperateBlacklist($id){
        $workerModel = Worker::findModel($id);
        if($workerModel->load(Yii::$app->request->post())){
            $workerModel->worker_blacklist_time = time();
            $modifiedAttributes = $workerModel->getDirtyAttributes();
            if($workerModel->save() && isset($modifiedAttributes['worker_is_blacklist'])){
                if($workerModel->worker_is_blacklist==0){
                    WorkerForRedis::initWorkerToRedis($id);
                }else{
                    WorkerForRedis::deleteWorkerToRedis($id);
                }
            }
            //var_dump($workerModel->errors);die;
        }
        return $this->redirect(['auth', 'id' => $id]);
    }

    /**
     * 操作阿姨离职
     * @param $id
     * @return \yii\web\Response
     */
    public function actionOperateDimission($id){
        $workerModel = $this->findModel($id);
        if($workerModel->load(Yii::$app->request->post())){
            $workerModel->worker_dimission_time = time();
            $modifiedAttributes = $workerModel->getDirtyAttributes();
            if($workerModel->save() && isset($modifiedAttributes['worker_is_dimission'])){
                if($workerModel->worker_is_dimission==0){
                    WorkerForRedis::initWorkerToRedis($id);
                }else{
                    WorkerForRedis::deleteWorkerToRedis($id);
                }
            }
        }
        return $this->redirect(['auth', 'id' => $id]);
    }

    /**
     * 录入新阿姨
     * @return string|\yii\web\Response
     * @throws ServerErrorHttpException
     */

    public function actionCreate()
    {
        $workerModel = new Worker;
        $workerExtModel = new WorkerExt;
        $workerStatModel = new WorkerStat();
        $workerAuthModel = new WorkerAuth();
        $workerModel->setScenario('create');
        if ($workerModel->load(Yii::$app->request->post()) && $workerExtModel->load(Yii::$app->request->post())) {
            $workerModel->created_ad = time();
            $workerModel->uploadImgToQiniu('worker_photo');
            if($workerModel->save()){
                $workerExtModel->worker_id = $workerModel->id;
                $workerExtModel->save();
                $workerStatModel->worker_id = $workerModel->id;
                $workerStatModel->save();
                $workerAuthModel->worker_id = $workerModel->id;
                $workerAuthModel->save();
                $workerParam = Yii::$app->request->post('Worker');
                WorkerForRedis::addWorkerInfoToRedis($workerModel->id,$workerModel->shop_id,$workerModel->worker_phone,$workerModel->worker_name,$workerModel->worker_type,$workerModel->worker_identity_id);
                if($workerParam['worker_district']){
                    foreach($workerParam['worker_district'] as $val){
                        $workerDistrictModel = new WorkerDistrict;
                        $workerDistrictModel->created_ad = time();
                        $workerDistrictModel->worker_id = $workerModel->id;
                        $workerDistrictModel->operation_shop_district_id = $val;
                        $workerDistrictModel->save();
                    }
                    WorkerForRedis::operateDistrictWorkerRelationToRedis($workerModel->id,$workerParam['worker_district']);
                }
                return $this->redirect(['view', 'id' => $workerModel->id,'tab'=>2]);
            }
        } else {
            return $this->render('create', [
                'worker' => $workerModel,
                'worker_ext' => $workerExtModel,
            ]);
        }
    }

    /**
     * ajax验证 阿姨信息(电话和身份证号是否唯一)
     * @return array
     */
    public function actionAjaxValidateWorkerInfo(){
        $worker_id = Yii::$app->request->get('worker_id');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //修改阿姨
        if ($worker_id) {
            $workerModel = Worker::findModel($worker_id);
            $workerModel->load(Yii::$app->request->post());
            return \yii\bootstrap\ActiveForm::validate($workerModel,['worker_phone']);
        //添加阿姨
        }else{
            //$workerModel = Worker::findAll(['isdel'=>0]);
            $workerModel = new Worker(['isdel'=>0]);
            $workerModel->load(Yii::$app->request->post());
            return \yii\bootstrap\ActiveForm::validate($workerModel,['worker_phone','worker_idcard']);
        }
    }

    /**
     * 更新阿姨
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Worker::findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionAuth($id){
        $workerAuthModel = WorkerAuth::findModel($id);

        if(Yii::$app->request->post('WorkerAuth')){
            $param = Yii::$app->request->post('WorkerAuth');
            $workerModel = Worker::findModel($id);
            if($workerAuthModel->load(Yii::$app->request->post()) && $workerAuthModel->save()){
                if(isset($param['worker_auth_status']) && $param['worker_auth_status']==2){
                    $workerModel->worker_auth_status = 1;
                    $workerModel->save();
                }elseif(isset($param['worker_auth_status']) && $param['worker_auth_status']==1){
                    $workerModel->worker_auth_status = 2;
                    $workerModel->save();
                }elseif(isset($param['worker_basic_training_status']) && $param['worker_basic_training_status']==2){
                    $workerModel->worker_auth_status = 3;
                    $workerModel->save();
                }elseif(isset($param['worker_basic_training_status']) && $param['worker_basic_training_status']==1){
                    $workerModel->worker_auth_status = 4;
                    $workerModel->save();
                }  elseif(isset($param['worker_ontrial_status']) && $param['worker_ontrial_status']==2){
                    $workerModel->worker_auth_status = 5;
                    $workerModel->save();
                } elseif(isset($param['worker_ontrial_status']) && $param['worker_ontrial_status']==1){
                    $workerModel->worker_auth_status = 6;
                    $workerModel->save();
                }elseif(isset($param['worker_onboard_status']) && $param['worker_onboard_status']==2){
                    $workerModel->worker_auth_status = 7;
                    $workerModel->save();
                }elseif(isset($param['worker_onboard_status']) && $param['worker_onboard_status']==1){
                    $workerModel->worker_auth_status = 8;
                    $workerModel->save();
                }elseif(isset($param['worker_upgrade_training_status']) && $param['worker_upgrade_training_status']==2){
                    $workerModel->worker_auth_status = 9;
                    $workerModel->save();
                }elseif(isset($param['worker_upgrade_training_status']) && $param['worker_upgrade_training_status']==1){
                    $workerModel->worker_auth_status = 10;
                    $workerModel->save();
                }
            }
        }
        return $this->render('view_auth',['worker_id'=>$id,'workerAuthModel'=>$workerAuthModel]);
    }

    public function actionOperateVacationApplication($id,$status){
        $model = WorkerVacationApplication::findOne($id);
        $model->worker_vacation_application_approve_status = $status;
        $model->worker_vacation_application_approve_time = time();
        $model->save();
        if($status==1){
            $workerVacationModel = new WorkerVacation;
            $workerVacationModel->worker_id = $model->worker_id;
            $workerVacationModel->worker_vacation_start_time = $model->worker_vacation_application_start_time;
            $workerVacationModel->worker_vacation_finish_time = $model->worker_vacation_application_end_time;
            $workerVacationModel->worker_vacation_type = $model->worker_vacation_application_type;
            $workerVacationModel->worker_vacation_source = 1;
            $workerVacationModel->worker_vacation_status = 1;
            $workerVacationModel->save();
        }
        return $this->redirect(['index','WorkerSearch[worker_vacation_application_approve_status]'=>0]);
    }

    /**
     * 删除阿姨
     * 数据库中不删除阿姨信息，采用软删除
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=Worker::findModel($id);
        $model->isdel = 1;
        if($model->save()){
            WorkerForRedis::deleteWorkerToRedis($id);
        }
        return $this->redirect(['index']);
    }




    /**
     * 通过搜索关键字获取门店信息
     * 联想搜索通过ajax返回
     * @param q string 关键字
     * @return result array 门店信息
     */
    public function actionShowShop($city_id=null,$q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $condition = '1=1';
        if($city_id){
            $condition .= ' and city_id='.$city_id;
        }
        if($q){
            $condition .=" and name like'%$q%'";
        }

        $shopResult = Shop::find()->where($condition)->select('id, name AS text')->asArray()->all();
        $out['results'] = array_values($shopResult);
        //$out['results'] = [['id' => '1', 'text' => '门店'], ['id' => '2', 'text' => '门店2'], ['id' => '2', 'text' => '门店3']];
        return $out;
    }

    public function actionShowDistrict($city_id=null,$q=null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ''];
        if(empty($city_id)){
            return $out;
        }
        $data = OperationShopDistrict::getCityShopDistrictList($city_id);
        $new_data = [];
        foreach ((array)$data as $val) {
            $new_data[] = ['id'=>$val['id'],'text'=>$val['operation_shop_district_name']];
        }
        $out['results']=$new_data;
        return $out;
    }

    public function actionShowArea($parent_id,$name=null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if(empty($parent_id)){
            return $out;
        }
        $condition = "parent_id=$parent_id";
        if(!empty($name)){
            $condition .= " and area_name like '%$name%'";
        }
        $data = OperationArea::getAllData($condition);
        $new_data = [];
        foreach ((array)$data as $val) {
            $new_data[] = ['id'=>$val['id'],'text'=>$val['area_name']];
        }
        $out['results']=$new_data;
        return $out;
    }

    /**
     * 创建阿姨请假信息
     * 可以为单个阿姨或者多个阿姨创建请假信息
     * @param workerId 阿姨Id
     * @return mixed
     */
    public function actionCreateVacation($workerIds){
        $workerIdsArr = explode(',',$workerIds);

        $workerVacationModel = new WorkerVacation();
        $post = \Yii::$app->request->post();
        if($post){
            foreach($workerIdsArr as $id){
                $workerVacationModel = new WorkerVacation();
                $workerVacationModel->worker_vacation_start_time = strtotime($post['WorkerVacation']['worker_vacation_start_time']);
                $workerVacationModel->worker_vacation_finish_time = strtotime($post['WorkerVacation']['worker_vacation_finish_time']);
                $workerVacationModel->worker_vacation_type = intval($post['WorkerVacation']['worker_vacation_type']);
                $workerVacationModel->worker_vacation_extend = trim($post['WorkerVacation']['worker_vacation_extend']);
                $workerVacationModel->worker_vacation_status = 1;
                $workerVacationModel->created_ad = time();
                $workerVacationModel->admin_id = Yii::$app->user->identity->id;
                $workerVacationModel->worker_id = $id;
                if($workerVacationModel->save()){
                    $workerModel = Worker::findOne($id);
                    $workerModel->worker_is_vacation = 1;
                    $workerModel->save();
                }
            }
            return $this->redirect(['index']);
        }else{
            $result = Worker::getWorkerListByIds($workerIdsArr,'worker_name');
            $workerNameStr = '';
            foreach ($result as $val) {
                $workerNameStr .= $val['worker_name'].',';
            }
            $workerNameStr = trim($workerNameStr,',');
            $workerVacationModel->worker_vacation_type = \Yii::$app->request->get('vacationType');
            return $this->renderAjax('create_vacation',['workerName'=>$workerNameStr,'workerVacationModel'=>$workerVacationModel,'type'=>Yii::$app->request->get('vacationType')]);
        }
    }

    public function actionOperateVacation($workerId){
        $param = Yii::$app->request->post('WorkerVacation');

        if($param){
            if(empty($param['id'])){
                $workerVacationModel = new WorkerVacation();
            }else{
                $workerVacationModel = WorkerVacation::findOne($param['id']);
            }
            $dateRange = explode('至',$param['daterange']);
            $startDate = $dateRange[0];
            $finishDate = $dateRange[1];
            $workerVacationModel->worker_id = $workerId;
            $workerVacationModel->worker_vacation_start_time = strtotime($startDate);
            $workerVacationModel->worker_vacation_finish_time = strtotime($finishDate);
            $workerVacationModel->worker_vacation_type = $param['worker_vacation_type'];
            $workerVacationModel->worker_vacation_extend = $param['worker_vacation_extend'];
            $workerVacationModel->worker_vacation_status = 1;
            $workerVacationModel->worker_vacation_source = 0;
            if($workerVacationModel->save()){
                $workerModel = Worker::findOne($workerId);
                $workerModel->worker_is_vacation = 1;
                $workerModel->save();
            }
        }
        return $this->redirect(['auth', 'id' => $workerId]);
    }

    public function actionOperateVacationBak($workerId){
        $param = Yii::$app->request->post('WorkerVacation');

        if($param){
            if(empty($param['id'])){
                $workerVacationModel = new WorkerVacation();
            }else{
                $workerVacationModel = WorkerVacation::findOne($param['id']);
            }
            $dateRange = explode('至',$param['daterange']);
            $startDate = $dateRange[0];
            $finishDate = $dateRange[1];
            $workerVacationModel->worker_id = $workerId;
            $workerVacationModel->worker_vacation_start_time = strtotime($startDate);
            $workerVacationModel->worker_vacation_finish_time = strtotime($finishDate);
            $workerVacationModel->worker_vacation_type = $param['worker_vacation_type'];
            $workerVacationModel->worker_vacation_extend = $param['worker_vacation_extend'];
            $workerVacationModel->worker_vacation_status = $param['worker_vacation_status'];
            if($workerVacationModel->save()){
                $workerModel = Worker::findOne($workerId);
                if(empty($param['id'])){
                    if($param['worker_vacation_status']==1){
                        $workerModel->worker_is_vacation = 1;
                        $workerModel->save();
                    }elseif($param['worker_vacation_status']==0){
                        $workerModel->worker_is_vacation = 0;
                        $workerModel->save();
                    }
                }else{
                    $modifiedAttributes = $workerVacationModel->getDirtyAttributes();
                    if($modifiedAttributes['worker_vacation_status']==1){
                        $workerModel->worker_is_vacation = 1;
                        $workerModel->save();
                    }elseif($modifiedAttributes['worker_vacation_status']==0){
                        $workerModel->worker_is_vacation = 0;
                        $workerModel->save();
                    }
                }
            }
        }
        return $this->redirect(['auth', 'id' => $workerId]);
    }


    /**
     * 创建阿姨封号信息
     * @param integer workerId 阿姨Id
     * @return empty
     */
    public function actionOperateBlock($workerId){
        $param = \Yii::$app->request->post('WorkerBlock');

        if($param){
            if(empty($param['id'])){
                $workerBlockModel = new WorkerBlock();
            }else{
                $workerBlockModel = WorkerBlock::findone($param['id']);
            }
            $dateRange = explode('至',$param['daterange']);
            $startDate = $dateRange[0];
            $finishDate = $dateRange[1];
            $workerBlockModel->worker_id = intval($workerId);
            $workerBlockModel->worker_block_start_time = strtotime($startDate);
            $workerBlockModel->worker_block_finish_time = strtotime($finishDate);
            $workerBlockModel->worker_block_reason = $param['worker_block_reason'];
            //更改的阿姨封号属性
            $modifiedAttributes = $workerBlockModel->getDirtyAttributes();
            $current_time = strtotime(date('Y-m-d'));
            if($workerBlockModel->save() && (isset($modifiedAttributes['worker_block_start_time']) || isset($modifiedAttributes['worker_block_finish_time']))){
                $workerModel = Worker::findModel($workerId);
                if($workerBlockModel->worker_block_start_time<=$current_time && $workerBlockModel->worker_block_finish_time>=$current_time){
                    $workerModel->worker_is_block = 1;
                    $workerModel->save();
                    WorkerForRedis::deleteWorkerToRedis($workerId);
                }else{
                    $workerModel->worker_is_block = 0;
                    $workerModel->save();
                    WorkerForRedis::initWorkerToRedis($workerId);
                }
            }
            return $this->redirect(['auth', 'id' => $workerId]);
        }
    }


    /**
     * 更改阿姨封号信息，并同时记录到封号操作日志
     *
     */
    public function actionUpdateWorkerBlock(){
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $post = Yii::$app->request->post();
            $block_id = $post['editableKey'];
            $workerBlockArr = $post['WorkerBlock'][$post['editableIndex']];

            $workerBlockModel = WorkerBlock::findOne($block_id);
            $old_finish_time = $workerBlockModel->worker_block_finish_time;
            $worker_id = $workerBlockModel->worker_id;
            if(empty($worker_id)){
                throw new ForbiddenHttpException('获取封号信息失败');
            }
            //更改封号结束时间
            if(isset($workerBlockArr['finishtime'])){
                $finish_time = strtotime($workerBlockArr['finishtime']);
                if($old_finish_time>$finish_time){
                    //缩短封号时间
                    $this->CreateBlockLog($worker_id,$block_id,2);
                }else{
                    //延长封号时间
                    $this->CreateBlockLog($worker_id,$block_id,3);
                }
                $workerBlockModel->worker_block_finish_time = $finish_time;
            //更改封号状态
            }elseif(isset($workerBlockArr['worker_block_status']) && $workerBlockArr['worker_block_status']==1){
                $workerBlockModel->worker_block_status = $workerBlockArr['worker_block_status'];
                $this->CreateBlockLog($worker_id,$block_id,4);
            }
            $workerBlockModel->save();
            //$model->getErrors();
            $out = json_encode(['output'=>array_values($param['WorkerBlock'] = $post['WorkerBlock'][$post['editableIndex']]), 'message'=>'']);
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
    }

    /**
     * 记录封号操作日志
     * @param integer workerId 阿姨Id
     * @param integer blockId 封号表主键id
     * @param integer type 封号类型 [1]创建操作[2]缩短封号时间[3]延长封号时间[4]关闭[5]其他
     * @return empty
     */
    protected function CreateBlockLog($workerId,$blocdId,$type){
        $logArr['worker_id'] = $workerId;
        $logArr['worker_block_id'] = $blocdId;
        $logArr['worker_block_operate_time'] = time();
        $logArr['worker_block_operate_type'] = $type;
        $logArr['worker_block_operate_id'] = Yii::$app->user->identity->id;
        if($type==1){
            $logArr['worker_block_operate_bak'] = '创建封号信息';
        }elseif($type==2){
            $logArr['worker_block_operate_bak'] = '缩短封号结束时间';
        }elseif($type==3){
            $logArr['worker_block_operate_bak'] = '延长封号结束时间';
        }elseif($type==4){
            $logArr['worker_block_operate_bak'] = '关闭阿姨封号';
        }elseif($type==5){
            $logArr['worker_block_operate_bak'] = '开启阿姨封号';
        }else{
            $logArr['worker_block_operate_type'] = 6;
            $logArr['worker_block_operate_bak'] = '其他操作';
        }

        $workerBlockModel = new WorkerBlockLog();
        $workerBlockModel->load(['WorkerBlockLog'=>$logArr]);
        $workerBlockModel->save();
    }

    /**
     * 导入新阿姨从Excel
     */
    public function actionExportDataFromExcel(){
        ini_set('memory_limit','512M');
        set_time_limit(0);
        $model = new WorkerExport();
        //上传阿姨信息
        $model->excel = UploadedFile::getInstance($model, 'excel');
        if ($model->excel) {
            $path='upload/';
            $filename=time().'.'.$model->excel->extension;
            if(!file_exists($path))mkdir($path);
            if(!$model->excel->saveAs($path.$filename)){
                \Yii::$app->getSession()->setFlash('default','上传文件错误');
            }
            $filePath = $path.$filename;
            $objPHPExcel = \PHPExcel_IOFactory::load($filePath);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $this->insertWorkerData($sheetData);
        }

        \Yii::$app->getSession()->setFlash('default','上传成功');
        $this->redirect(['index']);
    }

    public function actionExportVacationDataFromExcel(){
        $model = new WorkerExport();
        //上传阿姨请假信息
        $model->excel_vacation = UploadedFile::getInstance($model, 'excel_vacation');
        if ($model->excel_vacation) {
            $path='upload/';
            $filename=time().'.'.$model->excel_vacation->extension;
            if(!file_exists($path))mkdir($path);
            if(!$model->excel_vacation->saveAs($path.$filename)){
                \Yii::$app->getSession()->setFlash('default','上传文件错误');
            }
            $filePath = $path.$filename;
            $objPHPExcel = \PHPExcel_IOFactory::load($filePath);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $this->insertWorkerVacationData($sheetData);
        }


        \Yii::$app->getSession()->setFlash('default','上传成功');
        $this->redirect(['index']);
    }

    public function insertWorkerVacationData($workerVacationInfo){
        $typeList = ['休假'=>2,'事假'=>1];
        $connectionNew =  \Yii::$app->db;
        foreach ($workerVacationInfo as $key=>$col) {
            if($key<2){
                continue;
            }
            $workerResult = Worker::find()->where(['worker_phone'=>trim($col['B']),'worker_name'=>trim($col['A'])])->one();
            if(empty($workerResult)){
                \Yii::$app->getSession()->setFlash('default',$col['B'].'不存在');
            }
            $time = time();
            $workerVacationArr['worker_id'] = $workerResult['id'];
            $workerVacationArr['worker_vacation_start_time'] = strtotime($col['C']);
            $workerVacationArr['worker_vacation_finish_time'] = strtotime($col['D']);
            $workerVacationArr['worker_vacation_type'] = $typeList[$col['E']];
            $workerVacationArr['worker_vacation_extend'] = trim($col['F']);
            $workerVacationArr['worker_vacation_source'] = 0;
            $workerVacationArr['created_ad'] = $time;
            if(strtotime(trim($col['C']).' 00:00:00')<=$time && strtotime(trim($col['D']).' 11:59:59')>$time){
                $workerInfo['worker_is_vacation'] = $workerVacationArr['worker_vacation_type'];
                $connectionNew->createCommand()->update('{{%worker}}',$workerInfo,['id'=>intval($workerResult['id'])])->execute();
            }
            $batchWorkerVacation[] = $workerVacationArr;

        }
        $workerVacationtColumns = array_keys($workerVacationArr);
        $connectionNew->createCommand()->batchInsert('{{%worker_vacation}}',$workerVacationtColumns, $batchWorkerVacation)->execute();
    }


    public function insertWorkerData($workerInfo){
        ini_set('memory_limit','512M');
        $operationArea = new OperationArea();
        $connectionNew =  \Yii::$app->db;
        $shopArr = Shop::find()->asArray()->all();
        $shopArr = ArrayHelper::map($shopArr,'name','id');
        $identityConfigArr = WorkerIdentityConfig::find()->asArray()->all();
        $identityConfigArr = ArrayHelper::map($identityConfigArr,'worker_identity_name','id');
        $onlineCityList = OperationCity::getCityOnlineInfoList();
        $onlineCityList =  ArrayHelper::map($onlineCityList,'city_name','city_id');
        $districtList = OperationShopDistrict::getCityShopDistrictList();
        $districtList = ArrayHelper::map($districtList,'operation_shop_district_name','id');
        $sexList = ['女'=>0,'男'=>1];
        $typeList = ['自有'=>1,'非自有'=>2];
        $isHealthList = ['是'=>1,'否'=>0];
        $isInsuranceList= ['是'=>1,'否'=>0];
        $blockList= ['是'=>1,'否'=>0];
        $blacklist = [''=>0,'是'=>1,'否'=>0];
        $batchWorkerDistrict = [];
        $workerEduList = [1=>'小学',2=>'初中',3=>'高中',4=>'大学'];
        //$identityConfigArr = ['全职全日'=>1,'兼职'=>2];
        $lastWorkerId = Worker::find()->limit(1)->orderBy('id desc')->asArray()->one();

        if(empty($lastWorkerId)){
            $worker_id = 0;
        }else{
            $worker_id = $lastWorkerId['id'];
        }
        if($workerInfo){
            foreach($workerInfo as $key=>$col){
                if($key<2){
                    continue;
                }
                $worker_id++;
                $workerArr['id'] = $worker_id;
                $workerArr['worker_name'] = $col['A'];
                $workerArr['shop_id'] = isset($shopArr[$col['B']])?$shopArr[$col['B']]:'';
                $workerArr['worker_phone'] = $col['C'];
                $workerArr['worker_idcard'] = $col['D'];
                //$workerArr['worker_photo'] = $col['E'];
                if(stripos($col['F'],'市')===false){
                    $onlineCity = $col['F'].'市';
                    $workerArr['worker_work_city'] = isset($onlineCityList[$onlineCity])?$onlineCityList[$onlineCity]:0;
                }
                $workerArr['worker_type'] = $typeList[$col['L']];
                $workerArr['worker_identity_id'] = $identityConfigArr[$col['M']];
                $workerArr['worker_is_blacklist'] = $blacklist[$col['AB']];
                $workerArr['worker_blacklist_time'] = strtotime($col['AC']);
                $workerArr['worker_blacklist_reason'] = trim($col['AD']);
                $workerArr['worker_auth_status'] =8;
                if($col['AM']=='0000-00-00 00:00:00'){
                    $workerArr['created_ad'] = strtotime($col['AM']);
                }else{
                    $workerArr['created_ad'] = '';
                }

                $workerExtArr['worker_id'] = $worker_id;
                $workerExtArr['worker_age'] = intval($col['N']);
                $workerExtArr['worker_sex'] = $sexList[$col['O']];
                $workerExtArr['worker_edu'] = isset($workerEduList[$col['P']])?$workerEduList[$col['P']]:'';
                $workerExtArr['worker_is_health'] = $isHealthList[$col['Q']];
                $workerExtArr['worker_is_insurance'] = $isInsuranceList[$col['R']];
                $workerExtArr['worker_source'] = $col['S'];
                $workerExtArr['worker_bank_name'] = $col['T'];
                $workerExtArr['worker_bank_from'] = $col['U'];
                $workerExtArr['worker_bank_area'] = $col['V'];
                $workerExtArr['worker_bank_card'] = $col['W'];
//                if($col['K']){
//                    $provinceResult = $operationArea->find()->select('id')->where(['area_name'=>$col['H']])->asArray()->one();
//                    $cityResult = $operationArea->find()->select('id')->where(['area_name'=>$col['I']])->asArray()->one();
//                    $areaResult = $operationArea->find()->select('id')->where(['area_name'=>$col['J']])->asArray()->one();
//                    $workerExtArr['worker_live_province'] = $provinceResult['id'];
//                    $workerExtArr['worker_live_city'] = $cityResult['id'];
//                    $workerExtArr['worker_live_area'] = $areaResult['id'];
//                    $workerExtArr['worker_live_street'] = $col['K'];
//                }
//                if($col['K']){
//                    $areaArr = explode(':#:',$col['K']);
//                    if(strpos($areaArr[0],'市')!==false){
//                        $areaArr[0] = str_replace('市','',$areaArr[0]);
//                    }
//                    $provinceResult = $operationArea->find()->select('id')->where(['area_name'=>$areaArr[0]])->asArray()->one();
//                    $cityResult = $operationArea->find()->select('id')->where(['area_name'=>$areaArr[1]])->asArray()->one();
//                    $areaResult = $operationArea->find()->select('id')->where(['area_name'=>$areaArr[2]])->asArray()->one();
//                    $workerExtArr['worker_live_province'] = $provinceResult['id'];
//                    $workerExtArr['worker_live_city'] = $cityResult['id'];
//                    $workerExtArr['worker_live_area'] = $areaResult['id'];
//                    $workerExtArr['worker_live_street'] = $areaArr[3];
//                }else{
//                    $workerExtArr['worker_live_province'] = '';
//                    $workerExtArr['worker_live_city'] = '';
//                    $workerExtArr['worker_live_area'] = '';
//                    $workerExtArr['worker_live_street'] = '';
//                }




                $workerStatArr['worker_id'] = $worker_id;
                $workerStatArr['worker_stat_order_num'] =  intval($col['AE']);
                $workerStatArr['worker_stat_order_money'] = intval($col['AH']);
                $workerStatArr['worker_stat_order_refuse'] = intval($col['AF']);
                $workerStatArr['worker_stat_order_complaint'] = intval($col['AG']);
                $workerStatArr['worker_stat_sale_cards'] = intval($col['AI']);
                $workerStatArr['worker_stat_comment_good'] = intval($col['AJ']);
                $workerStatArr['worker_stat_comment_normal'] = intval($col['AK']);
                $workerStatArr['worker_stat_comment_bad'] = intval($col['AL']);

                $workerBlockArr['worker_id'] = $worker_id;
                $time = time();
                $workerBlockArr['worker_block_start_time'] = strtotime($col['Y']);
                $workerBlockArr['worker_block_finish_time'] = strtotime($col['Z']);
                $workerBlockArr['created_ad'] = time();
                if($workerBlockArr['worker_block_start_time']<$time && $workerBlockArr['worker_block_finish_time']>=$time){
                    $workerBlockArr['worker_block_status'] = 1;
                    $workerArr['worker_is_block'] = 1;
                }else{
                    $workerBlockArr['worker_block_status'] = 0;
                    $workerArr['worker_is_block'] = 0;
                }
                $workerBlockArr['worker_block_reason'] = trim($col['AA']);

                $workerAuthArr['worker_id'] = $worker_id;
                $workerAuthArr['worker_auth_status'] = 1;
                $workerAuthArr['worker_basic_training_status'] = 1;
                $workerAuthArr['worker_ontrial_status'] = 1;
                $workerAuthArr['worker_onboard_status'] = 1;

                $workerDeviceArr['worker_id'] = $worker_id;

                $districtArr = explode('，',$col['G']);
                if($col['G']){
                    foreach ((array)$districtArr as $d_val) {
                        $result = $this->getWorkerDistrict($onlineCity,$onlineCity.$d_val);
                        foreach ($result as $val) {
                            $districts[] = $val['operation_shop_district_id'];
                        }
                    }
                    $districts = array_unique($districts);
                    foreach ($districts as $val) {
                        $batchWorkerDistrict[] = [
                            'worker_id'=>$worker_id,
                            'operation_shop_district_id'=>$val,
                            'create_ad'=>time(),
                        ];
                    }
                }


                $batchWorker[] = $workerArr;
                $batchWorkerExt[] = $workerExtArr;
                $batchWorkerDevice[] = $workerDeviceArr;
                $batchWorkerStat[] = $workerStatArr;
                $batchWorkerAuth[] = $workerAuthArr;
                $batchWorkerBlockArr[] = $workerBlockArr;
                //Worker::addWorkerInfoToRedis($workerArr['id'],$workerArr['worker_phone'],$workerArr['worker_type'],$workerArr['worker_identity_id']);
            }
            $workerColumns = array_keys($workerArr);
            $connectionNew->createCommand()->batchInsert('{{%worker}}',$workerColumns, $batchWorker)->execute();
            $workerExtColumns = array_keys($workerExtArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_ext}}',$workerExtColumns, $batchWorkerExt)->execute();
            $workerDeviceColumns = array_keys($workerDeviceArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_device}}',$workerDeviceColumns, $batchWorkerDevice)->execute();
            $workerStatColumns = array_keys($workerStatArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_stat}}',$workerStatColumns, $batchWorkerStat)->execute();
            $workerAuthColumns = array_keys($workerAuthArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_auth}}',$workerAuthColumns, $batchWorkerAuth)->execute();
            if($batchWorkerDistrict){
                $connectionNew->createCommand()->batchInsert('{{%worker_district}}',['worker_id','operation_shop_district_id','created_ad'], $batchWorkerDistrict)->execute();
            }
            $workerBlockColumns = array_keys($workerBlockArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_block}}',$workerBlockColumns, $batchWorkerBlockArr)->execute();
        }
    }

    public function insertWorkerDataBak($workerInfo){
        $operationArea = new OperationArea();
        $connectionNew =  \Yii::$app->db;
        $shopArr = Shop::find()->asArray()->all();
        $shopArr = ArrayHelper::map($shopArr,'name','id');
//        $identityConfigArr = WorkerIdentityConfig::find()->asArray()->all();
//        $identityConfigArr = ArrayHelper::map($identityConfigArr,'worker_identity_name','id');
        $onlineCityList = OperationCity::getCityOnlineInfoList();
        $onlineCityList =  ArrayHelper::map($onlineCityList,'city_name','city_id');
        $onlineCityList = ['上海市'=>310100,'北京市'=>110100,'深圳市'=>440300];
        $districtList = OperationShopDistrict::getCityShopDistrictList();
        $districtList = ArrayHelper::map($districtList,'operation_shop_district_name','id');
        $sexList = ['女'=>0,'男'=>1];
        $typeList = ['自营'=>1,'非自营'=>2];
        $isHealthList = ['是'=>1,'否'=>0];
        $isInsuranceList= ['是'=>1,'否'=>0];
        $blockList= ['是'=>1,'否'=>0];
        $blacklist = [''=>0,'是'=>1,'否'=>0];
        $batchWorkerDistrict = [];
        $workerEduList = [1=>'小学',2=>'初中',3=>'高中',4=>'大学'];
        $identityConfigArr = ['全职全日'=>1,'兼职'=>2];
        $lastWorkerId = Worker::find()->limit(1)->orderBy('id desc')->asArray()->one();

        if(empty($lastWorkerId)){
            $worker_id = 0;
        }else{
            $worker_id = $lastWorkerId['id'];
        }
        if($workerInfo){
            foreach($workerInfo as $key=>$col){
                if($key<2){
                    continue;
                }
                if($col['K']!='自营'){
                    continue;
                }
                if($col['L']!='全职全日' && $col['L']!='兼职'){
                    continue;
                }
                $worker_id++;
                $workerArr['id'] = $worker_id;
                $workerArr['worker_name'] = $col['B'];
                $workerArr['shop_id'] = isset($shopArr[$col['C']])?$shopArr[$col['C']]:'';
                $workerArr['worker_phone'] = $col['D'];
                $workerArr['worker_idcard'] = $col['E'];
                //$workerArr['worker_photo'] = $col['E'];

                $worker_work_city = $col['G'].'市';
                $workerArr['worker_work_city'] = isset($onlineCityList[$worker_work_city])?$onlineCityList[$worker_work_city]:0;
                $workerArr['worker_type'] = $typeList[$col['K']];
                $workerArr['worker_identity_id'] = $identityConfigArr[$col['L']];
//                $workerArr['worker_is_blacklist'] = $blacklist[$col['AB']];
//                $workerArr['worker_blacklist_time'] = strtotime($col['AC']);
//                $workerArr['worker_blacklist_reason'] = trim($col['AD']);
                $workerArr['worker_auth_status'] = 8;
                if($col['AX']=='0000-00-00 00:00:00'){
                    $workerArr['created_ad'] = strtotime($col['AX']);
                }else{
                    $workerArr['created_ad'] = '';
                }

                $workerExtArr['worker_id'] = $worker_id;
                $workerExtArr['worker_age'] = intval($col['O']);
                $workerExtArr['worker_sex'] = $sexList[$col['P']];
                //$workerExtArr['worker_edu'] = isset($workerEduList[$col['Q']])?$workerEduList[$col['Q']]:'';
                $workerExtArr['worker_edu'] = $col['Q'];
                $workerExtArr['worker_is_health'] = intval(['R']);
                $workerExtArr['worker_is_insurance'] = intval($col['S']);
                $workerExtArr['worker_source'] = $col['U'];
                $workerExtArr['worker_bank_name'] = $col['V'];
                $workerExtArr['worker_bank_from'] = $col['W'];
                $workerExtArr['worker_bank_area'] = $col['X'];
                $workerExtArr['worker_bank_card'] = $col['Y'];
//                if($col['K']){
//                    $provinceResult = $operationArea->find()->select('id')->where(['area_name'=>$col['H']])->asArray()->one();
//                    $cityResult = $operationArea->find()->select('id')->where(['area_name'=>$col['I']])->asArray()->one();
//                    $areaResult = $operationArea->find()->select('id')->where(['area_name'=>$col['J']])->asArray()->one();
//                    $workerExtArr['worker_live_province'] = $provinceResult['id'];
//                    $workerExtArr['worker_live_city'] = $cityResult['id'];
//                    $workerExtArr['worker_live_area'] = $areaResult['id'];
//                    $workerExtArr['worker_live_street'] = $col['K'];
//                }
//                if($col['K']){
//                    $areaArr = explode(':#:',$col['K']);
//                    if(strpos($areaArr[0],'市')!==false){
//                        $areaArr[0] = str_replace('市','',$areaArr[0]);
//                    }
//                    $provinceResult = $operationArea->find()->select('id')->where(['area_name'=>$areaArr[0]])->asArray()->one();
//                    $cityResult = $operationArea->find()->select('id')->where(['area_name'=>$areaArr[1]])->asArray()->one();
//                    $areaResult = $operationArea->find()->select('id')->where(['area_name'=>$areaArr[2]])->asArray()->one();
//                    $workerExtArr['worker_live_province'] = $provinceResult['id'];
//                    $workerExtArr['worker_live_city'] = $cityResult['id'];
//                    $workerExtArr['worker_live_area'] = $areaResult['id'];
//                    $workerExtArr['worker_live_street'] = $areaArr[3];
//                }else{
//                    $workerExtArr['worker_live_province'] = '';
//                    $workerExtArr['worker_live_city'] = '';
//                    $workerExtArr['worker_live_area'] = '';
//                    $workerExtArr['worker_live_street'] = '';
//                }




                $workerStatArr['worker_id'] = $worker_id;
                $workerStatArr['worker_stat_order_num'] =  intval($col['AL']);
//                $workerStatArr['worker_stat_order_money'] = intval($col['AH']);
//                $workerStatArr['worker_stat_order_refuse'] = intval($col['AF']);
//                $workerStatArr['worker_stat_order_complaint'] = intval($col['AG']);
//                $workerStatArr['worker_stat_sale_cards'] = intval($col['AI']);
//                $workerStatArr['worker_stat_comment_good'] = intval($col['AJ']);
//                $workerStatArr['worker_stat_comment_normal'] = intval($col['AK']);
//                $workerStatArr['worker_stat_comment_bad'] = intval($col['AL']);

                $workerBlockArr['worker_id'] = $worker_id;
                $time = time();
//                $workerBlockArr['worker_block_start_time'] = strtotime($col['Y']);
//                $workerBlockArr['worker_block_finish_time'] = strtotime($col['Z']);
//                $workerBlockArr['created_ad'] = time();
//                if($workerBlockArr['worker_block_start_time']<$time && $workerBlockArr['worker_block_finish_time']>=$time){
//                    $workerBlockArr['worker_block_status'] = 1;
//                    $workerArr['worker_is_block'] = 1;
//                }else{
//                    $workerBlockArr['worker_block_status'] = 0;
//                    $workerArr['worker_is_block'] = 0;
//                }
//                $workerBlockArr['worker_block_reason'] = trim($col['AA']);

                $workerAuthArr['worker_id'] = $worker_id;
                $workerAuthArr['worker_auth_status'] = 1;
                $workerAuthArr['worker_basic_training_status'] = 1;
                $workerAuthArr['worker_ontrial_status'] = 1;
                $workerAuthArr['worker_onboard_status'] = 1;

                $workerDeviceArr['worker_id'] = $worker_id;

//                $districtArr = explode(',',$col['G']);
//                if($col['G']){
//                    foreach ((array)$districtArr as $d_val) {
//                        $batchWorkerDistrict[] = [
//                            'worker_id'=>$worker_id,
//                            'operation_shop_district_id'=>$districtList[$d_val],
//                            'create_ad'=>time(),
//                        ];
//                    }
//                }
                $batchWorker[] = $workerArr;
                $batchWorkerExt[] = $workerExtArr;
                $batchWorkerDevice[] = $workerDeviceArr;
                $batchWorkerStat[] = $workerStatArr;
                $batchWorkerAuth[] = $workerAuthArr;
                $batchWorkerBlockArr[] = $workerBlockArr;
                //Worker::addWorkerInfoToRedis($workerArr['id'],$workerArr['worker_phone'],$workerArr['worker_type'],$workerArr['worker_identity_id']);
            }
            $workerColumns = array_keys($workerArr);
            $connectionNew->createCommand()->batchInsert('{{%worker}}',$workerColumns, $batchWorker)->execute();
            $workerExtColumns = array_keys($workerExtArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_ext}}',$workerExtColumns, $batchWorkerExt)->execute();
            $workerDeviceColumns = array_keys($workerDeviceArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_device}}',$workerDeviceColumns, $batchWorkerDevice)->execute();
            $workerStatColumns = array_keys($workerStatArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_stat}}',$workerStatColumns, $batchWorkerStat)->execute();
            $workerAuthColumns = array_keys($workerAuthArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_auth}}',$workerAuthColumns, $batchWorkerAuth)->execute();
            if($batchWorkerDistrict){
                $connectionNew->createCommand()->batchInsert('{{%worker_district}}',['worker_id','operation_shop_district_id','created_ad'], $batchWorkerDistrict)->execute();
            }
            $workerBlockColumns = array_keys($workerBlockArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_block}}',$workerBlockColumns, $batchWorkerBlockArr)->execute();
            WorkerForRedis::initAllWorkerToRedis();
        }
    }

    /**
     * 从老数据库中 导入阿姨数据 到新系统数据库中
     * 默认每次导入40条数据
     */
    public function actionGetDataFromOldDataBase(){

        $operationArea = new OperationArea();

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
            'charset' => 'utf8',
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
                $workerArr['worker_auth_status'] = 8;
                //原有阿姨身份太凌乱，暂时只取兼职和全职
                if(strpos($val['is_fulltime'],'兼职')){
                    $workerArr['worker_identity_id']=2;
                }else{
                    $workerArr['worker_identity_id']=1;
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
                $workerArr['worker_work_city'] = 0;
                if(in_array($val['city_name'],$cityConfigArr)){
                    $workerArr['worker_work_city'] = $cityConfigArr[$val['city_name']];
                }

                $workerExtArr['worker_id'] = $val['id'];
                $workerExtArr['worker_age'] = intval($val['age']);
                $workerExtArr['worker_live_lng'] = $val['home_lng'];
                $workerExtArr['worker_live_lat'] = $val['home_lat'];
                $workerExtArr['worker_sex'] = intval($val['gender']);
                $workerExtArr['worker_is_health'] = intval($val['is_health']);
                $workerExtArr['worker_is_insurance'] = intval($val['is_insurance']);
                $workerEduConfig = [1=>'小学',2=>'初中',3=>'高中',4=>'大学'];
                if($val['education']){
                    $workerExtArr['worker_edu'] = $workerEduConfig[$val['education']];
                }else{
                    $workerExtArr['worker_edu'] = '';
                }

                $workerExtArr['worker_bank_card'] = intval($val['bank_card']);
                $workerExtArr['worker_bank_name'] = $val['bank_name'];
                $workerExtArr['worker_bank_from'] = $val['bank_from'];

                $workerDeviceArr['worker_id'] = $val['id'];
                $workerDeviceArr['worker_device_login_time'] = strtotime($val['last_login_time']);
                $workerDeviceArr['worker_device_login_ip'] = $val['last_login_ip'];
                $workerDeviceArr['worker_device_client_version'] = $val['client_version'];
                $workerDeviceArr['worker_device_version_name'] = $val['version_name'];
                $workerDeviceArr['worker_device_token'] = $val['device_token'];
                $workerDeviceArr['worker_device_mac_addr'] = $val['mac_add'];
                $workerDeviceArr['worker_device_curr_lng'] = $val['cur_lng'];
                $workerDeviceArr['worker_device_curr_lat'] = $val['cur_lat'];

                $workerStatArr['worker_id'] = $val['id'];
                $workerStatArr['worker_stat_order_num'] = intval($val['order_num']);
                $workerStatArr['worker_stat_sale_cards'] = intval($val['sale_card']);

                $workerAuthArr['worker_id'] = $val['id'];
                $workerAuthArr['worker_auth_status'] = 1;
                $workerAuthArr['worker_basic_training_status'] = 1;
                $workerAuthArr['worker_ontrial_status'] = 1;
                $workerAuthArr['worker_onboard_status'] = 1;

                $batchWorker[] = $workerArr;
                $batchWorkerExt[] = $workerExtArr;
                $batchWorkerDevice[] = $workerDeviceArr;
                $batchWorkerStat[] = $workerStatArr;
                $batchWorkerAuth[] = $workerAuthArr;
                Worker::addWorkerInfoToRedis($workerArr['id'],$workerArr['worker_phone'],$workerArr['worker_type'],$workerArr['worker_identity_id']);
            }

            $workerColumns = array_keys($workerArr);
            $connectionNew->createCommand()->batchInsert('{{%worker}}',$workerColumns, $batchWorker)->execute();
            $workerExtColumns = array_keys($workerExtArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_ext}}',$workerExtColumns, $batchWorkerExt)->execute();
            $workerDeviceColumns = array_keys($workerDeviceArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_device}}',$workerDeviceColumns, $batchWorkerDevice)->execute();
            $workerStatColumns = array_keys($workerStatArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_stat}}',$workerStatColumns, $batchWorkerStat)->execute();
            $workerAuthColumns = array_keys($workerAuthArr);
            $connectionNew->createCommand()->batchInsert('{{%worker_auth}}',$workerAuthColumns, $batchWorkerAuth)->execute();
        }

    }

    /**
     * 初始化redis数据
     */
    public function actionInitWorkerForRedis(){
        WorkerForRedis::initAllWorkerToRedis();
    }

    public function getWorkerDistrict($city_encode,$detail_encode){
        $detail_encode = urlencode($detail_encode);
        $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
        $address_decode = json_decode($address_encode, true);
        $longitude = $address_decode['result']['location']['lng'];
        $latitude = $address_decode['result']['location']['lat'];
        $where = '`operation_shop_district_coordinate_start_longitude` <= '.$longitude.' AND '.$longitude.' <= `operation_shop_district_coordinate_end_longitude`'.' AND `operation_shop_district_coordinate_start_latitude` >= '.$latitude.' AND `operation_shop_district_coordinate_end_latitude` <='.$latitude;
        $ShopDistrictInfo = \core\models\operation\OperationShopDistrictCoordinate::find()->select(['id', 'operation_shop_district_id', 'operation_shop_district_name', 'operation_city_id', 'operation_city_name', 'operation_area_id', 'operation_area_name'])->asArray()->where($where)->all();
        return $ShopDistrictInfo;

    }

    public function actionTest(){
        $city_encode = '北京市';
        $detail_encode = urlencode('北京市东城区东直门');
        $address_encode = file_get_contents("http://api.map.baidu.com/geocoder/v2/?city=".$city_encode."&address=".$detail_encode."&output=json&ak=AEab3d1da1e282618154e918602a4b98");
        $address_decode = json_decode($address_encode, true);
        $longitude = $address_decode['result']['location']['lng'];
        $latitude = $address_decode['result']['location']['lat'];
        //$ShopDistrictInfo = \core\models\operation\OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        $where = '`operation_shop_district_coordinate_start_longitude` <= '.$longitude.' AND '.$longitude.' <= `operation_shop_district_coordinate_end_longitude`'.' AND `operation_shop_district_coordinate_start_latitude` >= '.$latitude.' AND `operation_shop_district_coordinate_end_latitude` <='.$latitude;
        $ShopDistrictInfo = \core\models\operation\OperationShopDistrictCoordinate::find()->select(['id', 'operation_shop_district_id', 'operation_shop_district_name', 'operation_city_id', 'operation_city_name', 'operation_area_id', 'operation_area_name'])->asArray()->where($where)->all();

        var_dump($ShopDistrictInfo);
        die;
        echo '<pre>';
//        $a = Worker::findAllModel(['isdel'=>0],true);
//        var_dump($a);
        //var_dump(Worker::getWorkerDetailInfo(19077));die;
        //echo '星期1 8:00 10:00';
        //echo date('Y-m-d H:i',1446253200);
        //echo '<br>';
        var_dump(WorkerForRedis::initAllWorkerToRedis());die;
        //echo date('Y-m-d H:i',1446264000);
        //$a = Worker::getWorkerStatInfo(19077);
        //$a = Worker::getWorkerBankInfo(19077);
        //var_dump($a);die;
        $a = Worker::getDistrictCycleFreeWorker(1,1,[['orderBookBeginTime'=>'1446253200','orderBookEndTime'=>'1446264000']]);
        print_r($a);
        //$a = Worker::operateWorkerOrderInfoToRedis(1,1,1,2,1446434010,1446434010);
        die;
//        $a = json_decode('{"1":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00"],"2":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00"],"3":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00"],"4":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","16:00","17:00","20:00","21:00","22:00"],"5":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00"],"6":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00"],"7":["8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00"]}',1);
//        $workerInfo = [
//            'info'=>[
//                'worker_id'=>18564,
//                'worker_phone'=>18534121,
//                'worker_type'=>1
//            ],
//            'schedule'=>[
//                [
//                    'schedule_id'=>1,
//                    'worker_schedule_start_date'=>1490000000,
//                    'worker_schedule_end_date'=>1490000000,
//                    'worker_schedule_timeline'=>$a,
//                ]
//            ],
//            'order'=>[
//                [
//                    'order_id'=>1,
//                    'order_booked_count'=>3,
//                    'order_booked_begin_time'=>'14087655',
//                    'order_booked_end_time'=>'14087655',
//                ],
//                [
//                    'order_id'=>2,
//                    'order_booked_count'=>3,
//                    'order_booked_begin_time'=>'14087655',
//                    'order_booked_end_time'=>'14087655',
//                ],
//            ]
//        ];
//        Yii::$app->redis->set('WORKER_18475',json_encode($workerInfo));
//        die;
//        echo '<pre>';
//        var_dump(CustomerWorker::getCustomerDistrictNearbyWorker(1,1));die;
//        die;
//        var_dump(WorkerVacationApplication::getApplicationList(18517));
//
//        $a = Worker::getWorkerInfo(16351);
//        var_dump($a);
//        die;
       // Yii::$app->redis->set('worker_1',json_encode($workerInfo));
       // die;

        //$workers = Yii::$app->redis->mget('worker_1','worker_2','worker_3');
//          Yii::$app->redis->srem('district_1','16682');
//        Yii::$app->redis->sadd('district_1','16684','16683','16685','16686','16687','16688','16689','16682');
//        Yii::$app->redis->sadd('district_2','16694','16693','16695','16696','16697','16698','16699','16692');
//        Yii::$app->redis->sadd('worker_16694','10','11','9','8','7');
//        Yii::$app->redis->sadd('worker_16693','10','11');
        //$workers = Yii::$app->redis->smembers('district_1');
//        var_dump($workers);
//        die;
//        $time = date('H');
//        foreach($workers as $val){
//            $workerKey = 'worker_'.$val;
//            $workerIsBusy = Yii::$app->redis->sismember($workerKey,$time);
//            if(empty($workerIsBusy)){
//                $workerFreeArr[] = $val;
//            }
//        }
//        var_dump($workerFreeArr);
//        var_dump(Yii::$app->redis->sinter('worker_16983','worker_16694'));
    }


}
