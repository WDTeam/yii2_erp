<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

use \core\models\operation\OperationCity;

//use \common\models\Operation\OperationCity;

/**
 * @var yii\web\View $this
 * @var boss\models\CouponSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class='col-md-2'>
        <?php
		$cityOnlineList = OperationCity::getCityOnlineInfoList();
		$cities = array();
		if(!empty($cityOnlineList)){
			foreach($cityOnlineList as $value){
				$cities[$value['city_id']] = $value['city_name'];
			}
		}
        echo $form->field($model, 'coupon_city_id')->widget(Select2::classname(), [
            'name' => 'id',
            'hideSearch' => true,
            'data' => array(),
            'options' => ['placeholder' => '选择城市', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
       ?>
    </div>


    <?php 
	//$form->field($model, 'id') 
	?>
	
	<div class='col-md-2'>
    	<?= $form->field($model, 'coupon_name')->label('请输入优惠券名称') ?>
	</div>

	<div class='col-md-2'>
    	<?= $form->field($model, 'coupon_code')->label('请输入优惠码') ?>
	</div>

    <?php
	// $form->field($model, 'coupon_price') 
	?>

    <?php
	// $form->field($model, 'coupon_type') 
	?>

    <?php
	// $form->field($model, 'coupon_type_name') 
	?>

    <?php // echo $form->field($model, 'coupon_service_type_id') 
	?>

    <?php // echo $form->field($model, 'coupon_service_type_name') ?>

    <?php // echo $form->field($model, 'coupon_service_id') ?>

    <?php // echo $form->field($model, 'coupon_service_name') ?>

    <?php // echo $form->field($model, 'coupon_city_limit') ?>

    <?php // echo $form->field($model, 'coupon_city_id') ?>

    <?php // echo $form->field($model, 'coupon_city_name') ?>

    <?php // echo $form->field($model, 'coupon_customer_type') ?>

    <?php // echo $form->field($model, 'coupon_customer_type_name') ?>

    <?php // echo $form->field($model, 'coupon_time_type') ?>

    <?php // echo $form->field($model, 'coupon_time_type_name') ?>

    <?php // echo $form->field($model, 'coupon_begin_at') ?>

    <?php // echo $form->field($model, 'coupon_end_at') ?>

    <?php // echo $form->field($model, 'coupon_get_end_at') ?>

    <?php // echo $form->field($model, 'coupon_use_end_days') ?>

    <?php // echo $form->field($model, 'coupon_promote_type') ?>

    <?php // echo $form->field($model, 'coupon_promote_type_name') ?>

    <?php // echo $form->field($model, 'coupon_order_min_price') ?>

    <?php // echo $form->field($model, 'coupon_code_num') ?>

    <?php // echo $form->field($model, 'coupon_code_max_customer_num') ?>

    <?php // echo $form->field($model, 'is_disabled') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'system_user_id') ?>

    <?php // echo $form->field($model, 'system_user_name') ?>

   	<div class="form-group">
        <div class='col-md-2' style="margin-top: 22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
