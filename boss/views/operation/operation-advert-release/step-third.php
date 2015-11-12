<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', '第三步：选择目标版本');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//if(!empty($versions)){
//    echo '<div class="step3_versions_'.$platform['id'].'">';
//    echo '<label class="control-label" for="operationadvertrelease-city_id">'.$platform['operation_platform_name'].'：</label>';
//    echo Html::checkboxList('OperationAdvertRelease[version_id][]', null, $versions, ['platform_id' => $platform['id'], 'class' => 'platform_versions']);
//    echo '<div>';
//}
?>
<?php $form = ActiveForm::begin(); ?>
<label class="control-label" for="operationadvertrelease-city_id">
    <?php echo $this->title?>
</label>
<?php foreach($platforms as $k => $platform){
    if(!empty($platform['versions'])){ ?>
        <div class="form-group">
            <label class="control-label" for="operationadvertrelease-city_id"><?=$platform['operation_platform_name']?>：</label>
            <?php foreach($platform['versions'] as $key => $version){?>
                <label><?=Html::radio('version_id['.$platform['id'].'][]', false, ['value' => $version['id']]);?><?php echo $version['operation_platform_version_name']?></label>
            <?php }?>
            <?php //=Html::checkboxList('OperationAdvertRelease[version_id][]', null, $versions, ['platform_id' => $platform['id'], 'class' => 'platform_versions']);?>
        </div>
            <?php }?>
<?php }?>

<div class="form-group">
    <?=Html::submitButton('下一步', ['class' => 'btn btn-success form-control']) ?>
</div>
<?php ActiveForm::end(); ?>
