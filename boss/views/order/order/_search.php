<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use boss\models\order\Order;

/**
 * @var yii\web\View $this
 * @var core\models\order\OrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="heading">
	<h3 class="panel-title">查询</h3>
</div>

<div class="m_from">
<?php $form = ActiveForm::begin([
    //'type' => ActiveForm::TYPE_VERTICAL,
    'action' => ['order/order/index'],
    'method' => 'get',
]); ?>						
	<?php echo $form->field($searchModel, 'order_customer_phone')->TextInput(['class' => 'm_ipu'])->label('用户电话 :', ['class' => 'm_ipone']); ?>
	<?php echo $form->field($searchModel, 'order_worker_phone')->TextInput(['class' => 'm_ipu'])->label('阿姨电话 :', ['class' => 'm_ipone']); ?>
	<?php echo $form->field($searchModel, 'order_code')->TextInput(['class' => 'm_ipu'])->label('订单编号 :', ['class' => 'm_ipone']); ?>
	
	<div class="m_riqi">
	  <div class="m_fr">	
		<label class="m_ipone">下单时间:</label>
		<input type="text" name="datetime" class="ui_timepicker" value="" placeholder=""> 到
		<input type="text" name="datetime" class="ui_timepicker" value="" placeholder="">
     </div>
	  <div class="m_fr">	
        <label class="m_ipone">服务时间:</label>
		<input type="text" name="datetime" class="ui_timepicker" value="" placeholder=""> 到
		<input type="text" name="datetime" class="ui_timepicker" value="" placeholder="">
	   </div>

        <?= $form->field($searchModel, 'shop_id')->widget(Select2::classname(), [
            'initValueText' => '门店', // set the initial display text
            'options' => ['placeholder' => '选择门店', 'class' => 'm_ipu'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['show-shop']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ])->label('门店', ['class' => 'm_ipone']); ?>
        <?= $form->field($searchModel, 'district_id')->widget(Select2::classname(), [
            'name' => 'worker_district',
            'hideSearch' => true,
            'data' => Order::getDistrictList(),
            'options' => ['placeholder' => '选择商圈', 'class' => 'm_ipu'],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 10
            ],
        ])->label('商圈', ['class' => 'm_ipone']); ?>        	   
	   <?php echo $form->field($searchModel, 'order_address')->TextInput(['class' => 'm_ipu'])->label('客户地址 :', ['class' => 'm_ipone']); ?>
	   <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
	</div>
	
	
	
<?php ActiveForm::end(); ?>
  <div class="clear"></div>
</div>
