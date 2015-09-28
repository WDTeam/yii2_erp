<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    html,body{
        font-family: "Microsoft YaHei";
        overflow: hidden;
    }
</style>

    <div class="container-fluid text-center">
        <div class="warp">
            <div class="loginlogo" style="text-align:left;"><img src="../../adminlte/img/login/logo.png"> <span><a href="###"><?php echo Html::encode(Yii::t('app', 'help')); ?></a></span></div>
            <h1><span><?php echo Html::encode(Yii::t('app', 'begin_New_Work')); ?></span></h1>
            <div class="toumin"></div>
            <div class="login">
                <ul id="list">

                    <li><h3><?php echo Html::encode($this->title); ?></h3></li>
                    <?php $form = ActiveForm::begin(); ?>
                    <li><?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username'),
                            'class'=>'account'])->label(false) ?>
                    </li>

                    <li>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'),
                            'class'=>'password'])->label(false) ?>
                    </li>

                    <li>
                        <?= Html::submitButton(Yii::t('app', 'Login'), ['class'=>'btnlogin']) ?>
                    </li>
                    <li>
                        <p><i class="ckb" name="$model[rememberMe]"><?php echo Html::encode($model->getAttributeLabel('rememberMe')); ?></i> </p>
                        <span><a href="###"><?php echo Html::encode(Yii::t('app', 'forget_pwd')); ?></a></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="header">
            <img class="headerImg" src="../../adminlte/img/login/back_img4.jpg"
                 data-slideshow='../../adminlte/img/login/back_img1.jpg|../../adminlte/img/login/back_img2.jpg|../../adminlte/img/login/back_img3.jpg|../../adminlte/img/login/back_img.jpg'>
        </div>
    </div>
<?php ActiveForm::end(); ?>