<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="worker-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'shop_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 门店id...']], 

'worker_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨等级...']], 

'worker_auth_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨审核状态 0未通过1通过...']], 

'worker_ontrial_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨试工状态 0未试工，1已试工...']], 

'worker_onboard_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨上岗状态 0未上岗 1已上岗 ...']], 

'worker_work_city'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨工作城市...']], 

'worker_work_area'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨工作区县...']], 

'worker_rule'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨角色 1自有 2非自有...']], 

'worker_identify_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨身份id ...']], 

'worker_is_block'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨是否封号 0正常1封号...']], 

'created_ad'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨录入时间...']], 

'updated_ad'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 最后更新时间...']], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否删号 0正常1删号...']], 

'worker_work_lng'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨常用工作经度...']], 

'worker_work_lat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨常用工作纬度...']], 

'worker_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨姓名...', 'maxlength'=>10]], 

'worker_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨手机...', 'maxlength'=>20]], 

'worker_idcard'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨身份证号...', 'maxlength'=>20]], 

'worker_password'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨端登录密码...', 'maxlength'=>50]], 

'worker_work_street'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨常用工作地址...', 'maxlength'=>50]], 

'worker_photo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨头像地址...', 'maxlength'=>40]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
