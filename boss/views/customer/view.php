<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\builder\Form;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use boss\components\AreaCascade;

use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use common\models\CustomerAddress;
use common\models\CustomerWorker;

use common\models\GeneralRegion;
use common\models\OperationCity;

use common\models\Order;


/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */

$this->title = '客户详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['View']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">
    <div class="page-header">
        <!--<h1><?= Html::encode($this->title) ?></h1>-->
    </div>
    <div class="panel-body">
        <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
<?php 
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'基本信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        // [
        //     'attribute'=>'', 
        //     'label'=>'城市',
        //     'format'=>'raw',
        //     'value'=> $operationCity ? $operationCity->city_name : '-',
        //     'type'=>DetailView::INPUT_TEXT,
        //     'valueColOptions'=>['style'=>'width:90%']
        // ],
        [
            'attribute' => 'operation_city_id',
            'type' => DetailView::INPUT_WIDGET,
            'widgetOptions' => [
                'name'=>'operation_city_id',
                'class'=>\kartik\widgets\Select2::className(),
                'data' => [1 => '北京', 2=>'', 3=>'', 4=>''],
                'hideSearch' => true,
                'options'=>[
                    'placeholder' => '选择城市',
                ]
            ],
            'value'=>$operationCity['city_name'] ? $operationCity['city_name'] : '-',
        ],
        'customer_name',
        'customer_phone',
        [
            'attribute' => 'platform_id',
            'type' => DetailView::INPUT_WIDGET,
            'widgetOptions' => [
                'name'=>'platform_id',
                'class'=>\kartik\widgets\Select2::className(),
                'data' => $platforms,
                'hideSearch' => true,
                'options'=>[
                    'placeholder' => '选择平台',
                ]
            ],
            'value'=>$customerPlatform['platform_name'] ? $customerPlatform['platform_name'] : '-',
        ],
        [
            'attribute' => 'channal_id',
            'type' => DetailView::INPUT_WIDGET,
            'widgetOptions' => [
                'name'=>'channal_id',
                'class'=>\kartik\widgets\Select2::className(),
                'data' => $channals,
                'hideSearch' => true,
                'options'=>[
                    'placeholder' => '选择聚道',
                ]
            ],
            'value'=>$customerChannal['channal_name'] ? $customerChannal['channal_name'] : '-',
        ],
        // [
        //     'attribute'=>'customer_phone', 
        //     'label'=>'设备',
        //     'format'=>'raw',
        //     'value'=>$model->customer_phone,
        //     'type'=>DetailView::INPUT_SWITCH,
        //     'valueColOptions'=>['style'=>'width:90%']
        // ],
        // [
        //     'attribute'=>'customer_phone', 
        //     'label'=>'设备号',
        //     'format'=>'raw',
        //     'value'=>$model->customer_phone,
        //     'type'=>DetailView::INPUT_SWITCH,
        //     'valueColOptions'=>['style'=>'width:90%']
        // ],
        [
                'attribute' => 'customer_is_vip',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'value'=>$model->customer_is_vip ? '会员' : '非会员',
            ],
        [
            'attribute'=>'customer_live_address_detail', 
            'label'=>'住址',
            'format'=>'raw',
            'value'=>$generalRegion ? 
                $generalRegion->general_region_province_name
                .$generalRegion->general_region_city_name 
                .$generalRegion->general_region_area_name
                .$model->customer_live_address_detail
                : '-',
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'customer_live_address_detail', 
            'label'=>'接单地址',
            'format'=>'raw',
            'value'=> $addressStr,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    // 'enableEditMode'=>true,
]); 

echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'订单信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'', 
            'label'=>'订单',
            'format'=>'raw',
            'value'=> '<a href="/order/index?OrderSearch[customer_id]='. $model->id .'">'. $order_count .'</a>',
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>true,
]); 

echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'账户余额',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'customer_balance', 
            'label'=>'余额',
            'format'=>'raw',
            'value'=> $model->customer_balance,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>true,
]); 

// echo DetailView::widget([
//     'model' => $model,
//     'condensed'=>false,
//     'hover'=>true,
//     'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
//     'panel'=>[
//         'heading'=>'剩余优惠券',
//         'type'=>DetailView::TYPE_INFO,
//     ],
//     'attributes' => [
//         [
//             'attribute'=>'', 
//             'label'=>'',
//             'format'=>'raw',
//             'value'=> '',
//             'type'=>DetailView::INPUT_TEXT,
//             'valueColOptions'=>['style'=>'width:90%']
//         ],
//     ],
//     'enableEditMode'=>true,
// ]); 
?>

</div>


