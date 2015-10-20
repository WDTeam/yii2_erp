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
    <h3>阿姨状态：基础培训中 </h3>
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
if(1){
    $attributes =[       // 2 column layout
        'content'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px"><span style="font-weight: 700">审核状态：</span><span style="color:green">已通过</span></span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'worker_auth_status'=>[   // radio list
            'label'=>'审核状态',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'通过', true=>'未通过'],
            'options'=>['inline'=>true]
        ],
        'actions'=>[    // embed raw HTML content
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div style="text-align: left;float: right" >' .
                Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                '</div>',
            'columnOptions' => [ 'colspan' => 1 ],
        ],
    ];
}
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>审核</small></legend>',
            'options'=>[
                'style'=>'font-size:13px'
            ],
            'attributes'=>$attributes
        ],
    ]
]);
ActiveForm::end();
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>基础培训</small></legend>',
            'options'=>[
                'style'=>'font-size:14px;'
            ],
            'attributes'=>[       // 2 column layout
                'worker_auth_status'=>[   // radio list
                    'label'=>'基础培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true],
                    'columnOptions' => [ 'colspan' => 3 ],
                ],
                'actions'=>[    // embed raw HTML content
                    'type'=>Form::INPUT_RAW,
                    'value'=>  '<div style="text-align: left;float: right;height: 50px" >' .
                        Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                        '</div>',
                    'columnOptions' => [ 'colspan' => 1 ],
                ],
                'worker_rule_id'=>[   // radio list
                    'label'=>'工具培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true],
                    'columnOptions' => [ 'colspan' => 3 ],
                ],
                'worker_type'=>[   // radio list
                    'label'=>'实操培训',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true],
                    'columnOptions' => [ 'colspan' => 3 ],
                ],
            ],

        ],
    ]
]);
ActiveForm::end();

if(1){
    $attributes =[       // 2 column layout
        'content'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px"><span style="font-weight: 700">审核状态：</span>未设置</span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'worker_auth_status'=>[   // radio list
            'label'=>'初级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true],
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'actions'=>[    // embed raw HTML content
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div style="text-align: left;height:50px;float: right" >' .
                Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                '</div>',
            'columnOptions' => [ 'colspan' => 1 ],
        ],
        'worker_rule_id'=>[   // radio list
            'label'=>'中级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true],
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'worker_type'=>[   // radio list
            'label'=>'高级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true,],
            'columnOptions' => [ 'colspan' => 3 ],
        ],

    ];
}
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);

if(1){
    $attributes =[       // 2 column layout
        'content'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px"><span style="font-weight: 700">试工状态：</span>未设置</span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'content'=>[
            'label'=>'试工状态',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true]
        ],
        'worker_auth_status'=>[   // radio list
            'label'=>'试工状态',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true]
        ],
        'actions'=>[    // embed raw HTML content
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div style="text-align: left;float: right" >' .
                Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                '</div>',
            'columnOptions' => [ 'colspan' => 1 ],
        ],
    ];
}
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>试工</small></legend>',
            'options'=>[
                'style'=>'font-size:14px;'
            ],
            'attributes'=>$attributes,

        ],
    ]
]);
ActiveForm::end();

if(1){
    $attributes =[       // 2 column layout
        'content'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px"><span style="font-weight: 700">上岗状态：</span>未设置</span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'worker_auth_status'=>[   // radio list
            'label'=>'上岗状态',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true]
        ],
        'actions'=>[    // embed raw HTML content
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div style="text-align: left;float: right" >' .
                Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                '</div>',
            'columnOptions' => [ 'colspan' => 1 ],
        ],
    ];
}
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>上岗</small></legend>',
            'options'=>[
                'style'=>'font-size:14px;'
            ],
            'attributes'=>$attributes
        ],
    ]
]);
ActiveForm::end();


$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
if(1){
    $attributes =[       // 2 column layout
        'content'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px"><span style="font-weight: 700">晋升培训状态：</span>未设置</span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'worker_auth_status'=>[   // radio list
            'label'=>'初级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true],
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'actions'=>[    // embed raw HTML content
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div style="text-align: left;height:50px;float: right" >' .
                Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                '</div>',
            'columnOptions' => [ 'colspan' => 1 ],
        ],
        'worker_rule_id'=>[   // radio list
            'label'=>'中级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true],
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'worker_type'=>[   // radio list
            'label'=>'高级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[false=>'未通过', true=>'通过'],
            'options'=>['inline'=>true,],
            'columnOptions' => [ 'colspan' => 3 ],
        ],

    ];
}
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>晋升培训</small></legend>',
            'options'=>[
                'style'=>'font-size:14px;'
            ],
            'attributes'=>$attributes,
        ],
    ]
]);
ActiveForm::end();
?>
