<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

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

$this->title = '顾客详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">
    <div class="page-header">
        <!--<h1><?= Html::encode($this->title) ?></h1>-->
    </div>
<?php 
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    // 'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'基本信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'', 
            'label'=>'城市',
            'format'=>'raw',
            'value'=> $operationCity ? $operationCity->city_name : '-',
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'customer_name', 
            'label'=>'姓名',
            'format'=>'raw',
            'value'=>$model->customer_name,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'customer_phone', 
            'label'=>'手机号',
            'format'=>'raw',
            'value'=>$model->customer_phone,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'platform_id', 
            'label'=>'平台',
            'format'=>'raw',
            'value'=> $customerPlatform ? $customerPlatform->platform_name : '-',
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'channal_id', 
            'label'=>'聚道',
            'format'=>'raw',
            'value'=>$customerChannal ? $customerChannal->channal_name : '-',
            'type'=>DetailView::INPUT_SWITCH,
            'valueColOptions'=>['style'=>'width:90%']
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
            'attribute'=>'customer_is_vip', 
            'label'=>'身份',
            'format'=>'raw',
            'value'=>$model->customer_is_vip ? '会员' : '非会员',
            'type'=>DetailView::INPUT_SWITCH,
            'valueColOptions'=>['style'=>'width:90%']
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
            'type'=>DetailView::INPUT_SWITCH,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'customer_live_address_detail', 
            'label'=>'接单地址',
            'format'=>'raw',
            'value'=> $order_addresses,
            'type'=>DetailView::INPUT_SWITCH,
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
            'type'=>DetailView::INPUT_SWITCH,
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
            'type'=>DetailView::INPUT_SWITCH,
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


