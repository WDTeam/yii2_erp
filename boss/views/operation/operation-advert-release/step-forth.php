<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\datecontrol\Module;
use dosamigos\datetimepicker\DateTimePicker;

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
            <table class="table">
                <thead>
                    <tr>
                        <th>选择</th>
                        <th>位置名称</th>
                        <th>广告名称</th>
                        <th>上线时间</th>
                        <th>下线时间</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data as $k => $v){?>
                    <tr>
                        <td><?php echo Html::checkbox('advert[id][]', false, ['value' => $v['id']]);?></td>
                        <td><?php echo $v['position_name'];?></td>
                        <td><?php echo $v['operation_advert_content_name'];?></td>
                        <td>
                            <?php
                                echo Html::textInput('advert[starttime][]');
                            ?> 格式：2015-10-01 08:20:25

                            <?php
                                //echo '<label class="control-label">'.$model->attributeLabels()['operation_advert_start_time'].'</label>';
                                //echo DatePicker::widget([
                                //'name' => 'OperationAdvertContent[operation_advert_start_time]',
                                //'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                //'value' => !empty($model->operation_advert_start_time) ? date('Y-m-d', $model->operation_advert_start_time) : '',
                                //'pluginOptions' => [
                                    //'autoclose'=>true,
                                    //'format' => 'yyyy-mm-dd'
                                //]
                            //]);
                            ?>
                        </td>
                        <td>
                            <?php
                                echo Html::textInput('advert[endtime][]');
                            ?> 格式：2015-10-01 08:20:25
                            <?php
                                //echo '<label class="control-label">'.$model->attributeLabels()['operation_advert_end_time'].'</label>';
                                //echo DatePicker::widget([
                                //'name' => 'OperationAdvertContent[operation_advert_end_time]',
                                //'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                //'value' => !empty($model->operation_advert_start_time) ? date('Y-m-d', $model->operation_advert_end_time) : '',
                                //'pluginOptions' => [
                                    //'autoclose'=>true,
                                    //'format' => 'yyyy-mm-dd'
                                //]
                            //]);
                            ?>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </ul>
    </div>
</div>

<div class="form-group">
    <?=Html::submitButton('发布', ['class' => 'btn btn-success form-control']) ?>
</div>
<?php ActiveForm::end(); ?>

<div class="control-group">
    <label class="control-label">DateTime Picking</label>
    <div class="controls input-append date form_datetime" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
        <input size="16" type="text" value="" readonly>
        <span class="add-on"><i class="icon-remove"></i></span>
        <span class="add-on"><i class="icon-th"></i></span>
    </div>
    <input type="hidden" id="dtp_input1" value="" /><br/>
</div>
<?php $this->beginBlock('myjs') ?>
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['myjs'],['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']], \yii\web\View::POS_END); ?>

