<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
$workerVacationModel = new \common\models\WorkerVacation();
$workerModel = new \common\models\Worker();
?>

<?php

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
echo FormGrid::widget([
    'model'=>$workerModel,
    'form'=>$form,
    'autoGenerateColumns'=>true,
    'rows'=>[
        [
            'contentBefore'=>'<legend class="text-info"><small>阿姨审核</small></legend>',
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'审核状态',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
            ],
        ],
        [
            'contentBefore'=>'<legend class="text-info"><small>阿姨培训</small></legend>',
            'attributes'=>[       // 2 column layout
                //'worker_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter username...']],
                //'worker_password'=>['type'=>Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter password...']],
                'worker_auth_status'=>[   // radio list
                    'label'=>'培训状态',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[false=>'未通过', true=>'通过'],
                    'options'=>['inline'=>true]
                ],
            ],

        ],
        [
            'contentBefore'=>'<legend class="text-info"><small>阿姨试工</small></legend>',
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
            'contentBefore'=>'<legend class="text-info"><small>阿姨上岗</small></legend>',
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
                    'value'=>  '<div style="text-align: right; margin-top: 20px">' .
                        Html::resetButton('重置', ['class'=>'btn btn-default']) . ' ' .
                        Html::submitButton('提交', ['class'=>'btn btn-primary']) .
                        '</div>'
                ],
            ],
        ],
    ]
]);
ActiveForm::end();
?>
