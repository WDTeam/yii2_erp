<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\datetimepicker\DateTimePicker;
use boss\components\GoodsTypeCascade;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-goods-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php
        echo GoodsTypeCascade::widget([
            'model' => $model,
            'options' => ['class' => 'form-control'],
            'name' => 'OperationGoods[operation_category_ids][]',
        ]);
    ?>
    <?php echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'operation_goods_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'商品名称...', 'maxlength'=>60]],

//        'operation_category_ids'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对应服务品类名称']],


        'operation_goods_introduction'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 服务类型简介...','rows'=> 6]],

//        'operation_category_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对应服务品类名称（所属分类名称冗余）...', 'maxlength'=>60]],


        'operation_goods_english_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类型英文名称...', 'maxlength'=>60]],


        ]]); ?>
        <?= $form->field($model, 'operation_goods_start_time')->widget(DateTimePicker::className(), [
            'language' => 'es',
            'size' => 'ms',
            'template' => '{input}',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'inline' => true,
            'clientOptions' => [
                'startView' => 1,
                'minView' => 0,
                'maxView' => 1,
                'autoclose' => true,
                'linkFormat' => 'HH:ii', // if inline = true
                // 'format' => 'HH:ii P', // if inline = false
                'todayBtn' => true
            ]
        ]);?>
        <?= $form->field($model, 'operation_goods_end_time')->widget(DateTimePicker::className(), [
            'language' => 'es',
            'size' => 'ms',
            'template' => '{input}',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'inline' => true,
            'clientOptions' => [
                'startView' => 1,
                'minView' => 0,
                'maxView' => 1,
                'autoclose' => true,
                'linkFormat' => 'HH:ii P', // if inline = true
                'todayBtn' => true
            ]
        ]);?>
    
        <?= $form->field($model, 'operation_spec_info')->dropDownList($OperationSpec, ['prompt' => '请选择规格', 'id' => 'operationSpec'])->label('选择规格') ?>

        <div id="SpecInfo">
            <?php if($status == 'update') {
                echo $this->render('specinfo', [
                    'specvalues' => $specvalues,
                ]);
            }
            ?>
        </div>
    
        <?= $form->field($model, 'operation_goods_service_interval_time')->textInput(['maxlength' => true]) ?>
   
        <?= $form->field($model, 'operation_goods_service_estimate_time')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'operation_goods_additional_cost')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'operation_goods_price_description')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'operation_tags')->textInput(['maxlength' => true]) ?>
    
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_app_homepage_max_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_app_homepage_max_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_app_homepage_max_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_app_homepage_min_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_app_homepage_min_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_app_homepage_min_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_app_type_min_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_app_type_min_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_app_type_min_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_app_order_min_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_app_order_min_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_app_order_min_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_pc_homepage_max_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_pc_homepage_max_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_pc_homepage_max_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_pc_more_max_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_pc_more_max_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_pc_more_max_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
        <?php
         echo '<div class="form-group ">
            <label class="control-label col-md-2">'.$model->attributeLabels()['operation_goods_pc_submit_order_min_ico'].'</label>
            <div class="col-md-10"><input type="hidden" value="" name="OperationGoods[operation_goods_pc_submit_order_min_ico]">
            <input type="file" maxlength="" name="OperationGoods[operation_goods_pc_submit_order_min_ico]" ></div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>';
        ?>
    
<?php
//        'operation_goods_service_interval_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务间隔时间(单位：秒）...']],
//
//        'operation_price_strategy_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 价格策略编号...']],
//
//        'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']],
//
//        'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 编辑时间...']],
//
//
//
//        'operation_goods_service_time_slot'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 可服务时间段（序列化方式存储）...','rows'=> 6]],
//
//        'operation_goods_price_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 价格备注...','rows'=> 6]],
//
//        'operation_tags'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 服务类型标签编号(序列化方式存储)...','rows'=> 6]],
//
//        'operation_goods_app_ico'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)...','rows'=> 6]],
//
//        'operation_goods_pc_ico'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)...','rows'=> 6]],
//
//        'operation_goods_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 售价...', 'maxlength'=>19]],
//
//        'operation_goods_balance_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨结算价格...', 'maxlength'=>19]],
//
//        'operation_goods_additional_cost'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 附加费用...', 'maxlength'=>19]],
//
//        'operation_goods_lowest_consume'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 最低消费...', 'maxlength'=>19]],
//
//        'operation_goods_market_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 市场价格...', 'maxlength'=>19]],
//
//
//
//
//
//
//
//        'operation_price_strategy_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 价格策略名称...', 'maxlength'=>60]],


//    ]
//
//
//    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
