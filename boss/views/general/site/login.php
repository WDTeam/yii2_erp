<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use boss\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \dbbase\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
//ֻ�ڸ���ͼ��ʹ�÷�ȫ�ֵ�jui
AppAsset::addScript($this, 'adminlte/js/plugins/login/plugin.js');
AppAsset::addCss($this, 'adminlte/css/login/lrtk.css');

?>

<?php $form = ActiveForm::begin(); ?>

<div class="container-fluid text-center">
    <div class="warp">
        <div class="header">
            <div class="loginlogo" style="text-align:left;"><img src="../../adminlte/img/login/logo.png"> <span><a
                        href="###"><?php echo Html::encode(Yii::t('app', 'help')); ?></a></span></div>
        </div>
        <div class="login-content">
            <div class="middle">
                <div class="bgimg"><img src="../../adminlte/img/login/banner_730_380.png"></div>

                <!--<div class="toumin"></div>-->
                <div class="login">
                    <ul id="list">

                        <li><h3><?php echo Html::encode($this->title); ?></h3></li>

                        <li><?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username'),
                                'class' => 'account'])->label(false) ?>
                        </li>

                        <li>
                            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'),
                                'class' => 'password'])->label(false) ?>
                        </li>

                        <li>
                            <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btnlogin']) ?>
                        </li>
                        <li>
                            <p class="ickb"><i class="ckb"
                                               name="$model[rememberMe]"></i> <?php echo Html::encode($model->getAttributeLabel('rememberMe')); ?>
                            </p>
                            <span><a href="###"><?php echo Html::encode(Yii::t('app', 'forget_pwd')); ?></a></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer">
            Copyright © 2015 e家洁.
            All rights reserved. v1.0.0
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>

