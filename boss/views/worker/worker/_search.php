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

?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?php if(!\Yii::$app->user->identity->isNotAdmin()){ ?>
    <div class='col-md-2'>
        <?php echo $form->field($model, 'worker_work_city')->widget(Select2::classname(), [
            'name' => 'worker_work_city',
            'hideSearch' => true,
            'data' => $model::getOnlineCityList(),
            'options' => ['placeholder' => '选择城市...', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents'=> [
                "change" => "function() {
                            $('#select2-workersearch-shop_id-container>.select2-selection__clear').mousedown();
                         }",
            ]
        ]); ?>
    </div>
    <div class='col-md-3'>
        <?php echo  $form->field($model, 'shop_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => '搜索门店名称...', 'class' => 'col-md-2'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['show-shop']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {
                            city_id:$("#workersearch-worker_work_city").val(),
                            q:params.term,
                      };
                    }')
                ],
                'initSelection'=> new JsExpression('function (element, callback) {
                        callback({
                            shop_id:"'.$model->shop_id.'",
                            text:"'.$model->getShopName($model->shop_id).'"
                        });
                }'),
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
            'pluginEvents'=> [
                "change" => "function() { $('#select2-worker-shop_id-container>.select2-selection__clear').mousedown()}",
            ]
        ]); ?>
    </div>
    <?php } ?>
    <div class='col-md-3'>
        <?php echo $form->field($model, 'worker_type')->widget(Select2::classname(), [
            'name' => 'worker_type',
            'hideSearch' => true,
            'data' => ['1' => '自有', '2' => '非自有'],
            'options' => ['placeholder' => '选择类型', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_name') ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_phone') ?>
    </div>

    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>