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
<style type="text/css">
	.select2-container--krajee {
display: block;
float: right;
}

</style>
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
		 <input name="created_from" type="text" class="Wdate" id="d412" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})" value="<?= isset($searchParas['created_from']) ? Html::encode($searchParas['created_from']) : '' ?>"/> 到
		 <input name="created_to" type="text" class="Wdate" id="d412" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})" value="<?= isset($searchParas['created_to']) ? Html::encode($searchParas['created_to']) : '' ?>"/>
     </div>
	  <div class="m_fr" style="margin-bottom: 20px;">	
        <label class="m_ipone">服务时间:</label>
		  <input name="booked_from" type="text" class="Wdate" id="d412" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})" value="<?= isset($searchParas['booked_from']) ? Html::encode($searchParas['booked_from']) : '' ?>"/> 到
		  <input name="booked_to" type="text" class="Wdate" id="d412" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})" value="<?= isset($searchParas['booked_to']) ? Html::encode($searchParas['booked_to']) : '' ?>"/>
	   </div>

        <?= $form->field($searchModel, 'shop_id')->widget(Select2::classname(), [
            'initValueText' => '门店:', // set the initial display text
            'options' => ['placeholder' => '选择门店', 'class' => 'm_ipu'],
            'pluginOptions' => [
                'width' => '80%',
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
        ])->label('门店:', ['class' => 'm_ipone','style'=>'line-height:35px']); ?>
        
        <?= $form->field($searchModel, 'district_id')->widget(Select2::classname(), [
            'name' => 'worker_district',
            'hideSearch' => true,
            'data' => Order::getDistrictList(),
            'options' => ['placeholder' => '选择商圈', 'class' => 'm_ipu'],
            'pluginOptions' => [
                'width' => '80%',
                'tags' => true,
                'maximumInputLength' => 10
            ],
        ])->label('商圈:', ['class' => 'm_ipone','style'=>'line-height:35px']); ?>        	   
	   <?php echo $form->field($searchModel, 'order_address')->TextInput(['class' => 'm_ipu'])->label('客户地址 :', ['class' => 'm_ipone','style'=>'margin-left:20px;']); ?>
	   <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
	</div>
	
	
	
<?php ActiveForm::end(); ?>
  <div class="clear"></div>
</div>
