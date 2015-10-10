<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use kartik\grid\GridView;
use common\models\Customer;
use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use kartik\date\DatePicker;
/**
 * @var yii\web\View $this
 * @var common\models\Customer $model
 * @var yii\widgets\ActiveForm $form
 */
$url = \yii\helpers\Url::to(['customer']);
//$cityDesc = empty($worker->shop_id) ? '' : Worker::findOne($worker->shop_id)->description;
$cityDesc = '客户';
//$worker->hasOne(WorkerExt::className(),'id=worker_id');
//var_dump($worker->workerExts);die;
?>


<div class="customer-form">
<?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'id' => 'msg-form',
        // 'options' => ['class'=>'form-horizontal'],
        // 'enableAjaxValidation'=>false,
        'fieldConfig' => [
            // 'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ]
    ]);

    echo $form->field($model, 'customer_name')->textInput(['placeholder' => 'Enter 客户姓名...', 'maxlength' => 16]);
    // echo $form->field($model, 'customer_sex')->radioList(['0' => '女', '1' => '男'], ['inline' => true]);
    echo $form->field($model, 'customer_phone')->textInput(['placeholder' => 'Enter 客户手机...', 'maxlength' => 11]);
    // echo $form->field($customerScore, 'customer_score')->textInput(['placeholder' => 'Enter 客户积分...', 'maxlength' => 8]);
    // echo $form->field($model, 'created_at')->widget(DatePicker::classname(),[
    //     'name' => 'created_at',
    //     'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //     'value' => date('yyyy-mm-dd', $model->created_at),
    //     'pluginOptions' => [
    //         'autoclose'=>true,
    //         'format' => 'yyyy-mm-dd'
    //     ]
    // ]);
    // echo $form->field($model, 'updated_at')->widget(DatePicker::classname(),[
    //     'name' => 'updated_at',
    //     'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //     'value' => date('yyyy-mm-dd', $model->updated_at),
    //     'pluginOptions' => [
    //         'autoclose'=>true,
    //         'format' => 'yyyy-mm-dd'
    //     ]
    // ]);
    // echo $form->field($model, 'customer_birth')->widget(DatePicker::classname(),[
    //     'name' => 'customer_birth',
    //     'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //     'value' => date('yyyy-mm-dd', $model->customer_birth),
    //     'pluginOptions' => [
    //         'autoclose'=>true,
    //         'format' => 'yyyy-mm-dd'
    //     ]
    // ]);

    //echo $form->field($model, 'general_region_id')->textInput(['placeholder' => 'Enter 客户住址ID...', 'maxlength' => 8]);
    // echo $form->field($model, 'customer_level')->textInput(['placeholder' => 'Enter 客户评级...', 'maxlength' => 8]);
    // echo $form->field($model, 'customer_complaint_times')->textInput(['placeholder' => 'Enter 客户投诉次数...', 'maxlength' => 8]);

    echo $form->field($model, 'customer_src')->radioList(['0' => '线下', '1' => '线上'], ['inline' => true]);
    
    $customerPlatform= CustomerPlatform::findOne($model->platform_id);
    $platforms = CustomerPlatform::find()->all();
    $platformArr = array();
    foreach ($platforms as $platform) {
        $platformArr[$platform['id']] = $platform['platform_name'];
    }
    echo $form->field($model, 'platform_id')->widget(Select2::classname(), [
            'name' => 'platform_id',
            'value'=> $customerPlatform != NULL ? $customerPlatform->platform_name : '未知',
            'hideSearch' => true,
            'data' => $platformArr,
            'options' => ['placeholder' => '选择客户来源平台', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    $customerChannal= CustomerChannal::findOne($model->channal_id);
    $channals = CustomerChannal::find()->all();
    $channalArr = array();
    foreach ($channals as $channal) {
        $channalArr[$channal['id']] = $channal['channal_name'];
    }
    echo $form->field($model, 'channal_id')->widget(Select2::classname(), [
            'name' => 'channal_id',
            'value'=> $customerChannal != NULL ? $customerChannal->channal_name : '未知',
            'hideSearch' => true,
            'data' => $channalArr,
            'options' => ['placeholder' => '选择客户来源聚道', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    // echo $form->field($model, 'customer_login_ip')->textInput(['placeholder' => 'Enter 客户登陆IP...', 'maxlength' => 32]);
    // echo $form->field($model, 'customer_login_time')->widget(DatePicker::classname(),[
    //     'name' => 'customer_login_time',
    //     'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //     'value' => date('yyyy-mm-dd', $model->customer_login_time),
    //     'pluginOptions' => [
    //         'autoclose'=>true,
    //         'format' => 'yyyy-mm-dd'
    //     ]
    // ]);

    echo $form->field($model, 'customer_is_vip')->radioList(['0' => '非会员', '1' => '会员'], ['inline' => true]);
    echo $form->field($model, 'is_del')->radioList(['0' => '未加入黑名单', '1' => '已加入黑名单'], ['inline' => true]);

    // echo $form->field($customerBalance, 'customer_balance')->textInput(['placeholder' => 'Enter 客户余额', 'maxlength' => 10]);
    // echo $form->field($model, 'customer_del_reason')->textInput(['placeholder' => 'Enter 客户加入黑名单原因', 'maxlength' => 255]);
    
    // echo $form->field($model, 'customer_photo')->textInput(['placeholder' => 'Enter 客户头像...']);
    // echo $form->field($model, 'customer_email')->textInput(['placeholder' => 'Enter 客户邮箱...']);
    echo $form->field($model, 'customer_live_address_detail')->textInput(['placeholder' => 'Enter 客户住址详情...']);
    ?>
</div>
<?php 
echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); 
?>
</div>
