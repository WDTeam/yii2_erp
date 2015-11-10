<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use boss\models\operation\coupon\CouponRule as CouponRuleSearch;
use boss\components\AreaCascade;



$configdate=CouponRuleSearch::couponconfig();
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

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);  ?>
    
    		<div class="panel-body">
    		<?
    		echo Form::widget([
    		 		'model' => $model,
    		 		'form' => $form,
    		 		'columns' => 1,
    		 		'attributes' => [
    				'couponrule_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>' 优惠券名称...', 'maxlength'=>100]],
'couponrule_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券单价...', 'maxlength'=>8]],
'couponrule_price_sum'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠券总价...', 'maxlength'=>8]],			
    		 'couponrule_classify'=>[
    		 'type'=> Form::INPUT_RADIO_LIST,
    		 'items'=>$configdate[1],'options'=>[]],
			 'couponrule_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'如果是1码多用的优惠码...', 'maxlength'=>40]],
    		'couponrule_channelname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'渠道名称(主要使用到一码多用分渠道发)...', 'maxlength'=>80]],
             'couponrule_code_max_customer_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'一码多用单个优惠码最大使用人数限制...']],
    				
				'couponrule_Prefix'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠码前缀...', 'maxlength'=>20]],	
    			'couponrule_code_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'优惠码个数...']],
    		'couponrule_category'=>[
    		'type'=> Form::INPUT_RADIO_LIST,
    		'items'=>$configdate[2],'options'=>[]],
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
    		'items'=>$configdate[3],'options'=>[]],

    		'couponrule_service_type_id'=>[
     		'type' => Form::INPUT_DROPDOWN_LIST,
     		'items' => $configdate[7],
     		'options' => [
     		'prompt' => '服务类型',
     		],
     		],
    		
    		
    		'couponrule_commodity_id'=>[
    		'type' => Form::INPUT_DROPDOWN_LIST,
    		'items' => $configdate[8],
    		'options' => [
    		'prompt' => '商品类型',
    		],
    		],
    	
    		'couponrule_city_limit'=>[
    		'type'=> Form::INPUT_RADIO_LIST,
    		'items'=>$configdate[4],'options'=>[]],
	    	]
			]);
			?>	
    <?php
     echo AreaCascade::widget([
		'model' => $model,
		'options' => ['class' => 'form-control'],
		'label' =>'选择城市',
		'grades' => 'city',
		]);
        ?>

			<? echo Form::widget([
    		 		'model' => $model,
    		 		'form' => $form,
    		 		'columns' => 1,
    		 		'attributes' => [
		    		'couponrule_customer_type'=>[
		    		'type'=> Form::INPUT_CHECKBOX_LIST,
		    		'items'=>$configdate[5],'options'=>[]],
					'couponrule_promote_type'=>[
					'type'=> Form::INPUT_RADIO_LIST,
					'items'=>$configdate[6],'options'=>[]],
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
		            ]);  
		    		?>
    	
			    	<?= $form->field($model, 'couponrule_get_end_time')->widget(DatePicker::classname(), 		[
			    		'name' => 'couponrule_get_start_time',
			    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
			    		'pluginOptions' => [
			    		'autoclose' => true,
			    		'format' => 'yyyy-mm-dd'
			    		]
			            ]);  
			    	?>
			    	<?= $form->field($model, 'couponrule_use_start_time')->widget(DatePicker::classname(), 		[
			    		'name' => 'couponrule_get_start_time',
			    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
			    		'pluginOptions' => [
			    		'autoclose' => true,
			    		'format' => 'yyyy-mm-dd'
			    		]
			            ]);  
			    	?>
			    	<?= $form->field($model, 'couponrule_use_end_time')->widget(DatePicker::classname(), 		[
			    		'name' => 'couponrule_get_start_time',
			    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
			    		'pluginOptions' => [
			    		'autoclose' => true,
			    		'format' => 'yyyy-mm-dd'
			    		]
			            ]);  
			    	?>
    		     		 
    			    <?   echo Form::widget([
    		 		'model' => $model,
    		 		'form' => $form,
    		 		'columns' => 1,
    		 		'attributes' => [
'couponrule_use_end_days'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'领取后过期天数...']], 
'couponrule_order_min_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'满减或每减时订单最小金额...', 'maxlength'=>8]], 
    ]
    ]);		 
    		 
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
<?php
$this->registerJsFile('/js/coupon.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
?>
