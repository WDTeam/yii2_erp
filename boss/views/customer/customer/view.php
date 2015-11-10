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
//$city_name = core\models\operation\OperationCity::getCityName($model->operation_city_id);
$operation_city_name = '-';

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

		$platform_name_str .= $platform_name.'/';
		$channal_name_str .= $channal_name.'/';
		$device_name_str .= $device_name.'/';
		$device_no_str .= $device_no.'/';
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


//订单
$order_count = OrderSearch::getCustomerOrderCount($model->id);

//评价数量
$comment_count = CustomerComment::getCustomerCommentCount($model->id);
//$comment_count = 0;
//积分
$score_arr_info = Customer::getScoreById($model->id);
if($score_arr_info['errcode'] == 0){
	$score = $score_arr_info['score'];
}

//余额
$balance_arr_info = Customer::getBalanceById($model->id);
if($balance_arr_info['errcode'] == 0){
	$balance = $balance_arr_info['balance'];
}
//历史状态集
$customerBlockLog = CustomerBlockLog::listBlockLog($model->id);
//当前状态
$currentBlockStatus = CustomerBlockLog::getCurrentBlockStatus($model->id);

//workers
$worker_names = '';
$customer_workers_res = Customer::getWorkersById($model->id);
if(empty($customer_workers_res['customer_workers'])){
	$worker_names = '-';
}
foreach ($customer_workers_res['customer_workers'] as $key => $worker)
{
	$worker_names .= $worker['worker_name'].'/';
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
            'attribute'=>'', 
            'label'=>'手机号',
            'format'=>'raw',
            'value'=>$model->customer_phone,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'城市',
            'format'=>'raw',
            'value'=>$operation_city_name,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
		[
            'attribute'=>'', 
            'label'=>'创建时间',
            'format'=>'raw',
            'value'=>date('Y-m-d H:i', $model->created_at),
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
		[
            'attribute'=>'', 
            'label'=>'注册来源',
            'format'=>'raw',
            'value'=>$channal_name_str,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
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
            'label'=>'设备',
            'format'=>'raw',
            'value'=>$device_name_str,
            'type'=>DetailView::INPUT_SWITCH,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute' => '',
			'label'=>'身份',
            'format'=>'raw',
			'value'=>$model->customer_is_vip ? '会员' : '非会员',
            'type' => DetailView::INPUT_WIDGET,
			'valueColOptions'=>['style'=>'width:90%']
            //'widgetOptions' => [
            //    'name'=>'customer_is_vip',
            //    'class'=>\kartik\widgets\Select2::className(),
            //    'data' => array('1'=>'会员', '0'=>'非会员'),
            //    'hideSearch' => true,
            //    'options'=>[
            //        'placeholder' => '选择客户身份',
            //    ]
            //],
            
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
            'value'=>Html::a($order_count, ['order/order/index', 'OrderSearch[customer_id]'=>$model->id]),
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
		[
            'attribute'=>'', 
            'label'=>'常用阿姨',
            'format'=>'raw',
            'value'=> $worker_names,
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
        
    ],
    'enableEditMode'=>false,
]); 

//listCustomerCoupon($phone);
//$couponCustomerProvider = new ActiveDataProvider([
//	'query' => \core\models\operation\coupon\CouponCustomer::find()->where(['customer_id'=>$model->id])
//]);
//if((int)($couponCustomerProvider->query->count()) > 0){
//	echo GridView::widget([
//		'dataProvider' => $couponCustomerProvider,
//
//		'columns'=>[
//		    [
//		        'format' => 'raw',
//		        'label' => '类别',
//		        'value' => function($couponCustomerProvider){
//					$coupon = Coupon::find($couponCustomerProvider->coupon_id);
//					return $coupon->coupon_type_name;
//				},
//		        'width' => "80px",
//		    ],
//		    [
//		        'format' => 'raw',
//		        'label' => '金额',
//		        'value' => function($couponCustomerProvider){
//					return $couponCustomerProvider->coupon_price;
//				},
//		        'width' => "80px",
//		    ],
//		    [
//		        'format' => 'raw',
//		        'label' => '到期日',
//		        'value' => function($couponCustomerProvider){
//					return date('Y-m-d H:i:s', $couponCustomerProvider->expirate_at);
//				},
//		        'width' => "80px",
//		    ],
//		],
//	]);
//}

$customerBlockLogProvider = new ActiveDataProvider(['query' => \core\models\customer\CustomerBlockLog::find()->where(['customer_id'=>$model->id])->orderBy('created_at desc')]);
if((int)($customerBlockLogProvider->query->count()) > 0){
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
}

?>
</div>


