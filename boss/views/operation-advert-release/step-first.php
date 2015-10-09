<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */

$this->title = Yii::t('app', 'Release Advert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-create">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label class="control-label">平台名称</label>
        <?=Html::dropDownList('city_id', null, $citys, ['class' => 'form-control']);?>
        <div class="help-block"></div>
    </div>
    
    <div class="form-group">
        <?=Html::submitButton('下一步', ['class' => 'btn btn-success form-control']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    
    
    <?php
//    = $this->render('_form', [
//        'model' => $model,
//        'citys' => $citys
//    ]) 
    ?>

</div>
