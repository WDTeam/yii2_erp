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

use core\models\customer\Customer;
use core\models\customer\CustomerExtSrc;
use core\models\customer\CustomerAddress;
use core\models\order\OrderSearch;
use core\models\customer\CustomerComment;
use core\models\customer\CustomerExtScore;
use core\models\customer\CustomerExtBalance;
use core\models\customer\CustomerBlockLog;

use core\models\operation\coupon\Coupon;
use core\models\operation\coupon\CouponCustomer;
use core\models\operation\coupon\CouponCode;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Worker $model
 */
$this->title = '客户详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['View']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="customer-view">
<?php 
//城市
$city_name = core\models\operation\OperationCity::getCityName($model->operation_city_id);

$customer_ext_srcs = Customer::getSrcs($model->customer_phone);
$platform_name_str = '';
$channal_name_str = '';
$device_name_str = '';
$device_no_str = '';
if(empty($customer_ext_srcs)){
	$platform_name_str = '-';
	$channal_name_str = '-';
	$device_name_str = '-';
	$device_no_str = '-';
}else{
	foreach($customer_ext_srcs as $customer_ext_src){
		$platform_name= empty($customer_ext_src) ? '-' : empty($customer_ext_src['platform_name']) ? '-' : $customer_ext_src['platform_name']; 
		$channal_name = empty($customer_ext_src) ? '-' : empty($customer_ext_src['channal_name']) ? '-' : $customer_ext_src['channal_name']; 
		$device_name = empty($customer_ext_src) ? '-' : empty($customer_ext_src['device_name']) ? '-' : $customer_ext_src['device_name']; 
		$device_no = empty($customer_ext_src) ? '-' : empty($customer_ext_src['device_no']) ? '-' : $customer_ext_src['device_no']; 

		$platform_name_str .= $platform_name.'&nbsp;&nbsp;';
		$channal_name_str .= $channal_name.'&nbsp;&nbsp;';
		$device_name_str .= $device_name.'&nbsp;&nbsp;';
		$device_no_str .= $device_no.'&nbsp;&nbsp;';
	}
}


//全部服务地址
$customerAddress = CustomerAddress::listAddress($model->id);

$addressStr = '';
if(!empty($customerAddress)){
	foreach ($customerAddress as $address) {
		if ($address != NULL) {
		    $addressStr .= $address->operation_province_name
		        .$address->operation_city_name
		        .$address->operation_area_name
		        .$address->customer_address_detail
		        .' | '.$address->customer_address_nickname
		        .' | '.$address->customer_address_phone . '<br/>';
		}
	}
}
//var_dump($addressStr);
//exit();

//订单
$order_count = OrderSearch::getCustomerOrderCount($model->id);
//评价数量
$comment_count = CustomerComment::getCustomerCommentCount($model->id);
//$comment_count = 0;
//积分
$score = CustomerExtScore::getCustomerScore($model->id);
//余额
$balance = CustomerExtBalance::getCustomerbalance($model->id);
//历史状态集
$customerBlockLog = CustomerBlockLog::listBlockLog($model->id);
//当前状态
$currentBlockStatus = CustomerBlockLog::getCurrentBlockStatus($model->id);
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
        
         'customer_name',
        [
            'attribute'=>'', 
            'label'=>'城市',
            'format'=>'raw',
            'value'=>$city_name,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        'customer_phone',
        [
            'attribute'=>'', 
            'label'=>'平台',
            'format'=>'raw',
            'value'=>$platform_name_str,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
       [
            'attribute'=>'', 
            'label'=>'聚道',
            'format'=>'raw',
            'value'=>$channal_name_str,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'设备',
            'format'=>'raw',
            'value'=>$device_name_str,
            'type'=>DetailView::INPUT_SWITCH,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'设备号',
            'format'=>'raw',
            'value'=>$device_no_str,
            'type'=>DetailView::INPUT_SWITCH,
            'valueColOptions'=>['style'=>'width:90%']
        ],
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
            'attribute'=>'', 
            'label'=>'接单地址',
            'format'=>'raw',
            'value'=> $addressStr,
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
//     'mode'=>DetailView::MODE_VIEW,
//     'panel'=>[
//         'heading'=>'评价信息',
//         'type'=>DetailView::TYPE_INFO,
//     ],
//     'attributes' => [
//         [
//             'attribute'=>'', 
//             'label'=>'评价',
//             'format'=>'raw',
//             'value'=> '<a href="/order/index?OrderSearch[customer_id]='. $model->id .'">'. $comment_count .'</a>',
//             'type'=>DetailView::INPUT_TEXT,
//             'valueColOptions'=>['style'=>'width:90%']
//         ],
//     ],
//     'enableEditMode'=>false,
// ]); 

echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'其他信息',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'', 
            'label'=>'账户状态',
            'format'=>'raw',
            'value'=>$currentBlockStatus['block_status_name'],
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'订单总数',
            'format'=>'raw',
            'value'=>Html::a($order_count, ['order/index', 'OrderSearch[customer_id]'=>$model->id]),
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'评价总数',
            'format'=>'raw',
            'value'=>Html::a($comment_count, ['customer/customer-comment/index', 'CustomerCommentSearch[customer_id]'=>$model->id]),
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
		[
            'attribute'=>'', 
            'label'=>'投诉总数',
            'format'=>'raw',
            'value'=>'0',
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'账户余额',
            'format'=>'raw',
            'value'=>$balance,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'总积分数',
            'format'=>'raw',
            'value'=>$score,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
    ],
    'enableEditMode'=>false,
]); 

//listCustomerCoupon($phone);
$couponCustomerProvider = new ActiveDataProvider([
	'query' => \core\models\operation\coupon\CouponCustomer::find()->where(['customer_id'=>$model->id])
]);
echo GridView::widget([
    'dataProvider' => $couponCustomerProvider,
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
            'label' => '类别',
            'value' => function($couponCustomerProvider){
				$coupon = Coupon::find($couponCustomerProvider->coupon_id);
				return $coupon->coupon_type_name;
			},
            'width' => "80px",
        ],
        [
            'format' => 'raw',
            'label' => '金额',
            'value' => function($couponCustomerProvider){
				return $couponCustomerProvider->coupon_price;
			},
            'width' => "80px",
        ],
        [
            'format' => 'raw',
            'label' => '到期日',
            'value' => function($couponCustomerProvider){
				return date('Y-m-d H:i:s', $couponCustomerProvider->expirate_at);
			},
            'width' => "80px",
        ],
    ],
]);

$customerBlockLogProvider = new ActiveDataProvider(['query' => \core\models\customer\CustomerBlockLog::find()->where(['customer_id'=>$model->id])]);
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
                return $customerBlockLogProvider == NULL ? "未知" : $customerBlockLogProvider->customer_block_log_status ? '封号' : '正常';
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
?>
</div>


