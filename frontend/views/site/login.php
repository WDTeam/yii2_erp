<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?php $form = ActiveForm::begin(); ?>
    <div class="body bg-gray">
        <?= $form->field($model, 'mobile')->textInput(['placeholder' => $model->getAttributeLabel('mobile')])->label(false) ?>
        <?= $form->field($model, 'mobile_code')->textInput(['placeholder' => $model->getAttributeLabel('mobile_code')])->label(false) ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <div>
            没有收到验证码 <?php echo Html::a('免费获取验证码', Yii::$app->urlManager->createUrl(["site/send-mobile-code"]),['id'=>'reSendMobileCode']);?>.
        </div>
    </div>
    <div class="footer bg-gray">
        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary bg-olive btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->registerJs(<<<reSendMobileCode
    $('#reSendMobileCode').click(function(){
        var ths = this;
    	$.ajax({
            url: this.href,
            method: 'POST',
            dataType: 'JSON',
            data:{
            	mobile: $('#loginform-mobile').val()
            },
            success:function(data){
            	alert('手机验证码已发送，请注意查收!');
                $(ths).text('重发');
            },
            error:function(err){
                console.log(err);
                alert('验证码发送失败');
            }
        });
        return false;
    });
reSendMobileCode
);?>
