<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Customer $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'customer_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户名称...', 'maxlength'=>16]], 

'customer_sex'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户性别...']], 

'customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户手机号...', 'maxlength'=>11]], 

'customer_score'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户积分...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'customer_birth'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户生日...']], 

'region_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 居住区域关联...']], 

'customer_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户会员级别...']], 

'customer_src'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户来源，1为线下，2为线上...']], 

'channal_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 关联渠道...']], 

'platform_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 关联平台...']], 

'customer_login_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 登陆 时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否逻辑删除...']], 

'customer_login_ip'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 登陆ip...', 'maxlength'=>16]], 

'customer_photo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户头像...', 'maxlength'=>32]], 

'customer_email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户邮箱...', 'maxlength'=>32]], 

'customer_live_address_detail'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Customer Live Address Detail...', 'maxlength'=>64]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
