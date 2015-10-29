<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use kartik\grid\GridView;
use dbbase\models\Customer;
use dbbase\models\CustomerPlatform;
use dbbase\models\CustomerChannal;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Customer $model
 */

// $this->title = Yii::t('boss', '更新{modelClass}', [
//     'modelClass' => '客户',
// ]) . ' ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Customers'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="customer-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="customer-form">
		<?php 
		$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'id' => 'msg-form',
	        // 'options' => ['class'=>'form-horizontal'],
	        // 'enableAjaxValidation'=>false,
	        'fieldConfig' => [
	            // 'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
	            // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
	        ]
	    ]);
	    foreach ($customerAddressModel as $customerAddress) {
	    	
	    }
	    // echo $form->field($model, 'platform_id')->widget(Select2::classname(), [
	    //         'name' => 'platform_id',
	    //         'value'=> $customerPlatform != NULL ? $customerPlatform->platform_name : '未知',
	    //         'hideSearch' => true,
	    //         'data' => $platformArr,
	    //         'options' => ['placeholder' => '选择客户来源平台', 'inline' => true],
	    //         'pluginOptions' => [
	    //             'allowClear' => true
	    //         ],
	    //     ]);

	    // $customerChannal= CustomerChannal::findOne($model->channal_id);
	    // $channals = CustomerChannal::find()->all();
	    // $channalArr = array();
	    // foreach ($channals as $channal) {
	    //     $channalArr[$channal['id']] = $channal['channal_name'];
	    // }
	    // echo $form->field($model, 'channal_id')->widget(Select2::classname(), [
	    //         'name' => 'channal_id',
	    //         'value'=> $customerChannal != NULL ? $customerChannal->channal_name : '未知',
	    //         'hideSearch' => true,
	    //         'data' => $channalArr,
	    //         'options' => ['placeholder' => '选择客户来源聚道', 'inline' => true],
	    //         'pluginOptions' => [
	    //             'allowClear' => true
	    //         ],
	    //     ]);

	    // echo $form->field($model, 'customer_is_vip')->radioList(['0' => '非会员', '1' => '会员'], ['inline' => true]);
	    // echo $form->field($model, 'is_del')->radioList(['0' => '未加入黑名单', '1' => '已加入黑名单'], ['inline' => true]);
	    // echo $form->field($model, 'customer_live_address_detail')->textInput(['placeholder' => 'Enter 客户住址详情...']);
	    ?>
	</div>
	<?php 
	// echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
	//     ActiveForm::end(); 
	?>

</div>
