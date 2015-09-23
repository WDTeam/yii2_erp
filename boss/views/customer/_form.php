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

'customer_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户名...', 'maxlength'=>16]], 

'customer_sex'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 性别...']], 

'customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 电话...', 'maxlength'=>11]], 

'customer_score'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 积分...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'customer_birth'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 生日...']], 

'region_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 住址...']], 

'customer_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评级...']], 

'customer_complaint_times'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 投诉...']], 

'customer_src'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 来源，1为线下，2为线上...']], 

'channal_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 渠道...']], 

'platform_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 平台...']], 

'customer_login_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 登陆时间...']], 

'customer_is_vip'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 身份...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 加入黑名单...']], 

'customer_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 账户余额...', 'maxlength'=>8]], 

'customer_del_reason'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 加入黑名单原因...','rows'=> 6]], 

'customer_login_ip'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 登陆ip...', 'maxlength'=>16]], 

'customer_photo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 头像...', 'maxlength'=>32]], 

'customer_email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 邮箱...', 'maxlength'=>32]], 

'customer_live_address_detail'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 详细住址...', 'maxlength'=>64]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
