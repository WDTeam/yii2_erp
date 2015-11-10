<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use dbbase\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\base\Widget;
use yii\widgets\ActiveForm;

use dbbase\models\order\OrderExtCustomer;

use core\models\customer\Customer;
use core\models\customer\CustomerAddress;
use core\models\customer\CustomerWorker;
use core\models\customer\CustomerExtBalance;
use core\models\customer\CustomerExtScore;
use core\models\customer\CustomerExtSrc;
use core\models\customer\CustomerComment;
use core\models\order\OrderComplaint;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '客户管理');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 客户搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>
    <p>
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $b= Html::a('<i class="glyphicon" ></i>全部 '.$all_count, ['customer/customer/index'], ['class' => 'btn btn-success-selected', 'style' => 'margin-right:10px']). 
    Html::a('<i class="glyphicon" ></i>封号'.$block_count, ['customer/customer/index?CustomerSearch[is_del]=1'], ['class' => 'btn btn-success-selected', 'style' => 'margin-right:10px']).
    Html::a('<i class="glyphicon" ></i>按时间从大到小 ', ['index', 'CustomerSort'=>['field'=>'created_at', 'order'=>'asc']], ['class' => 'btn btn-success-selected', 'style' => 'margin-right:10px']).
    Html::a('<i class="glyphicon" ></i>按订单量从大到小 ', ['index', 'CustomerSort'=>['field'=>'created_at', 'order'=>'asc']], ['class' => 'btn btn-success-selected', 'style' => 'margin-right:10px']);
   
    ?>
    <?php 
    // ActiveForm::begin([
    // 'action' => ['multi-add-to-block'],
    // 'method' => 'post'
    //         ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'export'=>false,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'toolbar' =>
            [
                'content'=>''
                    // Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px','data-toggle'=>'modal',
                    //     'data-target'=>'#multi-add-to-block-modal',]),
                    // Html::a('<i class="glyphicon">批量加入黑名单</i>', ['customer/multi-add-to-block'], [
                    //     'class' => 'btn btn-default multi-add-to-block',
                    //     'data-toggle'=>'modal',
                    //     'data-target'=>'#multi-add-to-block-modal',
                    // ]),
                    // Html::a('<i class="glyphicon">批量移除黑名单</i>', ['customer/multi-remove-from-block'], [
                    //     'class' => 'btn btn-default multi-remove-from-block',
                    // ]),
//                    Html::a('<i class="glyphicon">data</i>', ['customer/customer/data'], [
//                        'class' => 'btn btn-default',
//                    ]),
            ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // [
            //     'format' => 'raw',
            //     'label' => 'ID',
            //     'value' => function ($dataProvider) {
            //         return $dataProvider->id;
            //     },
            //     'width' => "0px",
            // ],
            [
                'class'=>'kartik\grid\CheckboxColumn',
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],

            // [
            //     'format' => 'raw',
            //     'label' => '姓名',
            //     'value' => function ($dataProvider) {
            //         $name_show = empty($dataProvider->customer_name) ? "未知" : $dataProvider->customer_name;
            //         return '<a href="/customer/' . $dataProvider->id . '">'.$name_show.'</a>';
            //     },
            //     'width' => "80px",
            // ],
            [
                'format' => 'raw',
                'label' => '电话',
                'value' => function ($dataProvider) {
                    // return '<a href="/customer/' . $dataProvider->id . '">'.$dataProvider->customer_phone.'</a>';
                    return Html::a('<i class="glyphicon">'.$dataProvider->customer_phone.'</i>', ['customer/customer/view', 'id'=>$dataProvider->id]);
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '订单地址',
                'value' => function ($dataProvider) {
                    $currentAddress = CustomerAddress::getCurrentAddress($dataProvider->id);
                    if ($currentAddress == false) {
                        return '-';
                    }
                    $addressStr = '';
                    $addressStr = $currentAddress->operation_province_name
                        .$currentAddress->operation_city_name
                        .$currentAddress->operation_area_name
                        .$currentAddress->customer_address_detail
                        .'|'.$currentAddress->customer_address_nickname
                        .'|'.$currentAddress->customer_address_phone;
                    return $addressStr;
                },
                'width' => "300px",
            ],
			[
                'format' => 'raw',
                'label' => '状态',
                'value' => function ($dataProvider) {
                    $currentBlockStatus = \core\models\customer\CustomerBlockLog::getCurrentBlockStatus($dataProvider->id);
                    return $currentBlockStatus['block_status_name'];
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '封号原因',
                'value' => function ($dataProvider) {
                    $currentBlockReason = \core\models\customer\CustomerBlockLog::getCurrentBlockReason($dataProvider->id);
                    return $currentBlockReason == false ? '-' : $currentBlockReason;
                },
                'width' => "80px",
                'visible' => $is_del == 1 ? true : false,
            ],
            [
                'format' => 'raw',
                'label' => '身份',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_is_vip ? '会员' : '非会员';
                },
                'width' => "80px",
            ],
			[
                'format' => 'raw',
                'label' => '注册来源',
                'value' => function ($dataProvider) {
                    $customer_ext_src = Customer::getFirstSrc($dataProvider->customer_phone);
                    $channal_name = empty($customer_ext_src) ? '-' : empty($customer_ext_src['channal_name']) ? '-' : $customer_ext_src['channal_name']; 
					return $channal_name;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '平台',
                'value' => function ($dataProvider) {
                    $customer_ext_src = Customer::getFirstSrc($dataProvider->customer_phone);
                    $platform_name = empty($customer_ext_src) ? '-' : empty($customer_ext_src['platform_name']) ? '-' : $customer_ext_src['platform_name']; 
					return $platform_name;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '所有订单',
                'value' => function ($dataProvider) {
                    $order_count = OrderExtCustomer::find()->where(['customer_id'=>$dataProvider->id])->count();
					return Html::a($order_count, ['order/order/index', 'OrderSearch[customer_id]'=>$dataProvider->id]);
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '账户余额',
                'value' => function ($dataProvider) {
                    $customerBalance = Customer::getBalanceById($dataProvider->id);
                    if($customerBalance['errcode'] != 0){
						return '-';					
					}else{
						return $customerBalance['balance'];
					}
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '投诉',
                'value' => function ($dataProvider) {
					$complaint_count = (new OrderComplaint)->getComplainNumsByPhone($dataProvider->customer_phone);
					$complaint_count = $complaint_count == false ? 0 : $complaint_count;
                    return Html::a('<i class="glyphicon">'.$complaint_count.'</i>', ['order/order-complaint', 'customer_id'=>$dataProvider->id]);
                },
                'width' => "80px",
            ],
			[
                'format' => 'raw',
                'label' => '评论',
                'value' => function ($dataProvider) {
					$comment_count = CustomerComment::getCustomerCommentCount($dataProvider->id);
                    return Html::a('<i class="glyphicon">'.$comment_count.'</i>', ['customer/customer-comment', 'customer_id'=>$dataProvider->id]);
                },
                'width' => "80px",
            ],
			[
                'format' => 'raw',
                'label' => '积分',
                'value' => function ($dataProvider) {
                    $score_arr_info = Customer::getScoreById($dataProvider->id);
					$score = 0;
					if($score_arr_info['response'] == 'success'){
						$score = $score_arr_info['score'];
					}
					return $score;
                },
                'width' => "80px",
            ],
            [
                'format' => 'datetime',
                'label' => '创建时间',
                'value' => function ($dataProvider) {
                    return $dataProvider->created_at;
                    
                },
                'width' => "120px",
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{block}',
                'buttons' => [
                    'block' => function ($url, $model) {
                        return empty($model->is_del) ? Html::a('封号', [
                            'customer/customer/add-to-block',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '封号'),
                            'data-toggle'=>'modal',
                            'data-target'=>'#modal',
                            'data-id'=>$model->id,
                            'class'=>'btn btn-primary block-btn',
                        ]) : Html::a('解除封号', [
                            'customer/customer/remove-from-block',
                            'id' => $model->id
                            
                        ], [
                            'title' => Yii::t('app', '解除封号'),
							'class'=>'btn btn-primary',
                        ]);
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'striped'=>false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>$b,
            // 'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
            //     ['index'],
            //     ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
        
    ]);
    // ActiveForm::end(); 
    ?>
</div>
<?php echo Modal::widget([
'header' => '<h4 class="modal-title">封号原因</h4>',
'id' =>'modal',
]);?>

<?php $this->registerJs(<<<JSCONTENT
$('.block-btn').click(function(){
    $('#modal .modal-body').html('加载中……');
    $('#modal .modal-body').eq(0).load(this.href);
});
JSCONTENT
);?>
</div>





