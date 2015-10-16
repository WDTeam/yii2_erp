<?php
/**
* 对账记录列表
* @date: 2015-10-11
* @author: peak pan
* @return:
**/

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\FinancePopOrderSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceRecordLogSearch $searchModel
 */

$this->title = Yii::t('boss', '历史对账记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-record-log-index hideTemp">
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 对账查询</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel,'odrinfo'=>$odrinfo]); ?>
    </div>
    </div>

    <?php Pjax::begin();
     echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
           // 'finance_order_channel_id',
            'finance_order_channel_name',
          //  'finance_pay_channel_id',
     		[
     		'format' => 'raw',
     		'label' => '账期',
     		'value' => function ($dataProvider) {
     			$zhq=FinancePopOrderSearch::alltime($dataProvider->finance_record_log_statime,2).'到'.FinancePopOrderSearch::alltime($dataProvider->finance_record_log_endtime,2);
     			return $zhq;
     		},
     		'width' => "100px",
     		],
           // 'finance_pay_channel_name',
           'finance_record_log_succeed_count', 
    		[
        		'format' => 'raw',
        		'label' => '成功总金额',
        		'value' => function ($dataProvider) {
        			return FinancePopOrderSearch::sum_money($dataProvider->finance_record_log_succeed_sum_money);
        		},
        		'width' => "100px",
    		],
    		
    		
    		
            'finance_record_log_manual_count', //人工确认笔数
            'finance_record_log_manual_sum_money',
            [
            'format' => 'raw',
            'label' => '未处理笔数',
            'value' => function ($dataProvider) {
            	return FinancePopOrderSearch::countnub($dataProvider->id);
            },
            'width' => "100px",
            ],
            [
            'format' => 'raw',
            'label' => '未处理金额',
            'value' => function ($dataProvider) {
            	return FinancePopOrderSearch::summoney($dataProvider->id);
            },
            'width' => "100px",
            ],
           'finance_record_log_failure_count', 
           'finance_record_log_failure_money',
           'finance_record_log_confirm_name', 
           'finance_record_log_fee',
           
           
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}  {vacation} {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-pop-order/billinfo','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);},
                    'vacation' => function ($url, $model) {
                        return Html::a('<span class="fa fa-fw fa-history"></span>',
                        		
                        		Yii::$app->urlManager->createUrl(['finance-pop-order/index','id' => $model->id]), [
                        		'title' => Yii::t('yii', '对账处理'),
                        		]);}
                ],
            ],  	
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            /* 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 增加', ['create'], ['class' => 'btn btn-success']),  */


            'showFooter'=>false
        ],





    ]); Pjax::end(); ?>

</div>
