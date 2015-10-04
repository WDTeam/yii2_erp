<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-advert-release-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php //echo $form->field($model, 'operation_platform_id')->dropDownList($platforms)->label('所属平台') ?>
    
    <?php //echo !$model->isNewRecord ? $form->field($model, 'operation_platform_version_id')->dropDownList($versions)->label('所属版本') : $form->field($model, 'operation_platform_version_id')->dropDownList(['选择版本'])->label('所属版本') ?>

    <?= $form->field($model, 'operation_advert_position_id')->dropDownList($positions);?>
    
    <?php
        echo '<div class="form-group field-operationadvertrelease-operation_advert_contents">';
        echo '    <label class="control-label" for="operationadvertrelease-operation_advert_position_id">选择广告内容</label>';
        echo '    <div class="list-group">';
        $cur = unserialize($model->operation_advert_contents);
        foreach($contents as $k => $v){
            $checked = '';
            if(!empty($cur) && in_array($k, $cur)){
                $checked = 'checked';
            }
            echo '<div class="list-group-item">'
            .'<div class="col-md-1">'
            .Html::checkbox('OperationAdvertRelease[operation_advert_contents][]', $checked, ['value' => $k])
            ."</div>"
            .'<div class="col-md-10">'
            .$v
            ."</div>"
            .'<div class="btn-group col-md-1">'
            .Html::button('<span class="glyphicon glyphicon-arrow-up"></span>', ['class' => 'advert-goup btn btn-primary badge col-md-6', 'title' => '向上'])
            .Html::button('<span class="glyphicon glyphicon-arrow-down"></span>', ['class' => 'advert-godown btn btn-success badge col-md-6', 'title' => '向下'])
            .'</div>'
            .'<div class="clearfix"></div>'        
            .'</div>';
        }
        echo '    </div>';
        echo '</div>';
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
