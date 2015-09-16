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
        
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
         <div class="body bg-gray">
                  <?= $form->field($model, 'mobile')->textInput(['placeholder' => $model->getAttributeLabel('mobile')])->label(false) ?>

                   <?= $form->field($model, 'verification')->textInput(['placeholder' => $model->getAttributeLabel('verification')])->label(false) ?>
                 
                
                    <?= Html::Button('获取验证码', [
                    'class' => 'btn btn-primary bg-olive btn-block',
                    'id'=>'btn01' , 
                    'name' => 'signup-button01',
                    'href'=>Yii::$app->urlManager->createUrl(["site/send-mobile-code"])
                    ]) ?>
               
                    <?= Html::submitButton('报名', ['class' => 'btn btn-primary bg-olive btn-block', 'name' => 'signup-button']) ?>
               
            <?php ActiveForm::end(); ?>
        </div>
   
</div>

<?php $this->registerJs(<<<reSendMobileCode
    $('#btn01').click(function(){
      if($('#signupform-mobile').val()==""){
          alert("请输入手机号获取验证码");
          }else{
               $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            dataType: 'JSON',
            data:{
                mobile: $('#signupform-mobile').val()
            },
            success:function(){
                alert('手机验证码已发送，请注意查收!');
            },
            error:function(err){
                console.log(err);
                alert('发送失败');
            }
        });   
   
         }
        return false;
    });
reSendMobileCode
);?>