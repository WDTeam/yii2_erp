<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
use dosamigos\datetimepicker\DateTimePicker;
?>

<div class="panel-heading">
    <h3 class="panel-title"></h3>
    <input type="hidden" name="OperationGoods[operation_spec_name]" value="">
</div>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<?php foreach((array)$goodslist as $key => $value){ ?>
    <div class="form-group ">
        <label class="control-label col-md-2"><?= $value['operation_goods_name']?></label>
        <input type="hidden" name="goodinfo[goodids][]" value="<?= $value['id']?>"/>
        <div class="col-md-10">
            市场价格：<input type="text" maxlength="" style="width:50px;" placeholder="市场价格" value="" name="goodinfo[operation_goods_market_price][]" >元
            销售价格：<input type="text" maxlength="" style="width:50px;" placeholder="销售价格" value="" name="goodinfo[operation_goods_price][]" >元
            最低消费数量：<input type="text" maxlength="" style="width:70px;" placeholder="最低消费数量" value="" name="goodinfo[operation_goods_lowest_consume][]" > <?= $value['operation_spec_strategy_unit']?>
            开始服务时间：<input type="text" maxlength="" style="width:50px;" placeholder="开始时间" value="" name="goodinfo[operation_goods_start_time][]" >
            结束服务时间：<input type="text" maxlength="" style="width:50px;" placeholder="结束时间" value="" name="goodinfo[operation_goods_end_time][]" >
        </div>
        <div>
            <?php 
                
            ?>
            <?php
//                echo DateTimePicker::widget([
//                        'name' => 'stat_time[]',
//        //                'type' => '',
//                        'language' => 'es',
//                        'template' => '{input}',
//                        'pickButtonIcon' => 'glyphicon glyphicon-time',
//                        'inline' => true,
//                        'size' => 'ms',
//                        'options' => [
//                                'readonly' => true
//                        ],
//                        'clientOptions' => [
//                            'startView' => 1,
//                            'minView' => 0,
//                            'maxView' => 1,
//                            'autoclose' => true,
//                            'linkFormat' => 'HH:ii ', // if inline = true
//                            'todayBtn' => true,
//                            'todayHighlight' => true,
//                        ]
//                ]);

                ?>
            <?php
//                echo DateTimePicker::widget([
//                        'name' => 'stat_time[]',
//        //                'type' => '',
//                        'language' => 'es',
//                        'template' => '{input}',
//                        'pickButtonIcon' => 'glyphicon glyphicon-time',
//                        'inline' => true,
//                        'size' => 'ms',
//                        'options' => [
//                                'readonly' => true
//                        ],
//                        'clientOptions' => [
//                            'startView' => 1,
//                            'minView' => 0,
//                            'maxView' => 1,
//                            'autoclose' => true,
//                            'linkFormat' => 'HH:ii ', // if inline = true
//                            'todayBtn' => true,
//                            'todayHighlight' => true,
//                        ]
//                ]);
                ?>
        </div>
        <div class="col-md-offset-2 col-md-10"></div>
        <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
    </div>
<?php }?>
<?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>