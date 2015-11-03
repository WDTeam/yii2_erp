<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use boss\models\finance\FinancePopOrderSearch;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '对账管理');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-pop-order-index hideTemp">
      <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i> 上传对账单</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel,'ordedat' => $ordedatainfo,'paydat'=>$paydat]); ?>
    </div>
    </div>
</div>
    <?php
   
    ActiveForm::begin([
    'action' => ['indexall'],
    'method' => 'post'
    		]);
 
     echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
     		'class' => 'yii\grid\CheckboxColumn',
     		'name'=>'ids'
			],
     		'order_code',
              		[
     		'format' => 'raw',
     		'label' => '第三方订单号',
     		'value' => function ($dataProvider) {
     			return $dataProvider->finance_pop_order_number;
     		},
     		'width' => "80px",
     		],	
     		[
     		'format' => 'raw',
     		'label' => '渠道名称',
     		'value' => function ($dataProvider) {
     			return $dataProvider->finance_order_channel_title ? $dataProvider->finance_order_channel_title  : '未知';
     		},
     		'width' => "100px",
     		],
            [
            'format' => 'raw',
            'label' => '阿姨姓名',
            'value' => function ($dataProvider) {
            	return  FinancePopOrderSearch::Workerinfo($dataProvider->finance_pop_order_worker_uid,'worker_name');
            },
            'width' => "100px",
            ],
            [
            'format' => 'raw',
            'label' => '预约开始时间',
            'value' => function ($dataProvider) {
            	return FinancePopOrderSearch::alltime($dataProvider->finance_pop_order_booked_time);
            },
            'width' => "100px",
            ],
            [
            'format' => 'raw',
            'label' => '预约服务时长',
            'value' => function ($dataProvider) {
            	return FinancePopOrderSearch::alltimecount($dataProvider->finance_pop_order_booked_counttime);
            },
            'width' => "100px",
            ],
            'order_money',//系统金额
            'finance_pop_order_sum_money', // 对账金额
            [
            'format' => 'raw',
            'label' =>FinancePopOrderSearch::get_stypname($channleid),
            'value' => function ($dataProvider) {
            	return $dataProvider->finance_pop_order_discount_pay;
            },
            'width' => "70px",
            ],
           'finance_pop_order_reality_pay', //实际收款
            [
            'format' => 'raw',
            'label' => '结算状态',
            'value' => function ($dataProvider) {
            if($dataProvider->finance_pop_order_status==1){ $status='结算';}elseif($dataProvider->finance_pop_order_status==2){ $status='<font color="red">退款</font>';}elseif($dataProvider->finance_pop_order_status==3){ $status='<font color="red">服务费</font>';}elseif($dataProvider->finance_pop_order_status==4){ $status='<font color="red">转账</font>';}elseif($dataProvider->finance_pop_order_status==5){ $status='<font color="red">交易失败</font>';}
            	return $status;
            },
            'width' => "70px",
            ],
            [
            'format' => 'raw',
            'label' => '对账状态',
            'value' => function ($dataProvider) {
            	$platform = FinancePopOrderSearch::is_orderstatus($dataProvider->finance_pop_order_pay_status_type);
            	return $platform;
            },
            'width' => "70px",
            ],
            [
            'format' => 'raw',
            'label' => '订单状态',
            'value' => function ($dataProvider) {
            	return $dataProvider->order_status_name;
            },
            'width' => "70px",
            ],
            [
            'format' => 'raw',
            'label' => '收款状态',
            'value' => function ($dataProvider) {
            	return $dataProvider->finance_pop_order_discount_pay==1?'未确定':'已确定';
            },
            'width' => "70px",
            ],
            'finance_pop_order_finance_time:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {taginfo} {tagssign}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="btn btn-primary">编辑</span>', Yii::$app->urlManager->createUrl(['finance/finance-pop-order/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);},
                                                  
                                                  'taginfo' => function ($url, $model) {
                                                  	return Html::a('<span class="fa fa-fw fa-linkedin-square"></span>',
                                                  			[
                                                  			'finance/finance-pop-order/forminfo',
                                                  			'id' => $model->id,
                                                  			'edit'=>'baksiteinfo',
                                                  			'oid'=>$model->finance_record_log_id
                                                  			]
                                                  			,
                                                  			[
                                                  			'title' => Yii::t('yii', '请输入标记原因'),
                                                  			'data-toggle' => 'modal',
                                                  			'data-target' => '#tagModal',
                                                  			'class'=>'vacationinfo',
                                                  			'data-id'=>$model->id,
                                                  			'style' => 'margin-right:3px'
                                                  			]);
                                                  },  

                                                  'tagssign' => function ($url, $model) {
                                                  	return Html::a('<span class="fa fa-fw fa-history"></span>',
                                                  			[
                                                  			'finance/finance-pop-order/forminfo',
                                                  			'id' => $model->id,
                                                  			'edit'=>'baksite',
                                                  			'oid'=>$model->finance_record_log_id
                                                  			]
                                                  			,
                                                  			[
                                                  			'title' => Yii::t('yii', '请输入坏账原因'),
                                                  			'data-toggle' => 'modal',
                                                  			'data-target' => '#vacationModal',
                                                  			'class'=>'vacation',
                                                  			'data-id'=>$model->id,
                                                  			'style' => 'margin-right:3px'
                                                  			]);
                                                  },
                                                  
                                                  
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type'=>'info',
           'before'=>
           Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px']).
           Html::a('<i class="glyphicon" ></i>对账成功(总额:'.$searchModel->OrderPayStatus(1,$lastidRecordLogid,$channleid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=1&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(1,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>公司有渠道无 (总额:'.$searchModel->OrderPayStatus(3,$lastidRecordLogid,$channleid).')', ['orderlist?FinancePopOrderSearch[finance_pop_order_pay_status_type]=3&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(3,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>渠道有公司无 (总额:'.$searchModel->OrderPayStatus('2',$lastidRecordLogid,$channleid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=2&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(2,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>金额不对 (总额:'.$searchModel->OrderPayStatus(4,$lastidRecordLogid,$channleid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=4&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(4,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>状态不对(总额:'.$searchModel->OrderPayStatus(5,$lastidRecordLogid,$channleid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=5&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(5,$statusdeflde).'', 'style' => 'margin-right:10px']),
            'showFooter'=>false,
        ],
    ]);
       ActiveForm::end();
       echo Modal::widget([
       		'header' => '<h4 class="modal-title">坏账原因</h4>',
       		'id'=>'vacationModal',
       		]);

       echo Modal::widget([
       		'header' => '<h4 class="modal-title">标记原因</h4>',
       		'id'=>'tagModal',
       		]);
$this->registerJs(<<<JSCONTENT
		$('.vacationinfo').click(function() {
            $('#tagModal .modal-body').html('加载中……');
            $('#tagModal .modal-body').eq(0).load(this.href);
        });
        $('.vacation').click(function() {
            $('#vacationModal .modal-body').html('加载中……');
            $('#vacationModal .modal-body').eq(0).load(this.href);
        });
        $('.block').click(function() {
            $('#blockModal .modal-body').html('加载中……');
            $('#blockModal .modal-body').eq(0).load(this.href);
        });
        $('.batchVacation').click(function() {
            $('#vacationModal .modal-body').html('加载中……');
            url = this.href;
            vacationType = $(this).attr('type');
            selectWorkerIds = '';
            $('.danger').each(function(index,ele){
                 workerId = $(this).attr('data-key');
                 selectWorkerIds += workerId+','
            })
            $('#vacationModal .modal-body').eq(0).load(url);
        });
JSCONTENT
);
?>
