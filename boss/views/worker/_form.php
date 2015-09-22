<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use kartik\grid\GridView;
use common\models\Worker;
use common\models\WorkerExt;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $worker
 * @var yii\widgets\ActiveForm $form
 */

$url = \yii\helpers\Url::to(['show-shop']);
//$cityDesc = empty($worker->shop_id) ? '' : Worker::findOne($worker->shop_id)->description;
$cityDesc = '门店';
//$worker->hasOne(WorkerExt::className(),'id=worker_id');
//var_dump($worker->workerExts);die;
?>

<div class="worker-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'id' => 'msg-form',
        //'options' => ['class'=>'form-horizontal'],
        //'enableAjaxValidation'=>false,
        'fieldConfig' => [
            //'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            //'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ]
    ]);

    echo $form->field($worker, 'shop_id')->widget(Select2::classname(), [
        'initValueText' => $cityDesc, // set the initial display text
        'options' => ['placeholder' => '搜索门店名称...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ],
    ]);

    echo $form->field($worker, 'worker_name')->textInput(['placeholder' => 'Enter 阿姨姓名...', 'maxlength' => 10]);
    echo $form->field($worker, 'worker_phone')->textInput(['placeholder' => 'Enter 阿姨手机...', 'maxlength' => 20]);
    echo $form->field($worker, 'worker_idcard')->textInput(['placeholder' => 'Enter 阿姨身份证号...', 'maxlength' => 20]);
    echo $form->field($worker, 'worker_work_city')->textInput(['placeholder' => 'Enter 阿姨工作城市...']);
    echo $form->field($worker, 'worker_work_area')->textInput(['placeholder' => 'Enter 阿姨工作区县...']);
    echo $form->field($worker, 'worker_work_street')->textInput(['placeholder' => 'Enter 阿姨常用工作地址...', 'maxlength' => 50]);
    echo $form->field($worker_ext, 'worker_sex')->radioList(['0' => '女', '1' => '男'], ['inline' => true]);
    echo $form->field($worker_ext, 'worker_age')->textInput(['placeholder' => 'Enter 阿姨工作区县...']);
    //echo '<label class="control-label">'.$model->attributeLabels()['operation_boot_page_online_time'].'</label>';

    echo $form->field($worker_ext, 'worker_birth')->widget(DatePicker::classname(),[
        'name' => 'worker_birth',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => date('Y-m-d', $worker_ext->worker_birth),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    //echo $form->field($worker_ext, 'worker_birth')->time(['placeholder' => 'Enter 阿姨生日...']);
    echo $form->field($worker_ext, 'worker_edu')->textInput(['placeholder' => 'Enter 阿姨教育程度...']);
    echo $form->field($worker_ext, 'worker_hometown')->textInput(['placeholder' => 'Enter 阿姨籍贯...']);
    echo $form->field($worker_ext, 'worker_is_health')->radioList(['1' => '是', '0' => '否'], ['inline' => true]);
    echo $form->field($worker_ext, 'worker_is_insurance')->radioList(['1' => '是', '0' => '否'], ['inline' => true]);
    echo $form->field($worker_ext, 'worker_source')->textInput(['placeholder' => 'Enter 阿姨来源...']);
    echo $form->field($worker, 'worker_type')->radioList(['0' => '自有', '1' => '非自有'], ['inline' => true]);

    echo $form->field($worker_ext, 'worker_bank_name')->textInput(['placeholder' => 'Enter 开户银行...']);
    echo $form->field($worker_ext, 'worker_bank_from')->textInput(['placeholder' => 'Enter 银行卡开户网点...']);
    echo $form->field($worker_ext, 'worker_bank_card')->textInput(['placeholder' => 'Enter 银行卡号...']);

    echo $form->field($worker_ext, 'worker_live_province')->textInput();
    echo $form->field($worker_ext, 'worker_live_city')->textInput();
    echo $form->field($worker_ext, 'worker_live_area')->textInput();
    echo $form->field($worker_ext, 'worker_live_street')->textInput();
    ?>
</div>
<?php
echo Html::submitButton($worker->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $worker->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

ActiveForm::end(); ?>
</div>
