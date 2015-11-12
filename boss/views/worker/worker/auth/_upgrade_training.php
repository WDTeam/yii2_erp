<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use dbbase\models\WorkerBlock;
use yii\bootstrap\Modal;
use core\models\worker\Worker;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
?>

<?php

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
if($worker_auth_status>9){
    $attributes =[       // 2 column layout
        'actions'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px">晋升培训状态：<span style="color:green">已通过</span></span>'
        ]
    ];
}elseif($worker_auth_status<8){
    $attributes =[       // 2 column layout
        'actions'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px">晋升培训状态：未设置</span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'worker_upgrade_training_status'=>[   // radio list
            'label'=>'初级培训',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[1=>'通过',2=>'不通过'],
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
        'content1'=>[   // radio list
            'label'=>'中级培训',
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div class="form-group field-workerauth-worker_ontrial_failed_reason">
                        <label class="control-label" for="workerauth-worker_ontrial_failed_reason">中级培训</label>
                        <div id="workerauth-worker_ontrial_failed_reason">
                        <label class="radio-inline"><input name="WorkerAuth[worker_ontrial_failed_reason1]" value="0" type="radio"> 通过</label>
                        <label class="radio-inline"><input name="WorkerAuth[worker_ontrial_failed_reason1]" value="1" type="radio"> 不通过</label>
                        </div>
                        <div class="help-block"></div>
                        </div>',
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'content2'=>[   // radio list
            'label'=>'高级培训',
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div class="form-group field-workerauth-worker_ontrial_failed_reason">
                        <label class="control-label" for="workerauth-worker_ontrial_failed_reason">高级培训</label>
                        <div id="workerauth-worker_ontrial_failed_reason">
                        <label class="radio-inline"><input name="WorkerAuth[worker_ontrial_failed_reason2]" value="0" type="radio"> 通过</label>
                        <label class="radio-inline"><input name="WorkerAuth[worker_ontrial_failed_reason2]" value="1" type="radio"> 不通过</label>
                        </div>
                        <div class="help-block"></div>
                        </div>',
            'columnOptions' => [ 'colspan' => 3 ],
        ],
    ];
}
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo FormGrid::widget([
    'model'=>$workerAuthModel,
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
