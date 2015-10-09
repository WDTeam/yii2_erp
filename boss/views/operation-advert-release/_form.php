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
    <div class="btn-group">
        <?php
            echo Html::button('<span class="glyphicon glyphicon-plus"></span>增加广告内容', ['class' => 'btn btn-primary', 'id' => 'insertAdvertContent', 'title' => '增加内容']);
            echo Html::button('<span class="glyphicon glyphicon-remove"></span>清空广告内容', ['class' => 'btn btn-danger', 'id' => 'emptyAdvertContent', 'title' => '清空内容']);
        ?>
    </div>
    <div class="form-group field-operationadvertrelease-operation_advert_contents">
    <label class="control-label" for="operationadvertrelease-operation_advert_position_id">选择广告内容</label>
    <div class="list-group" id="advertListContent">
    <?php
    $cur = unserialize($model->operation_advert_contents);
    foreach($contents as $k => $v){
    ?>
        <div class="list-group-item" content_id="<?=$k ?>">
            <div class="col-md-11">
                <input type="hidden" value="<?php echo $k; ?>" name="OperationAdvertRelease[operation_advert_contents][]" />
                <?=$v?>
            </div>
            <div class="btn-group col-md-1">
            <?php
                echo Html::button('<span class="glyphicon glyphicon-arrow-up"></span>', ['class' => 'advert-goup btn btn-primary badge col-md-6', 'title' => '向上']);
                echo Html::button('<span class="glyphicon glyphicon-arrow-down"></span>', ['class' => 'advert-godown btn btn-success badge col-md-6', 'title' => '向下']);
            ?>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php }?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
