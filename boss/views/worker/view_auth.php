<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use common\models\WorkerBlock;
use yii\bootstrap\Modal;
use core\models\worker\Worker;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
$workerVacationModel = new \common\models\WorkerVacation();
$workerModel = new \common\models\Worker();
$worker = Worker::find()->select('worker_name')->where(['id'=>$worker_id])->one();
$workerBlockModel = WorkerBlock::find()->where(['worker_id'=>$worker_id])->one();

if($workerBlockModel!==null){
    $workerBlockModel->worker_block_start_time = date('Y-m-d',$workerBlockModel->worker_block_start_time);
    $workerBlockModel->worker_block_finish_time = date('Y-m-d',$workerBlockModel->worker_block_finish_time);
}else{
    $workerBlockModel = new WorkerBlock();
    $workerBlockModel->worker_block_status = 1;
}
?>
<div style="margin-bottom: 15px">
    <h3>阿姨状态：上岗 封号中</h3>
</div>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">封号操作</h4>',
    'toggleButton' => ['label' => '<i ></i>封号', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">休假操作</h4>',
    'toggleButton' => ['label' => '<i ></i>休假', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">事假操作</h4>',
    'toggleButton' => ['label' => '<i ></i>事假', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">黑名单操作</h4>',
    'toggleButton' => ['label' => '<i ></i>黑名单', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">离职操作</h4>',
    'toggleButton' => ['label' => '<i ></i>离职', 'class' => 'btn btn-success']
]);
echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
Modal::end();
?>
<div style="height:20px"></div>
<?php
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>审核</small></legend>',
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'审核状态',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'通过', true=>'未通过'],
                    'options'=>['inline'=>true,'disabled'=>true]
                ],
            ],
        ],
        [
            'contentBefore'=>'<legend class="text-info"><small>基础培训</small></legend>',
            'columnOptions' => [ 'colspan' => 2 ],
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'基础培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
                'worker_rule_id'=>[   // radio list
                    'label'=>'工具培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
                'worker_type'=>[   // radio list
                    'label'=>'实操培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
            ],
        ],
        [
            'contentBefore'=>'<legend class="text-info"><small>试工</small></legend>',
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'试工状态',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
            ],

        ],
        [
            'contentBefore'=>'<legend class="text-info"><small>上岗</small></legend>',
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'上岗状态',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
            ],

        ],
        [
            'contentBefore'=>'<legend class="text-info"><small>晋升培训</small></legend>',
            'columnOptions' => [ 'colspan' => 2 ],
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'初级培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true,'width'=>'10%'],
                ],
                'worker_rule_id'=>[   // radio list
                    'label'=>'中级培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true,'width'=>'10%']
                ],
                'worker_type'=>[   // radio list
                    'label'=>'高级培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true,'width'=>'90%']
                ],
            ],
        ],
//        [
//            'attributes'=>[       // 1 column layout
//                'worker_name'=>['type'=>Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter notes...']],
//            ],
//        ],
//        [
//            'contentBefore'=>'<legend class="text-info"><small>Profile Info</small></legend>',
//            'columns'=>3,
//            'autoGenerateColumns'=>false, // override columns setting
//            'attributes'=>[       // colspan example with nested attributes
//                'worker_name' => [
//                    'label'=>'Address',
//                    'columns'=>6,
//                    'attributes'=>[
//                        'worker_name'=>[
//                            'type'=>Form::INPUT_TEXT,
//                            'options'=>['placeholder'=>'Enter address...'],
//                            'columnOptions'=>['colspan'=>3],
//                        ],
//                        'worker_name'=>[
//                            'type'=>Form::INPUT_TEXT,
//                            'options'=>['placeholder'=>'Zip...'],
//                            'columnOptions'=>['colspan'=>2],
//                        ],
//                        'worker_name'=>[
//                            'type'=>Form::INPUT_TEXT,
//                            'options'=>['placeholder'=>'Phone...']
//                        ],
//                    ]
//                ]
//            ],
//        ],
//        [
//            'attributes'=>[
//                'birthday'=>['type'=>Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\DatePicker', 'hint'=>'Enter birthday (mm/dd/yyyy)'],
//                'state_1'=>['type'=>Form::INPUT_DROPDOWN_LIST, 'items'=>[1=>'2'], 'hint'=>'Type and select state'],
//                'color'=>['type'=>Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\ColorInput', 'hint'=>'Choose your color'],
//            ]
//        ],
        [
            'attributes'=>[       // 3 column layout

//                'worker_photo'=>[   // uses widget class with widget options
//                    'type'=>Form::INPUT_WIDGET,
//                    'label'=>Html::label('Brightness (%)'),
//                    'widgetClass'=>'\kartik\widgets\RangeInput',
//                    'options'=>['width'=>'80%']
//                ],
                'actions'=>[    // embed raw HTML content
                    'type'=>Form::INPUT_RAW,
                    'value'=>  '<div style="text-align: left; margin-top: 20px">' .
                        Html::submitButton('提交', ['class'=>'btn btn-primary']) .
                        '</div>'
                ],
            ],
        ],
    ]
]);
ActiveForm::end();
?>
