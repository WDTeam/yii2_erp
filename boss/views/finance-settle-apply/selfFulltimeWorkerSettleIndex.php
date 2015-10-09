<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\FinanceWorkerNonOrderIncomeSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceSettleApplySearch $searchModel
 */

$this->title = Yii::t('finance', '全职结算');
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.css" rel="stylesheet">
<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.js"></script>
<script src="//cdn.bootcss.com/angular.js/1.4.6/angular.js"></script>
<script src="//cdn.bootcss.com/angular-strap/2.3.3/modules/popover.js"></script>
<script src="//cdn.bootcss.com/angular-strap/2.3.3/modules/tooltip.js"></script>
<style type="text/css">
    .popover {
        height:50px;
        max-width:2000px;
    }
</style>
<form id ="financeSettleApplyForm">
   
<div class="finance-settle-apply-index">
     <div class="panel panel-info">
        <div class = "container">
            <input type="hidden" id="finance_settle_apply_status" name="FinanceSettleApplySearch[finance_settle_apply_status]"  />
            <input type="hidden" id="ids" name="FinanceSettleApplySearch[ids]"/>
            <input type="hidden" id="nodeId"   name="FinanceSettleApplySearch[nodeId]" value = "<?php echo $nodeId; ?>"/>
        </div>

        <script>
            function checkResult(checkStatus){
                //勾选的结算记录id
                var ids = $('#w1').yiiGridView('getSelectedRows');
                if(ids === ''){
                    return;
                }
                if(checkStatus === 1){
                    $("#finance_settle_apply_status").val($("#nodeId").val());
                }else{
                    $("#finance_settle_apply_status").val(-$("#nodeId").val());
                }
                $("#ids").val(ids);
                var url = '/finance-settle-apply/review';
                $('#financeSettleApplyForm').attr('action',url);
                $('#financeSettleApplyForm').submit();
            }
            function changetTab(applyStatus,nodeId){
                $("#nodeId").val(nodeId);
                $("#finance_settle_apply_status").val(applyStatus);
                var url = '/finance-settle-apply/index';
                $('#financeSettleApplyForm').attr('action',url);
                $('#financeSettleApplyForm').submit();
            }
        </script>

        <?php Pjax::begin(); echo GridView::widget([
            'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'worder_tel',
                'worker_type_name',
                ['attribute'=>'created_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->created_at),'#');}],
                'finance_settle_apply_cycle_des',
                'finance_settle_apply_money', 
                'finance_settle_apply_man_hour', 
                'finance_settle_apply_order_money', 
                'finance_settle_apply_order_cash_money', 
                'finance_settle_apply_order_money_except_cash',
                ['attribute'=>'finance_settle_apply_subsidy',
                 'content'=>function($model,$key,$index){return '<a class="btn btn-default"  id = "subsidyButton" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.FinanceWorkerNonOrderIncomeSearch::getSubsidyDetail($model->id).'">'.$model->finance_settle_apply_subsidy.'</a>';}],
                'finance_settle_apply_reviewer', 
                ['attribute'=>'updated_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->updated_at),'#');}],
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'agree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', Yii::$app->urlManager->createUrl(['worker/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', '审核通过'),
                        ]);
                    },
                    'disagree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>',
                            [
                                '/worker/vacation-create',
                                'id' => $model->id
                            ]
                            ,
                            [
                                'title' => Yii::t('yii', '请假信息录入'),
                                'data-toggle' => 'modal',
                                'data-target' => '#vacationModal',
                                'class'=>'vacation',
                                'data-id'=>$model->id,
                            ]);
                    },
                ],
            ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'floatHeader'=>false,
           'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before' =>false,
            'after'=>false,
            'showFooter' => false
        ],

        ]); Pjax::end(); ?>
     </div>
</div>
<script>
$(function () {
    $('[data-toggle="popover"]').popover();
});
</script>
</form>
