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
if($worker_auth_status>=1){
    $attributes =[       // 2 column layout
        'actions'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px">审核状态：<span style="color:green">已通过</span></span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'worker_auth_status'=>[   // radio list
            'label'=>'审核状态',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[1=>'通过',2=>'不通过'],
            'options'=>['inline'=>true],
        ],
        'actions'=>[    // embed raw HTML content
            'type'=>Form::INPUT_RAW,
            'value'=>  '<div style="text-align: left;height:50px;float: right" >' .
                Html::submitButton('保存', ['class'=>'btn btn-primary']) .
                '</div>',
            'columnOptions' => [ 'colspan' => 1 ],
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
            'contentBefore'=>'<legend class="text-info"><small>审核</small></legend>',
            'options'=>[
                'style'=>'font-size:14px;'
            ],
            'attributes'=>$attributes,

        ],
    ]
]);
ActiveForm::end();
?>
