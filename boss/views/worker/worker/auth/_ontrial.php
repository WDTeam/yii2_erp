<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use dbbase\models\WorkerBlock;
use yii\bootstrap\Modal;
use core\models\worker\Worker;
use core\models\worker\WorkerStat;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
?>

<?php
$workerStat = WorkerStat::find()->where(['worker_id'=>$workerAuthModel->worker_id])->one();

if($worker_auth_status>5){
    $attributes =[       // 2 column layout
        'stat'=>[
            'label'=>'试工信息',
            'type'=>Form::INPUT_RAW,
            'value'=>' <div style="height:35px;margin-left: 15px"><span style="margin-right: 15px">订单总数:'.$workerStat->worker_stat_order_num.'</span>   <span style="margin-right: 15px">拒单数:'.$workerStat->worker_stat_order_refuse.'</span>  <span style="margin-right: 15px">投诉数:'.$workerStat->worker_stat_order_complaint.'</span></div>',
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'actions'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px">试工状态：<span style="color:green">已通过</span></span>'
        ]
    ];
}elseif($worker_auth_status<4){
    $attributes =[       // 2 column layout
        'actions'=>[
            'type'=>Form::INPUT_RAW,
            'value'=>'<span style="display: inline-block;font-size: 14px;font-weight: 300;margin-bottom: 15px">试工状态：未设置</span>'
        ]
    ];
}else{
    $attributes =  [       // 2 column layout
        'stat'=>[
            'label'=>'试工信息',
            'type'=>Form::INPUT_RAW,
            'value'=>' <div style="height:35px"><span style="margin-right: 15px">订单总数:'.$workerStat->worker_stat_order_num.'</span>   <span style="margin-right: 15px">拒单数:'.$workerStat->worker_stat_order_refuse.'</span>  <span style="margin-right: 15px">投诉数:'.$workerStat->worker_stat_order_complaint.'</span></div>',
            'columnOptions' => [ 'colspan' => 3 ],
        ],
        'worker_ontrial_status'=>[   // radio list
            'label'=>'试工状态',
            'type'=>Form::INPUT_RADIO_LIST,
            'items'=>[1=>'通过',2=>'不通过'],
            'options'=>['inline'=>true],
            'columnOptions' => [ 'colspan' => 2 ],
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
    'model'=>$workerAuthModel,
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
?>
