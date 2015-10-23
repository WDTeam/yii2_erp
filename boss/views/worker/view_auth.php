<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use common\models\WorkerBlock;
use yii\bootstrap\Modal;
use core\models\worker\Worker;
use core\models\worker\WorkerStat;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
$workerModel = Worker::findOne($worker_id);
$worker = Worker::find()->select('worker_name,worker_auth_status')->where(['id'=>$worker_id])->one();

$workerBlockModel = WorkerBlock::find()->where(['worker_id'=>$worker_id])->one();
if($workerBlockModel!==null){
    $workerBlockModel->worker_block_start_time = date('Y-m-d',$workerBlockModel->worker_block_start_time);
    $workerBlockModel->worker_block_finish_time = date('Y-m-d',$workerBlockModel->worker_block_finish_time);
}else{
    $workerBlockModel = new WorkerBlock();
    $workerBlockModel->worker_block_status = 1;
}
if($worker->worker_auth_status==0){
    if($workerAuthModel->worker_auth_status==0){
        $currentAuthState= '审核中';
    }elseif($workerAuthModel->worker_auth_status==2){
        $currentAuthState = '审核不通过';
    }
}elseif($worker->worker_auth_status==1){
    if($workerAuthModel->worker_basic_training_status==0){
        $currentAuthState= '基础培训中';
    }elseif($workerAuthModel->worker_basic_training_status==2){
        $currentAuthState = '基础培训不通过';
    }
}elseif($worker->worker_auth_status==2){
    if($workerAuthModel->worker_ontrial_status==0){
        $currentAuthState= '试工中';
    }elseif($workerAuthModel->worker_ontrial_status=2){
        $currentAuthState = '试工不通过';
    }
}elseif($worker->worker_auth_status==3){
    if($workerAuthModel->worker_onboard_status==0){
        $currentAuthState= '上岗中';
    }elseif($workerAuthModel->worker_onboard_status==2){
        $currentAuthState = '上岗不通过';
    }
}elseif($worker->worker_auth_status==4){
    if($workerAuthModel->worker_upgrade_training_status==0){
        $currentAuthState= '晋升培训中';
    }elseif($workerAuthModel->worker_upgrade_training_status==2){
        $currentAuthState = '晋升培训不通过';
    }
}elseif($worker->worker_auth_status==5){
    $currentAuthState= '已通过晋升培训';
}

?>
<div class="panel">
    <div class="panel panel-body">
<div style="margin-bottom: 15px">
    <h3>阿姨状态：<?=$currentAuthState?> </h3>
</div>

<?php
Modal::begin([
    'header' => '<h4 class="modal-title">封号操作</h4>',
    'toggleButton' => ['label' => '<i ></i>封号', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();

Modal::begin([
    'header' => '<h4 class="modal-title">休假操作</h4>',
    'toggleButton' => ['label' => '<i ></i>休假', 'class' => 'btn btn-success']
]);
echo $this->render('create_vacation',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'type'=>1]);
Modal::end();

Modal::begin([
    'header' => '<h4 class="modal-title">事假操作</h4>',
    'toggleButton' => ['label' => '<i ></i>事假', 'class' => 'btn btn-success']
]);
echo $this->render('create_vacation',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'type'=>2]);
Modal::end();

Modal::begin([
    'header' => '<h4 class="modal-title">黑名单操作</h4>',
    'toggleButton' => ['label' => '<i ></i>黑名单', 'class' => 'btn btn-success']
]);
echo $this->render('operate_blacklist',['workerModel'=>$workerModel]);
Modal::end();

Modal::begin([
    'header' => '<h4 class="modal-title">离职操作</h4>',
    'toggleButton' => ['label' => '<i ></i>离职', 'class' => 'btn btn-success']
]);
echo $this->render('operate_dimission',['workerModel'=>$workerModel]);
Modal::end();

?>

<div style="height:20px"></div>
<?php

echo $this->render('auth/_auth',['workerAuthModel'=>$workerAuthModel,'worker_auth_status'=>$worker->worker_auth_status]);
echo $this->render('auth/_basic_training',['workerAuthModel'=>$workerAuthModel,'worker_auth_status'=>$worker->worker_auth_status]);
echo $this->render('auth/_ontrial',['workerAuthModel'=>$workerAuthModel,'worker_auth_status'=>$worker->worker_auth_status]);
echo $this->render('auth/_onboard',['workerAuthModel'=>$workerAuthModel,'worker_auth_status'=>$worker->worker_auth_status]);
echo $this->render('auth/_upgrade_training',['workerAuthModel'=>$workerAuthModel,'worker_auth_status'=>$worker->worker_auth_status]);




?>
</div>
</div>