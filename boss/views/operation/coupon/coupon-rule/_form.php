<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use kartik\date\DatePicker;

$model->couponrule_classify=1;
$model->couponrule_category=1;
$model->couponrule_type=1;

$model->is_disabled=1;
$model->couponrule_city_limit=1;
$model->couponrule_customer_type=1;
$model->couponrule_promote_type=1;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponRule $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-rule-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
    
    
    		?>
    		
    		<div class="panel-body">
    		<?
    		echo Form::widget([
    		 		'model' => $model,
    		 		'form' => $form,
    		 		'columns' => 1,
    		 		'attributes' => [
    		 'couponrule_classify'=>[
    		 'type'=> Form::INPUT_RADIO_LIST,
    		 'items'=>['1' => '一码一用', '2' => '一码多用'],'options'=>[]],
    		'couponrule_channelname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'渠道名称(主要使用到一码多用分渠道发)...', 'maxlength'=>80]],

    		'couponrule_category'=>[
    		'type'=> Form::INPUT_RADIO_LIST,
    		'items'=>['1' => '一般优惠券', '2' => '赔付优惠券'],'options'=>[]],
    		

    				]
    				]);
    				?>
    		</div>	
    		
    		
    		<?
    		echo Form::widget([
    		 		'model' => $model,
    		 		'form' => $form,
    		 		'columns' => 1,
    		 		'attributes' => [		
    		'couponrule_type'=>[
    		'type'=> Form::INPUT_RADIO_LIST,
    		'items'=>['1' => '全网优惠券', '2' => '类别优惠券','3' => '商品优惠券'],'options'=>[]],

    		'couponrule_service_type_id'=>[
     		'type' => Form::INPUT_DROPDOWN_LIST,
     		'items' => ['1'=>'服务类别名称1','2'=>'服务类别名称2'],
     		'options' => [
     		'prompt' => '服务类别名称',
     		],
     		],
    		
    		
    		'couponrule_commodity_id'=>[
    		'type' => Form::INPUT_DROPDOWN_LIST,
    		'items' => ['1'=>'商品优惠券名称1','2'=>'商品优惠券名称2'],
    		'options' => [
    		'prompt' => '商品优惠券名称',
    		],
    		],
    		

    		'couponrule_city_limit'=>[
    		'type'=> Form::INPUT_RADIO_LIST,
    		'items'=>['1' => '不限', '2' => '单一城市'],'options'=>[]],
    		
    		
    		'couponrule_city_id'=>[
    		'type' => Form::INPUT_DROPDOWN_LIST,
    		'items' => ['1'=>'北京','2'=>'天津'],
    		'options' => [
    		'prompt' => '请选择地区',
    		],
    		],
    		'couponrule_customer_type'=>[
    		'type'=> Form::INPUT_CHECKBOX_LIST,
    		'items'=>['1' => '所有用户', '2' => '新用户', '3' => '老用户', '4' => '会员'],'options'=>[]],

			'couponrule_promote_type'=>[
			'type'=> Form::INPUT_CHECKBOX_LIST,
			'items'=>['1' => '立减', '2' => '满减'],'options'=>[]],


    		]
    		]);
    ?>
    	 
    	<?= $form->field($model, 'couponrule_get_start_time')->widget(DatePicker::classname(), 		[
    		'name' => 'couponrule_get_start_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
    	
    	
    	<?= $form->field($model, 'couponrule_get_end_time')->widget(DatePicker::classname(), 		[
    		'name' => 'couponrule_get_start_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
    			  
    	
    	<?= $form->field($model, 'couponrule_use_start_time')->widget(DatePicker::classname(), 		[
    		'name' => 'couponrule_get_start_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
    		     
    		 
    	<?= $form->field($model, 'couponrule_use_end_time')->widget(DatePicker::classname(), 		[
    		'name' => 'couponrule_get_start_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
    		     		 
    		 
    <?		 
    		 echo Form::widget([
    		 		'model' => $model,
    		 		'form' => $form,
    		 		'columns' => 1,
    		 		'attributes' => [
'couponrule_use_end_days'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'领取后过期天数...']], 

'couponrule_code_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠码个数...']], 

'couponrule_code_max_customer_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'如果是一码多用单个优惠码最大使用人数限制...']], 

'couponrule_order_min_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'满减或每减时订单最小金额...', 'maxlength'=>8]], 

'couponrule_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券单价...', 'maxlength'=>8]], 

'couponrule_price_sum'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券总价...', 'maxlength'=>8]], 

'couponrule_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>' 优惠券名称...', 'maxlength'=>100]], 

'couponrule_category_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券范畴...', 'maxlength'=>100]], 

'couponrule_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券类型名称...', 'maxlength'=>100]], 

'couponrule_service_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'服务类别名称...', 'maxlength'=>100]], 

'couponrule_commodity_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'如果是商品名称...', 'maxlength'=>100]], 

'couponrule_customer_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'适用客户类别名称...', 'maxlength'=>100]], 

'couponrule_city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'城市名称...', 'maxlength'=>60]], 

'couponrule_promote_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券优惠类型名称...', 'maxlength'=>60]], 

'couponrule_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'如果是1码多用的优惠码...', 'maxlength'=>40]], 

'couponrule_Prefix'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠码前缀...', 'maxlength'=>20]], 

    ]
    ]);
    		 
    		 
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
<?php
$this->registerJsFile('/js/coupon.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
?>
