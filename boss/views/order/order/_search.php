<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use boss\models\order\Order;

/**
 * @var yii\web\View $this
 * @var core\models\order\OrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style type="text/css">
    .select2-container--krajee {
        display: block;
        float: right;
    }

    #m_warp .form-group {
        width: 280px;
        float: left;
    }

    .select2-container--krajee {
        display: block;
        float: left;
    }

    #m_warp .m_riqi .btn {
        margin-left: 65px;
        float: left !important;
        background: #f6a202;
        text-align: center !important;
        height: 32px !important;
        line-height: 32px !important;
        font-size: 14px;
        padding: 0 !important;
        border-radius: 3px !important;
        width: 180px !important;
    }
</style>
<div class="heading">
    <h3 class="panel-title"><i style="margin-right:5px;" class="glyphicon glyphicon-search"></i>查询</h3>
</div>
<div class="m_from">
    <?php
    $a1 = explode('\\', get_class($searchModel));
    $search_class_name = array_pop($a1);
    $form = ActiveForm::begin([
        //'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['order/order/index'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($searchModel, 'order_customer_phone')->TextInput(['class' => 'm_ipu input_customer_phone'])->label('客户电话 :', ['class' => 'm_ipone']); ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($searchModel, 'shop_id')->widget(Select2::classname(), [
                'initValueText' => '门店:', // set the initial display text
                'options' => ['placeholder' => '选择门店', 'class' => 'm_ipu'],
                'pluginOptions' => [
                    'width' => '65%',
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['show-shop']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
            ])->label('门店:', ['class' => 'm_ipone', 'style' => 'line-height:35px']); ?>
        </div>
        <div class="col-sm-6">
            <div class="form-group" style="width: auto;">
                <label class="m_ipone">下单时间:</label>
                <input name="<?= $search_class_name ?>[created_from]" type="text" class="Wdate" id="d412"
                       onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})"
                       value="<?= isset($searchParas[$search_class_name]['created_from']) ? Html::encode($searchParas[$search_class_name]['created_from']) : '' ?>"/>到
                <input name="<?= $search_class_name ?>[created_to]" type="text" class="Wdate" id="d412"
                       onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})"
                       value="<?= isset($searchParas[$search_class_name]['created_to']) ? Html::encode($searchParas[$search_class_name]['created_to']) : '' ?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($searchModel, 'order_worker_phone')->TextInput(['class' => 'm_ipu'])->label('阿姨电话 :', ['class' => 'm_ipone']); ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($searchModel, 'district_id')->widget(Select2::classname(), [
                'name' => 'worker_district',
                'hideSearch' => true,
                'data' => Order::getDistrictList(),
                'options' => ['placeholder' => '选择商圈', 'class' => 'm_ipu'],
                'pluginOptions' => [
                    'width' => '65%',
                    'tags' => true,
                    'maximumInputLength' => 10
                ],
            ])->label('商圈:', ['class' => 'm_ipone', 'style' => 'line-height:35px']); ?>
        </div>
        <div class="col-sm-6">
            <div class="form-group" style="width: auto;">
                <label class="m_ipone">服务时间:</label>
                <input name="<?= $search_class_name ?>[booked_from]" type="text" class="Wdate" id="d412"
                       onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})"
                       value="<?= isset($searchParas[$search_class_name]['booked_from']) ? Html::encode($searchParas[$search_class_name]['booked_from']) : '' ?>"/>到
                <input name="<?= $search_class_name ?>[booked_to]" type="text" class="Wdate" id="d412"
                       onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'1990-03-08 00:00:00',maxDate:'2030-12-32 23:59:59'})"
                       value="<?= isset($searchParas[$search_class_name]['booked_to']) ? Html::encode($searchParas[$search_class_name]['booked_to']) : '' ?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
        <?= $form->field($searchModel, 'order_code')->TextInput(['class' => 'm_ipu'])->label('订单编号 :', ['class' => 'm_ipone']); ?>
        </div>
        <div class="col-sm-3">
        <?= $form->field($searchModel, 'order_address')->TextInput(['class' => 'm_ipu'])->label('客户地址 :', ['class' => 'm_ipone']); ?>
        </div>
        <div class="col-sm-3">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary button-search']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="clear"></div>
</div>
