<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

//use \dbbase\models\OperationCity;

use \core\models\operation\coupon\Coupon;
use \core\models\operation\OperationCity;

/**
 * @var yii\web\View $this
 * @var dbbase\models\coupon\Coupon $model
 */
$this->title = '创建优惠券';
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', ''), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//coupon categories
$coupon_categories = Coupon::getCategories();

//coupon types
$coupon_types = Coupon::getServiceTypes();
//service_cates
$service_cates = Coupon::getServiceCates();
//services
//city types
$city_types = Coupon::getCityTypes();
//citys
$cityOnlineList = OperationCity::getCityOnlineInfoList();
$cities = array();
if(!empty($cityOnlineList)){
	foreach($cityOnlineList as $value){
		$cities[$value['city_id']] = $value['city_name'];
	}
}
//customer types
$customer_types = Coupon::getCustomerTypes();
//time types
$time_types = Coupon::getTimeTypes();
//promote types
$promote_types = Coupon::getPromoteTypes();

?>
<div class="coupon-create">
    <div class="coupon-form">
        <?php if($model->hasErrors()):?>
        <div class="alert alert-danger" role="alert"><?=Html::errorSummary($model); ?></div>
        <?php endif;?>
        <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">优惠券基本信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_name')->textInput()->label('优惠券名称') ?>
				<?= $form->field($model, 'coupon_price')->textInput()->label('优惠券价值') ?>
            </div>

			<div class="panel-heading">
                <h3 class="panel-title">优惠券类型</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_category')->inline()->radioList($coupon_categories)->label('优惠券类型') ?>
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">优惠券服务类型规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_type')->inline()->radioList($coupon_types)->label('优惠券类型') ?>
				<?= $form->field($model, 'coupon_service_type_id')->dropDownList(ArrayHelper::map($service_cates, 'service_cate_id', 'service_cate_name'),['maxlength' => true])->label('服务类别') ?>
				<?= $form->field($model, 'coupon_service_id')->dropDownList([""=>"请选择服务"],['maxlength' => true])->label('服务') ?>
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">优惠券城市规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_city_limit')->inline()->radioList($city_types)->label('城市限制类型') ?>
                <?= $form->field($model, 'coupon_city_id')->inline()->radioList($cities)->label('城市') ?>
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">优惠券客户规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_customer_type')->inline()->radioList($customer_types)->label('适用客户类别') ?>
            </div>

			<div class="panel-heading">
                <h3 class="panel-title">优惠券时间规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_time_type')->inline()->radioList($time_types)->label('优惠券有效时间类型') ?>
				<?= $form->field($model, 'coupon_begin_at')->label('开始时间')->widget(
                    DatePicker::className(), [
                    'inline' => true,
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'startDate' => date('Y-m-d'),
                    ],
                    'language'=>'zh-CN'
                ]);?>
				<?= $form->field($model, 'coupon_end_at')->label('结束时间')->widget(
                    DatePicker::className(), [
                    'inline' => true,
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'startDate' => date('Y-m-d'),
                    ],
                    'language'=>'zh-CN'
                ]);?>
				<?= $form->field($model, 'coupon_get_end_at')->label('领取结束时间')->widget(
                    DatePicker::className(), [
                    'inline' => true,
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'startDate' => date('Y-m-d'),



                    ],
                    'language'=>'zh-CN'
                ]);?>
				<?= $form->field($model, 'coupon_use_end_days')->textInput(['maxlength' => 255])->label('领取后过期天数') ?>
            </div>


			<div class="panel-heading">
                <h3 class="panel-title">优惠券优惠规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_promote_type')->inline()->radioList($promote_types)->label('优惠券优惠类型') ?>
                <?= $form->field($model, 'coupon_order_min_price')->textInput()->label('订单最小金额') ?>
            </div>

			<div class="panel-heading">
                <h3 class="panel-title">优惠码规则</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'coupon_code_num')->textInput()->label('优惠码个数') ?>
                <?php //$form->field($model, 'coupon_code_max_customer_num')->textInput()->label('最大使用人数');
				?>
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
$this->registerJsFile('/js/coupon.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
?>

