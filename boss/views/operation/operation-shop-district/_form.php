<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\datetimepicker\DateTimePicker;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationShopDistrict $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="operation-shop-district-form">
    
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">基础信息</h3>
        </div>
        <div class="panel-body">
            <?php
            echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'operation_shop_district_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'商圈名称...', 'maxlength'=>20]],
            ]


            ]);
            ?>
            <?php if($citymodel) { ?>
                <?= $form->field($citymodel, 'city_name')->textInput() ?>
            <?php }?>

            <?= $form->field($model, 'operation_area_id')->dropDownList($areaList,['prompt' => '请选择区域'])->label('所属区域') ?>
            <div class="form-group field-operationshopdistrict-operation_area_id">
                &nbsp;&nbsp;&nbsp;&nbsp;<div class="glyphicon glyphicon-plus addshopdistrictcoordinate" ></div>
            </div>
            <div class="shopdistrictcoordinatelist">
                <?php if($operation == 'update'){ ?>
                    <?php foreach((array)$OperationShopDistrictCoordinateList as $key => $value){ ?>
                        <div class="form-group ">
                            <div class="col-md-10">
                                开始经度：<input type="text" class="longitude" style="width:50px;" value="<?= $value['operation_shop_district_coordinate_start_longitude']?>" name="operation_shop_district_coordinate_start_longitude[]" >
                                开始纬度：<input type="text" class="latitude" style="width:50px;" value="<?= $value['operation_shop_district_coordinate_start_latitude']?>" name="operation_shop_district_coordinate_start_latitude[]" >
                                结束经度：<input type="text" class="longitude" style="width:70px;" value="<?= $value['operation_shop_district_coordinate_end_longitude']?>" name="operation_shop_district_coordinate_end_longitude[]" >
                                结束纬度：<input type="text" class="latitude" style="width:50px;" value="<?= $value['operation_shop_district_coordinate_end_latitude']?>" name="operation_shop_district_coordinate_end_latitude[]" >
                                <?php if($key != 0){ ?>
                                    <div class="glyphicon glyphicon-minus delshopdistrictcoordinate" ></div>
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                    <?php }else{ ?>
                    <div class="form-group ">
                        <div class="col-md-10">
                            开始经度：<input type="text" class="longitude" style="width:50px;" value="" name="operation_shop_district_coordinate_start_longitude[]" >
                            开始纬度：<input type="text" class="latitude" style="width:50px;" value="" name="operation_shop_district_coordinate_start_latitude[]" >
                            结束经度：<input type="text" class="longitude" style="width:70px;" value="" name="operation_shop_district_coordinate_end_longitude[]" >
                            结束纬度：<input type="text" class="latitude" style="width:50px;" value="" name="operation_shop_district_coordinate_end_latitude[]" >
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block createlal' : 'btn btn-primary btn-lg btn-block createlal']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
