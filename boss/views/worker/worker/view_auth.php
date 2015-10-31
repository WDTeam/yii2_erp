<?php
use yii\bootstrap\Modal;
use boss\models\worker\WorkerBlock;
use boss\models\worker\Worker;
use boss\models\worker\WorkerStat;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
$workerModel = Worker::findOne($worker_id);
$worker = Worker::find()->select('worker_name,worker_auth_status')->where(['id'=>$worker_id])->one();

if($worker->worker_auth_status==0){
    $btnState = ['disabled'=>'disabled'];
    if($workerAuthModel->worker_auth_status==0){
        $currentAuthState= '审核中';
    }elseif($workerAuthModel->worker_auth_status==2){
        $currentAuthState = '审核不通过';
    }
}elseif($worker->worker_auth_status==1){
    $btnState = ['disabled'=>'disabled'];
    if($workerAuthModel->worker_basic_training_status==0){
        $currentAuthState= '基础培训中';
    }elseif($workerAuthModel->worker_basic_training_status==2){
        $currentAuthState = '基础培训不通过';
    }
}elseif($worker->worker_auth_status==2){
    $btnState = ['disabled'=>'disabled'];
    if($workerAuthModel->worker_ontrial_status==0){
        $currentAuthState= '试工中';
    }elseif($workerAuthModel->worker_ontrial_status=2){
        $currentAuthState = '试工不通过';
    }
}elseif($worker->worker_auth_status==3){
    $btnState = [];
    if($workerAuthModel->worker_onboard_status==0){
        $currentAuthState= '上岗中';
    }elseif($workerAuthModel->worker_onboard_status==2){
        $currentAuthState = '上岗不通过';
    }
}elseif($worker->worker_auth_status==4){
    $btnState = [];
    if($workerAuthModel->worker_upgrade_training_status==0){
        $currentAuthState= '晋升培训中';
    }elseif($workerAuthModel->worker_upgrade_training_status==2){
        $currentAuthState = '晋升培训不通过';
    }
}elseif($worker->worker_auth_status==5){
    $btnState = [];
    $currentAuthState= '晋升培训通过';
}

?>
<div class="panel">
<div class="panel panel-body">
<div style="margin-bottom: 15px">
    <h3>阿姨状态：<?=$currentAuthState?> </h3>
</div>

<?php
Modal::begin(['header' => '<h4 class="modal-title">封号操作</h4>','toggleButton' => array_merge($btnState ,['label' => '<i ></i>封号', 'class' => 'btn btn-success'])]);
echo $this->render('operate/_operate_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name']]);
Modal::end();

Modal::begin(['header' => '<h4 class="modal-title">休假操作</h4>','toggleButton' => array_merge($btnState ,['label' => '<i ></i>休假', 'class' => 'btn btn-success'])]);
echo $this->render('operate/_operate_vacation_1',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name']]);
Modal::end();

Modal::begin(['header' => '<h4 class="modal-title">事假操作</h4>', 'toggleButton' => array_merge($btnState ,['label' => '<i ></i>事假', 'class' => 'btn btn-success'])]);
echo $this->render('operate/_operate_vacation_2',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name']]);
Modal::end();

Modal::begin(['header' => '<h4 class="modal-title">黑名单操作</h4>', 'toggleButton' => array_merge($btnState ,['label' => '<i ></i>黑名单', 'class' => 'btn btn-success'])]);
echo $this->render('operate/_operate_blacklist',['workerModel'=>$workerModel]);
Modal::end();

Modal::begin(['header' => '<h4 class="modal-title">离职操作</h4>','toggleButton' => array_merge($btnState ,['label' => '<i ></i>离职', 'class' => 'btn btn-success'])]);
echo $this->render('operate/_operate_dimission',['workerModel'=>$workerModel]);
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

<?php
$this->registerJs(<<<JSCONTENT
        $('#vacation_1').on('click',function(){
            $('#workervacation-worker_vacation_type').val('')
        })
        $('#vacation_2').on('click',function(){
            $('#workervacation-worker_vacation_type').val('')
        })
JSCONTENT
);
?>