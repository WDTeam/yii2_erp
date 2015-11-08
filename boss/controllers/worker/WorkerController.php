<?php

namespace boss\controllers\worker;



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
        if(Yii::$app->request->get('getData')==1){
            $this->actionGetDataFromOldDataBase();
            $this->redirect(['index']);
        }
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
            //更新阿姨商圈信息 ???
            $workerDistrictModel = new WorkerDistrict;
            $workerParam = Yii::$app->request->post('Worker');
            $workerDistrictModel->deleteAll(['worker_id'=>$id]);
            Worker::deleteDistrictWorkerRelationToRedis($workerModel->id);
            Worker::updateWorkerInfoToRedis($workerModel->id,$workerModel->worker_phone,$workerModel->worker_type,$workerModel->worker_identity_id);
            if($workerParam['worker_district']){
                foreach($workerParam['worker_district'] as $val){
                    $workerDistrictModel = new WorkerDistrict;
                    $workerDistrictModel->created_ad = time();
                    $workerDistrictModel->worker_id = $id;
                    $workerDistrictModel->operation_shop_district_id = $val;
                    $workerDistrictModel->save();
                }
                //更新商圈绑定阿姨到redis
                $operateStatus = Worker::operateDistrictWorkerRelationToRedis($id,$workerParam['worker_district']);
                if($operateStatus==false){
                    throw new ServerErrorHttpException('更新商圈绑定阿姨到缓存失败');
                }
            }
            return $this->redirect(['view', 'id' => $workerModel->id]);
        } else {
//            $workerBlockData = new ActiveDataProvider([
//                'query' => WorkerBlock::find()->select("*,from_unixtime(`worker_block_finish_time`,'%Y-%m-%d')  as `finish_time`")->where(['worker_id'=>$id])->orderBy('id desc'),
//            ]);
            $workerVacationData = new ActiveDataProvider([
                'query' => WorkerVacation::find()->where(['worker_id'=>$id])->orderBy('id desc'),
            ]);
            $workerBlockLogData = new ActiveDataProvider([
                'query' => WorkerBlockLog::find()->where(['worker_id'=>$id])->orderBy('id desc'),
            ]);
            $schedule = WorkerSchedule::find()->where(['worker_id'=>$workerModel->id])->asArray()->all();

            return $this->render('view', ['workerModel' => $workerModel,'worker_id'=>$id,'workerVacationData'=>$workerVacationData,'workerBlockLogData'=>$workerBlockLogData,'schedule'=>$schedule]);
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
            Worker::updateWorkerScheduleInfoToRedis($id);
          }
        return $this->redirect(['view', 'id' => $id,'tab'=>2]);
    }


    public function actionOperateBlacklist($id){
        $workerModel = $this->findModel($id);
        if($workerModel->load(Yii::$app->request->post())){
            $workerModel->worker_blacklist_time = time();
            $workerModel->save();
        }
        return $this->redirect(['auth', 'id' => $id]);
    }

    public function actionOperateDimission($id){
        $workerModel = $this->findModel($id);
        if($workerModel->load(Yii::$app->request->post())){
            $workerModel->worker_dimission_time = time();
            $workerModel->save();
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
                Worker::addWorkerInfoToRedis($workerModel->id,$workerModel->worker_phone,$workerModel->worker_type,$workerModel->worker_identity_id);
                if($workerParam['worker_district']){
                    foreach($workerParam['worker_district'] as $val){
                        $workerDistrictModel = new WorkerDistrict;
                        $workerDistrictModel->created_ad = time();
                        $workerDistrictModel->worker_id = $workerModel->id;
                        $workerDistrictModel->operation_shop_district_id = $val;
                        $workerDistrictModel->save();
                    }
                    $operateStatus = Worker::operateDistrictWorkerRelationToRedis($workerModel->id,$workerParam['worker_district']);
                    if($operateStatus==false){
                        throw new ServerErrorHttpException('更新商圈绑定阿姨到缓存失败');
                    }
                }
                return $this->redirect(['view', 'id' => $workerModel->id,'tab'=>2]);
            }else{
                var_dump($workerModel->errors);
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
            $workerModel = $this->findModel($worker_id);
            $workerModel->load(Yii::$app->request->post());
            return \yii\bootstrap\ActiveForm::validate($workerModel,['worker_phone']);
        //添加阿姨
        }else{
//            $workerModel = Worker::findAll(['isdel'=>0]);
            $workerModel = new Worker;
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
        $model = $this->findModel($id);

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
            $workerModel = $this->findModel($id);
            if($workerAuthModel->load(Yii::$app->request->post()) && $workerAuthModel->save()){
                if(isset($param['worker_auth_status']) && $param['worker_auth_status']==1){
                    $workerModel->worker_auth_status = 1;
                    $workerModel->save();
                    var_dump($workerModel->errors);die;
                }elseif(isset($param['worker_basic_training_status']) && $param['worker_basic_training_status']==1){
                    $workerModel->worker_auth_status = 2;
                    $workerModel->save();
                } elseif(isset($param['worker_ontrial_status']) && $param['worker_ontrial_status']==1){
                    $workerModel->worker_auth_status = 3;
                    $workerModel->save();
                }elseif(isset($param['worker_onboard_status']) && $param['worker_onboard_status']==1){
                    $workerModel->worker_auth_status = 4;
                    $workerModel->save();
                }elseif(isset($param['worker_upgrade_training_status']) && $param['worker_upgrade_training_status']==1){
                    $workerModel->worker_auth_status = 5;
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
        $model=$this->findModel($id);
        $model->isdel = 1;
        $model->save();
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
        $condition = '';
        if($city_id){
            $condition = ['city_id'=>$city_id];
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
            $workerBlockModel->worker_block_status = intval($param['worker_block_status']);
            //为更改前的阿姨封号属性
            $oldAttributes = $workerBlockModel->oldAttributes;
            //更改的阿姨封号属性
            $modifiedAttributes = $workerBlockModel->getDirtyAttributes();
            //记录阿姨日志
            if($workerBlockModel->save()){
                $workerModel = $this->findModel($workerId);
                if(empty($param['id'])){
                    if($param['worker_block_status']==1){
                        $workerModel->worker_is_block = 1;
                        $workerModel->save();
                    }elseif($modifiedAttributes['worker_block_status']==0){
                        $workerModel->worker_is_block = 0;
                        $workerModel->save();
                    }
                    $this->CreateBlockLog($workerId,$workerBlockModel->id,1);
                }else{
                    if(isset($modifiedAttributes['worker_block_finish_time'])){
                        if($modifiedAttributes['worker_block_finish_time']<$oldAttributes['worker_block_finish_time']){
                            $this->CreateBlockLog($workerId,$workerBlockModel->id,2);
                        }elseif($modifiedAttributes['worker_block_finish_time']>$oldAttributes['worker_block_finish_time']){
                            $this->CreateBlockLog($workerId,$workerBlockModel->id,3);
                        }
                    }
                    if(isset($modifiedAttributes['worker_block_status'])){
                        if($modifiedAttributes['worker_block_status']==1){
                            $workerModel->worker_is_block = 1;
                            $workerModel->save();
                            //记录日志记录
                            $this->CreateBlockLog($workerId,$workerBlockModel->id,5);
                        }elseif($modifiedAttributes['worker_block_status']==0){
                            $workerModel->worker_is_block = 0;
                            $workerModel->save();
                            //记录日志记录
                            $this->CreateBlockLog($workerId,$workerBlockModel->id,4);
                        }
                    }

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

    public static function exportDataFromMysqlToRedis(){

    }

    public function actionTest1(){
        Worker::operateWorkerOrderInfoToRedis(19077,1,123,2,1446681600,1446685200);
    }

    public function actionTest(){
        echo '<pre>';
        $a = Worker::findAllModel(['isdel'=>0],1);
        var_dump($a);
        //var_dump(Worker::getWorkerDetailInfo(19077));die;
        //echo '星期1 8:00 10:00';
        //echo date('Y-m-d H:i',1446253200);
        //echo '<br>';
        var_dump(WorkerVacationApplication::getApplicationTimeLine(19074,1));die;
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
