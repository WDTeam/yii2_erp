<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use kartik\grid\GridView;
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

    <?php $form = ActiveForm::begin(
        ['type' => ActiveForm::TYPE_HORIZONTAL, 'id' => 'msg-form',
            //'options' => ['class'=>'form-horizontal'],
            //'enableAjaxValidation'=>false,
            'fieldConfig' => [
                //'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
                //'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ]
        ]);
    //    echo '<fieldset id="w2"><div class="form-group field-operationcity-operation_city_name required">';
    //            echo '<label class="control-label col-md-2" for="operationcity-operation_city_name">选择城市</label>';
    //            echo '<div class="col-md-10">'.Yii::$app->areacascade->cascadeAll('OperationCity', ['class' => 'form-control']).'</div>';
    //            echo '</div></fieldset>';
    ?>
    <div class="panel"><h3 style="margin-left: 30px">门店信息</h3>
        <?= $form->field($worker, 'worker_work_city')->widget(Select2::classname(), [
            'name' => 'worker_rule_id',
            'hideSearch' => true,
            'data' => [1 => '北京', 2 => '上海', 3 => '成都', 4 => '深圳'],
            'options' => ['placeholder' => '选择城市', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
        <?= $form->field($worker, 'shop_id')->widget(Select2::classname(), [
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
        ]); ?>
    </div>
    <div class="panel"><h3 style="margin-left: 30px">阿姨基本信息</h3>
        <?= $form->field($worker, 'worker_name')->textInput(['placeholder' => 'Enter 阿姨姓名...', 'maxlength' => 10]); ?>
        <?= $form->field($worker, 'worker_phone')->textInput(['placeholder' => 'Enter 阿姨手机...', 'maxlength' => 20]); ?>
        <?= $form->field($worker, 'worker_idcard')->textInput(['placeholder' => 'Enter 阿姨身份证号...', 'maxlength' => 20]); ?>
        <?= $form->field($worker_ext, 'worker_sex')->radioList(['0' => '女', '1' => '男'], ['inline' => true]); ?>
        <?= $form->field($worker_ext, 'worker_age')->textInput(['placeholder' => 'Enter 阿姨年龄...']); ?>

        <?= $form->field($worker_ext, 'worker_birth')->widget(DatePicker::classname(), [
            'name' => 'worker_birth',
            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
            'value' => date('Y-m-d', $worker_ext->worker_birth),
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]); ?>
        <?= $form->field($worker_ext, 'worker_source')->textInput(['placeholder' => 'Enter 阿姨来源...']); ?>
        <?= $form->field($worker_ext, 'worker_edu')->textInput(['placeholder' => 'Enter 阿姨教育程度...']); ?>
        <?= $form->field($worker_ext, 'worker_hometown')->textInput(['placeholder' => 'Enter 阿姨籍贯...']); ?>
        <?= $form->field($worker, 'worker_work_area')->textInput(['placeholder' => 'Enter 阿姨工作区县...']); ?>
        <?= $form->field($worker, 'worker_work_street')->textInput(['placeholder' => 'Enter 阿姨常用工作地址...', 'maxlength' => 50]); ?>
        <?= $form->field($worker_ext, 'worker_is_health')->radioList(['1' => '是', '0' => '否'], ['inline' => true]); ?>
        <?= $form->field($worker_ext, 'worker_is_insurance')->radioList(['1' => '是', '0' => '否'], ['inline' => true]); ?>
        <?= $form->field($worker, 'worker_type')->radioList(['1' => '自有', '2' => '非自有'], ['inline' => true]); ?>
        <?= $form->field($worker, 'worker_rule_id')->widget(Select2::classname(), [
            'name' => 'worker_rule_id',
            'hideSearch' => true,
            'data' => [1 => '全职', 2 => '兼职', 3 => '时段', 4 => '高峰'],
            'options' => ['placeholder' => '选择阿姨身份'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>


    <div class="panel"><h3 style="margin-left: 30px">结算相关信息</h3>
        <?= $form->field($worker_ext, 'worker_bank_name')->textInput(['placeholder' => 'Enter 开户银行...']); ?>
        <?= $form->field($worker_ext, 'worker_bank_from')->textInput(['placeholder' => 'Enter 银行卡开户网点...']); ?>
        <?= $form->field($worker_ext, 'worker_bank_card')->textInput(['placeholder' => 'Enter 银行卡号...']); ?>
    </div>
    <div class="panel"><h3 style="margin-left: 30px">审核相关信息</h3>
        <?= $form->field($worker, 'worker_auth_status')->radioList(['1' => '已通过', '0' => '未通过'], ['inline' => true]); ?>
        <?= $form->field($worker, 'worker_ontrial_status')->radioList(['1' => '已试工', '0' => '未试工'], ['inline' => true]); ?>
        <?= $form->field($worker, 'worker_onboard_status')->radioList(['1' => '已上岗', '0' => '未上岗'], ['inline' => true]); ?>
    </div>

</div>
<?php
echo Html::submitButton($worker->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $worker->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

ActiveForm::end(); ?>
</div>
