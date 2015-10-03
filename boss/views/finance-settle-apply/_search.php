<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var boss\models\WorkerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$url = \yii\helpers\Url::to(['show-shop']);
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_work_city')->widget(Select2::classname(), [
            'name' => 'worker_rule_id',
            'hideSearch' => true,
            'data' => [1 => '北京', 2 => '上海', 3 => '成都', 4 => '深圳'],
            'options' => ['placeholder' => '选择城市', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
    <div class='col-md-3'>
        <?= $form->field($model, 'shop_id')->widget(Select2::classname(), [
            'initValueText' => '店铺', // set the initial display text
            'options' => ['placeholder' => '搜索门店名称...', 'class' => 'col-md-2'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ]); ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_type')->radioList(['1' => '自有', '2' => '非自有'], ['inline' => true]); ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_rule_id')->widget(Select2::classname(), [
                'name' => 'worker_rule_id',
                'hideSearch' => true,
                'data' => [1 => '全职', 2 => '兼职', 3 => '时段', 4 => '高峰'],
                'options' => ['placeholder' => '选择阿姨身份'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
    </div>
    <div class='col-md-2'>
    <?php echo  $form->field($model, 'created_ad')->widget(DatePicker::classname(),[
        'name' => 'create_time',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => date('Y-m-d', $model->created_ad),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    
    ?>
  </div>  
    <div class='col-md-2'>
    <?php echo  $form->field($model, 'updated_ad')->widget(DatePicker::classname(),[
        'name' => 'create_time',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => date('Y-m-d', $model->updated_ad),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    
    ?>
  </div> 
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_phone') ?>
    </div>


    <div class='col-md-2' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton(Yii::t('finance', 'Export'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
