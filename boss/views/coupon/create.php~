<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

use \core\models\coupon\Coupon;

/**
 * @var yii\web\View $this
 * @var common\models\coupon\Coupon $model
 */

$this->title = '创建优惠券';
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', ''), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//coupon types
$coupon_types = Coupon::getServiceTypes();
//service_types
//services
?>
<div class="order-create">
    <div class="order-form">
        <?php if($model->hasErrors()):?>
        <div class="alert alert-danger" role="alert"><?=Html::errorSummary($model); ?></div>
        <?php endif;?>
        <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">优惠券基本信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_name')->textInput(['maxlength' => 255])->label('优惠券名称'); ?>
				<?= $form->field($model, 'coupon_price')->textInput(['maxlength' => 255])->label('优惠券价值'); ?>
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">优惠券类型信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_type')->inline()->radioList(); ?>
				<?= $form->field($model, 'coupon_service_type_id')->dropDownList([""=>"请选择服务类别"],['maxlength' => true]) ?>
				<?= $form->field($model, 'coupon_service_id')->dropDownList([""=>"请选择服务"],['maxlength' => true]) ?>
                
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">优惠券城市规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_city_limit')->inline()->radioList(); ?>
                <?= $form->field($model, 'coupon_city_id')->inline()->radioList($service_types); ?>
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">优惠券客户规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_customer_type')->inline()->radioList(); ?>
            </div>

			<div class="panel-heading">
                <h3 class="panel-title">优惠券时间规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_time_type')->inline()->radioList(); ?>
                <?= $form->field($model, 'coupon_begin_at')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'coupon_end_at')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'coupon_get_end_at')->inline()->radioList([1=>'是',0=>'否'])->label(''); ?>
				<?= $form->field($model, 'coupon_get_end_at')->inline()->radioList([1=>'是',0=>'否'])->label(''); ?>
            </div>


			<div class="panel-heading">
                <h3 class="panel-title">优惠券优惠规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_promote_type')->inline()->radioList(); ?>
                <?= $form->field($model, 'coupon_order_min_price')->textInput(['maxlength' => true]) ?>
            </div>

			<div class="panel-heading">
                <h3 class="panel-title">优惠码规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_code_num')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'coupon_code_max_customer_num')->textInput(['maxlength' => true]) ?>
            </div>


            <div class="panel-footer">
                <div class="form-group">
                    <div class="col-sm-offset-0 col-sm-12">
                        <?= Html::submitButton('创建', ['class' =>'btn btn-warning btn-lg btn-block']); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php
$this->registerJsFile();
$this->registerCss();
?>
