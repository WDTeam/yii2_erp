<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\FileInput

/**
 * @var yii\web\View $this
 * @var boss\models\FinancePopOrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-pop-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);

    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); 
    
    ?>


   
 <div class='col-md-2'>
    <?= $form->field($model, 'finance_order_channel_id')->widget(Select2::classname(), [
        'name' => '订单渠道',
        'hideSearch' => true,
        'data' => $odrinfo,
        'options' => ['placeholder' => '选择订单渠道','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     </div>
    <div class='col-md-2'>
<div class='col-md-2' style="margin-top: 22px;">
<?php echo FileInput::widget([
		'name' => 'attachments',
		'options' => ['multiple' => true],
		'pluginOptions' => ['previewFileType' => 'any']
		]);

?>
</div>
    </div> 
     
    <?//= $form->field($model, 'finance_order_channel_title') ?>

    <?// $form->field($model, 'finance_pay_channel_id') ?>

    <?php // echo $form->field($model, 'finance_pay_channel_title') ?>

    <?php // echo $form->field($model, 'finance_pop_order_customer_tel') ?>

    <?php // echo $form->field($model, 'finance_pop_order_worker_uid') ?>

    <?php // echo $form->field($model, 'finance_pop_order_booked_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_booked_counttime') ?>

    <?php // echo $form->field($model, 'finance_pop_order_sum_money') ?>

    <?php // echo $form->field($model, 'finance_pop_order_coupon_count') ?>

    <?php // echo $form->field($model, 'finance_pop_order_coupon_id') ?>

    <?php // echo $form->field($model, 'finance_pop_order_order2') ?>

    <?php // echo $form->field($model, 'finance_pop_order_channel_order') ?>

    <?php // echo $form->field($model, 'finance_pop_order_order_type') ?>

    <?php // echo $form->field($model, 'finance_pop_order_status') ?>

    <?php // echo $form->field($model, 'finance_pop_order_finance_isok') ?>

    <?php // echo $form->field($model, 'finance_pop_order_discount_pay') ?>

    <?php // echo $form->field($model, 'finance_pop_order_reality_pay') ?>

    <?php // echo $form->field($model, 'finance_pop_order_order_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_pay_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_pay_status') ?>

    <?php // echo $form->field($model, 'finance_pop_order_pay_title') ?>

    <?php // echo $form->field($model, 'finance_pop_order_check_id') ?>

    <?php // echo $form->field($model, 'finance_pop_order_finance_time') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
