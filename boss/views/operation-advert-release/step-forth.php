<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', '第四步：选择广告完成发布');
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
<label class="control-label" for="operationadvertrelease-city_id"><?php echo $this->title?></label>
<?php //foreach($data as $k => $value){?>
<?php //if(!empty($value['adverts'])){?>
<!--<div class="panel panel-body panel-default">
    <h1 class="panel-title">
        <?php //echo $value['platform']['operation_platform_name']?>
        <?php //echo isset($value['version']) ? $value['version']['operation_platform_version_name'] : '';?>：
    </h1>
    <div class="panel-body">
        <ul class="list-group">
            <?php //foreach($value['adverts'] as $key => $v){?>
            <li class="list-group-item list-group-item-info">
                <label>
                <?php //echo Html::checkbox('advert[]', false, ['value' => $v['id']]);?>
                <?php //echo $v['position_name'].':'.$v['operation_advert_content_name']?>
                </label>
            </li>
            <?php //}?>
        </ul>
    </div>
    <h1 class="panel-footer"></h1>
</div>-->
<?php //}?>
<?php //}?>

<div class="panel panel-body panel-default">
    <h1 class="panel-title">可发布的广告：</h1>
    <div class="panel-body">
        <ul class="list-group">
            <?php foreach($data as $k => $v){?>
            <li class="list-group-item list-group-item-info">
                <?php echo Html::checkbox('advert[id][]', false, ['value' => $v['id']]);?>
                <?php echo $v['position_name'].':'.$v['operation_advert_content_name'];?>
                <label>上线时间：<?php echo Html::textInput('advert[starttime][]');?></label>
                <label>上线时间：<?php echo Html::textInput('advert[endtime][]');?> 格式：2015-10-01 08:20:25</label>
            </li>
            <?php }?>
        </ul>
    </div>
</div>

<div class="form-group">
    <?=Html::submitButton('发布', ['class' => 'btn btn-success form-control']) ?>
</div>
<?php ActiveForm::end(); ?>
