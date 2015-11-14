<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationShopDistrictGoods $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-shop-district-goods-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'operation_goods_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商品编号...']], 

'operation_shop_district_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商圈id...']], 

'operation_city_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市编号...']], 

'operation_category_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对应服务品类编号（所属分类编号冗余）...']], 

'operation_shop_district_goods_service_interval_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务间隔时间(单位：分钟)...']], 

'operation_shop_district_goods_service_estimate_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预计服务时长(单位：分钟)...']], 

'operation_shop_district_goods_lowest_consume_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 最低消费数量...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 编辑时间...']], 

'operation_shop_district_goods_introduction'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 服务类型简介...','rows'=> 6]], 

'operation_shop_district_goods_service_time_slot'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 可服务时间段（序列化方式存储）...','rows'=> 6]], 

'operation_spec_info'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 序列化存储规格...']], 

'operation_shop_district_goods_price_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 价格备注...','rows'=> 6]], 

'operation_tags'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 服务类型标签编号(序列化方式存储)...','rows'=> 6]], 

'operation_goods_img'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 商品图片...','rows'=> 6]], 

'operation_shop_district_goods_app_ico'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)...','rows'=> 6]], 

'operation_shop_district_goods_pc_ico'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)...','rows'=> 6]], 

'operation_shop_district_goods_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 售价...', 'maxlength'=>19]], 

'operation_shop_district_goods_balance_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨结算价格...', 'maxlength'=>19]], 

'operation_shop_district_goods_additional_cost'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 附加费用...', 'maxlength'=>19]], 

'operation_shop_district_goods_lowest_consume'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 最低消费价格...', 'maxlength'=>19]], 

'operation_shop_district_goods_market_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 市场价格...', 'maxlength'=>19]], 

'operation_shop_district_goods_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商品名称...', 'maxlength'=>60]], 

'operation_shop_district_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商圈名称...', 'maxlength'=>60]], 

'operation_category_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对应服务品类名称...', 'maxlength'=>60]], 

'operation_shop_district_goods_english_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类型英文名称...', 'maxlength'=>60]], 

'operation_spec_strategy_unit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 计量单位...', 'maxlength'=>60]], 

'operation_shop_district_goods_no'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商品货号...', 'maxlength'=>20]], 

'operation_shop_district_goods_start_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 开始服务时间即开始时间...', 'maxlength'=>20]], 

'operation_shop_district_goods_end_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 结束服务时间即结束时间...', 'maxlength'=>20]], 

'operation_city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市名称...', 'maxlength'=>50]], 

'operation_category_ids'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对应服务品类的所有编号以“,”关联...', 'maxlength'=>255]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
