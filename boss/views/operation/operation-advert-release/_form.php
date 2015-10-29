<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\datecontrol\Module;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationAdvertRelease $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="operation-advert-release-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'advert_content_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 广告内容编号...']], 

//'city_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市编号...']], 

//'is_softdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否删除...']], 

//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

        'starttime'=>[
            'label'=> '上线时间',
            'type'=> Form::INPUT_TEXT,
            'options'=>['placeholder'=>'请输入上线时间...',
            'maxlength'=>32]
        ],
        'endtime'=>[
            'label'=> '下线时间',
            'type'=> Form::INPUT_TEXT,
            'options'=>['placeholder'=>'请输入下线时间...',
            'maxlength'=>32]
        ],
        'status'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>[0 => '未上线', 1 => '上线', 2 => '下线'], 'label'=>'在线状态', 'options'=>['placeholder'=>'请输入在线状态：1上线，2下线...']],
        //'starttime'=>
        //[
            //'type'=> Form::INPUT_WIDGET,
            //'widgetClass'=>DateControl::classname(),
            //'options'=>['type'=>DateControl::FORMAT_DATETIME]
        //], 

        //'endtime'=>
        //[
            //'type'=> Form::INPUT_WIDGET,
            //'widgetClass'=>DateControl::classname(),
            //'options'=>['type'=>'datetime']
        //], 

//'city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市名称...', 'maxlength'=>60]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
