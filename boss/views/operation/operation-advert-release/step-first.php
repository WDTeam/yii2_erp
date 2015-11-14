<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */

$this->title = Yii::t('app', '第一步：选择城市');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-create">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group" id="adv_city_selected">
        <label class="control-label">城市名称</label>
        <?=Html::checkboxList('city_ids[]', null, $citys, ['class' => 'form-control', 'style' => 'height:auto; min-height:50px; _height:50px;']);?>
        <div class="help-block"></div>
        <input type="button" value="反选" class="btn btn-sm btn-warning" id="adv_city_reverse" /> 
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
