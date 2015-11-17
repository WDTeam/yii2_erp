<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

use dosamigos\datetimepicker\DateTimePickerAsset;

DateTimePickerAsset::register($this);

$this->title = Yii::t('app', '第四步：选择广告完成发布');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
<label class="control-label" for="operationadvertrelease-city_id"><?php echo $this->title?></label>

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
                        <td>
                            <?php echo Html::checkbox('advert['. $k .'][id]', false, ['value' => $v['id']]);?>
                        </td>
                        <td><?php echo $v['position_name'];?></td>
                        <td><?php echo $v['operation_advert_content_name'];?></td>
                        <td>
                            <div class="control-group">
                                <div class="form-controls input-append date form_datetime"
 data-link-field="<?php echo $v['id'] . '_starttime';?>">
                                    <input size="26" type="text" value="" readonly>
                                    <span class="add-on"><i class="icon-remove"></i></span>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                                <input type="hidden" id="<?php echo $v['id'] . '_starttime';?>" value="" name="advert[<?php echo $k ?>][starttime]" /><br/>
                            </div>
                        </td>
                        <td>
                            <div class="control-group">
                                <div class="form-controls input-append date form_datetime" data-link-field="<?php echo $v['id'] . '_endtime';?>">
                                    <input size="26" type="text" value="" readonly>
                                    <span class="add-on"><i class="icon-remove"></i></span>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                                <input type="hidden" id="<?php echo $v['id'] . '_endtime';?>" value="" name="advert[<?php echo $k ?>][endtime]" /><br/>
                            </div>
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

<?php $this->beginBlock('myjs') ?>

    var myDate = new Date();            //当前时间
    var year   = myDate.getFullYear();  //当前年份
    var month  = myDate.getMonth() + 1; //当前月份
    var day    = myDate.getDate();      //当前日

    var mytime = year + '-' + month + '-' + day;

    $('.form_datetime').datetimepicker({
        format:         'yyyy-mm-dd hh:ii:ss',
        language:       'zh-CN',
        weekStart:      1,
        todayBtn:       1,
		autoclose:      1,
		todayHighlight: 1,
		startView:      2,
		forceParse:     0,
        showMeridian:   1,
        startDate :     mytime
    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['myjs'], \yii\web\View::POS_END); ?>

