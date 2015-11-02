<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use core\models\system\SystemUser;

/**
 * @var yii\web\View $this
 * @var dbbase\models\shop\ShopCustomeRelation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-custome-relation-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
    		'system_user_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>SystemUser::getuserlist(),'options' => [
    		'prompt' => '请选择用户',
    		],'class' => 'col-md-2'],
    		
    		'shopid'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>SystemUser::getuserlist(),'options' => [
    		'prompt' => '请选择门店',
    		],'class' => 'col-md-2'],
    		
    		'shop_manager_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>SystemUser::getuserlist(),'options' => [
    		'prompt' => '请选择家政公司',
    		],'class' => 'col-md-2'],

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
