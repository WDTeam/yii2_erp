<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\SignupForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '在线报名';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>请填写报名信息:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

              
             <!--<?= $form->field($model,'whatodo')->checkboxList(['家庭保洁','企业保洁'])?>
                            
             <?= $form->field($model,'from_type')->radioList(['E家洁','小家政公司','（中介劳务公司）','推广平台1','推广平台2']) ?>
                             
             <?= $form->field($model,'when')->radioList(['随时可以','周六周日','不确定' ]) ?>-->

                 <?= $form->field($model, 'username')->label('姓名') ?>
                 <?= $form->field($model, 'idnumber')->label('身份证号') ?>
               <!-- <?= $form->field($model, 'mobile')->label('手机号码') ?>-->
               
               <!-- <?= $form->field($model, 'verification')->textInput(['maxlength' => true])->label('验证码') ?>
                <div class="form-group">
                    <?= Html::Button('获取验证码', [
                    'class' => 'btn btn-primary',
                    'id'=>'btn01' , 
                    'name' => 'signup-button01',
                    'href'=>Yii::$app->urlManager->createUrl(["site/send-mobile-code"])
                    ]) ?>
                </div>-->
                  <?= $form->field($model, 'ecn')->label('紧急联系人手机号') ?>
                <div class="form-group">
                    <?= Html::submitButton('报名', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php $this->registerJs(<<<reSendMobileCode
    $('#btn01').click(function(){
        $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            dataType: 'JSON',
            data:{
                mobile: $('#signupform-mobile').val()
            },
            success:function(data){
                alert('手机验证码已发送，请注意查收'+data);
            },
            error:function(err){
                console.log(err);
                alert('发送失败');
            }
        });
        return false;
    });
reSendMobileCode
);?>