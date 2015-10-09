<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'age'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 年龄...']], 

'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'study_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 学习状态:1认同，2不认同...']], 

'study_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 学习所用时长（单位秒）...']], 

'notice_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 通知状态：1已通知，2未通知...']], 

'online_exam_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 在线考试时间（开始还是结束呢？）...']], 

'online_exam_score'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 在线考试成绩...']], 

'exam_result'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 现场考试结果：1通过，2未通过...']], 

'operation_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实操考试时间...']], 

'operation_score'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实操考试成绩...']], 

'test_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 试工状态：1.安排试工，2：不用试工...']], 

'test_result'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 试工结果...']], 

'sign_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 签约状态...']], 

'birthday'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 

'username'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 姓名...', 'maxlength'=>255]], 

'password_hash'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 加密的密码...', 'maxlength'=>255]], 

'password_reset_token'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 密码重置...', 'maxlength'=>255]], 

'email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 邮箱...', 'maxlength'=>255]], 

'whatodo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 提供的服务列表...', 'maxlength'=>255]], 

'test_situation'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 试工情况...', 'maxlength'=>11]], 

'auth_key'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 手机验证码x...', 'maxlength'=>32]], 

'idnumber'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 身份证号...', 'maxlength'=>24]], 

'mobile'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 手机...', 'maxlength'=>15]], 

'ecn'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 紧急联系号码...', 'maxlength'=>15]], 

'city'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 市...', 'maxlength'=>3]], 

'province'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 省...', 'maxlength'=>3]], 

'district'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 区...', 'maxlength'=>2]], 

'where'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 来自哪里...']], 

'when'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 选择服务时间...']], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 1...', 'maxlength'=>1]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
