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
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use common\models\CustomerAddress;
use common\models\CustomerWorker;
use common\models\GeneralRegion;
use common\models\OperationCity;
use common\models\CustomerExtBalance;
use common\models\CustomerExtScore;
use common\models\CustomerExtSrc;
use common\models\CustoemrBlockLog;
use common\models\CustomerComment;
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
<?php 
$customerExtSrc = CustomerExtSrc::find()->where(['customer_id'=>$model->id])->orderBy('created_at asc')->one();
$platform_name_str = '';
$channal_name_str = '';
if ($customerExtSrc == NULL) {
    $platform_name_str = '';
    $channal_name_str = '';
}else{
    $platform_name_str = $customerExtSrc->platform_name;
    $channal_name_str = $customerExtSrc->channal_name;
}
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
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
                'name'=>'id',
                'class'=>\kartik\widgets\Select2::className(),
                'data' => ArrayHelper::map(OperationCity::find()->asArray()->all(), 'id', 'city_name'),
                'hideSearch' => true,
                'options'=>[
                    'placeholder' => '选择城市',
                ]
            ],
            'value'=>$operationCity['city_name'] ? $operationCity['city_name'] : '-',
        ],
        // 'customer_name',
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
            'value'=>$platform_name_str,
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
            'value'=>$channal_name_str,
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
                    'name'=>'customer_is_vip',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => array('1'=>'会员', '0'=>'非会员'),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择客户身份',
                    ]
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
    'enableEditMode'=>false,
]); 

// $order_count = Order::find()->where(['customer_id'=>$model->id])->scalar();
$order_count = \core\models\order\OrderSearch::getCustomerOrderCount($model->id);
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
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
    'enableEditMode'=>false,
]); 

// $customerComment = CustomerComment::find()->where(['customer_id'=>$model->id])->all();
$commentCount = CustomerComment::find()->where(['customer_id'=>$model->id])->count();
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'评价信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'', 
            'label'=>'评价',
            'format'=>'raw',
            'value'=> '<a href="/order/index?OrderSearch[customer_id]='. $model->id .'">'. $commentCount .'</a>',
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>false,
]); 

$customerExtScore = CustomerExtScore::find()->where(['customer_id'=>$model->id])->one();
// $customer_score = \common\models\CustomerExtScore::getScore($model->id);
echo DetailView::widget([
    'model' => $customerExtScore,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'积分信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'customer_score', 
            'label'=>'积分',
            'format'=>'raw',
            'value'=> $customerExtScore == NULL ? 0 : $customerExtScore->customer_score,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>false,
]);

$customerExtBalance = CustomerExtBalance::find()->where(['customer_id'=>$model->id])->one();
echo DetailView::widget([
    'model' => $customerExtBalance,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'账户余额',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'customer_balance', 
            'label'=>'余额',
            'format'=>'raw',
            'value'=> $customerExtBalance == NULL ? 0 : $customerExtBalance->customer_balance,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>false,
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


// $customerBlockLog = \common\models\CustomerBlockLog::findAll('customer_id'=>$model->id);
$customerBlockLogProvider = new ActiveDataProvider(['query' => \common\models\CustomerBlockLog::find(),]);
echo GridView::widget([
    'dataProvider' => $customerBlockLogProvider,
    // 'responsive' => false,
    // 'hover' => false,
    // 'condensed' => false,
    // 'floatHeader' => false,
    // 'panel' => [
    //     'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i>历史状态信息</h3>',
    //     'type' => 'info',
    //     'before' =>'',
    //     'after' =>'',
    //     'showFooter' => false
    // ],
    'columns'=>[
        [
            'format' => 'raw',
            'label' => '历史状态',
            'value' => function ($customerBlockLogProvider) {
                return $customerBlockLogProvider == NULL ? "未知" : $customerBlockLogProvider->customer_block_log_status ? '黑名单' : '正常';
            },
            'width' => "80px",
        ],
        [
            'format' => 'raw',
            'label' => '原因',
            'value' => function ($customerBlockLogProvider) {
                return $customerBlockLogProvider == NULL ? "未知" : $customerBlockLogProvider->customer_block_log_reason;
            },
            'width' => "80px",
        ],
        [
            'format' => 'raw',
            'label' => '时间',
            'value' => function ($customerBlockLogProvider) {
                return date('Y-m-d H:i:s', $customerBlockLogProvider->created_at);
            },
            'width' => "80px",
        ],
    ],
]);

$currentBlockStatus = \common\models\CustomerBlockLog::getCurrentBlockStatus($model->id);
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'当前状态信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'customer_balance', 
            'label'=>'当前状态',
            'format'=>'raw',
            'value'=> empty($currentBlockStatus) ? '未知' : $currentBlockStatus['block_status_name'],
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>false,
]); 
?>

</div>


