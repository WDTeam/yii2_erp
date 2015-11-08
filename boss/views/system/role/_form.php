<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;
use boss\components\RbacHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        /*'enableAjaxValidation' => true,
        'validateOnSubmit'=>true,
        'validateOnChange' => false*/
    ]);
    ?>
    <div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
                <?= Yii::t('app', 'Role'); ?>
            </div>
			<div class="panel-body">
                <?php
                echo $form->field($model, 'name')->textInput($model->isNewRecord ? [] : ['disabled' => 'disabled']) .
                     $form->field($model, 'description')->textarea(['style' => 'height: 100px']) .
                     Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), [
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                     ]);
                ?>
            </div>

		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
                <?= Yii::t('app', 'Permissions'); ?>
            </div>

			<div class="panel-body">
			<?php 
			$menus = RbacHelper::getMenus();
			echo $this->render('form_permissions', [
			    'menus'=>$menus,
			    'model'=>$model,
			]);
			?>
			
			
            <?php 
                echo $form->field($model, 'permissions')->checkboxList(RbacHelper::$permissions, [
                    'id'=>'item_orther',
                ])->label('其它');
            ?>
            </div>
		</div>
	</div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->registerJs(<<<JSCONTENT
    $('.panel-body input[name="Auth[permissions]"]').remove();
JSCONTENT
);?>
