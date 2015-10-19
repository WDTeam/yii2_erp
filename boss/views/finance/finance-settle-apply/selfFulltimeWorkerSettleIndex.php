<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\FinanceSettleApplySearch;
use boss\models\FinanceWorkerNonOrderIncomeSearch;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceSettleApplySearch $searchModel
 */
if($searchModel->settle_type == FinanceSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE){
    $this->title = Yii::t('finance', '自营全职结算');
}
if($searchModel->settle_type == FinanceSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE){
    $this->title = Yii::t('finance', '自营兼职结算');
}
if($searchModel->settle_type == FinanceSettleApplySearch::SHOP_WORKER_SETTELE){
    $this->title = Yii::t('finance', '门店阿姨结算');
}
if($searchModel->settle_type == FinanceSettleApplySearch::ALL_WORKER_SETTELE){
    $this->title = Yii::t('finance', '阿姨结算');
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['settle_type'] = $searchModel->settle_type;
$this->params['review_section'] = $searchModel->review_section;
?>
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
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
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> <?php echo $this->title?>搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_self_fulltime_search', ['model' => $searchModel]); ?>
        </div>
    </div>
     <div class="panel panel-info">

        <?php Pjax::begin(); 
        $columns = [
                ['class' => 'yii\grid\SerialColumn'],
                'worder_tel',
                'worder_name',
                ['attribute'=>'created_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->created_at),'#');}],
                'finance_settle_apply_cycle_des',
                'finance_settle_apply_money', 
                'finance_settle_apply_man_hour', 
                'finance_settle_apply_order_money', 
                'finance_settle_apply_order_cash_money', 
                'finance_settle_apply_order_money_except_cash',
                ['attribute'=>'finance_settle_apply_subsidy',
                 'content'=>function($model,$key,$index){return '<a class="btn btn-default"  id = "subsidyButton" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.FinanceWorkerNonOrderIncomeSearch::getSubsidyDetail($model->id).'">'.$model->finance_settle_apply_subsidy.'</a>';}],
                ['attribute'=>'finance_settle_apply_status',
                    'content'=> function($model,$key,$index){return $model->getSettleApplyStatusDes($model->finance_settle_apply_status);} ],   
                'comment',
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['/finance/finance-settle-apply/self-fulltime-worker-settle-view', 'FinanceSettleApplySearch[id]' => $model->id],[]), [
                            'title' => Yii::t('yii', '查看'),'data-pjax'=>'0','target' => '_blank',
                        ]);
                    },
                    'agree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', Yii::$app->urlManager->createUrl(['/finance/finance-settle-apply/self-fulltime-worker-settle-done', 'id' => $model->id, 'settle_type'=>$this->params['settle_type'],'is_ok'=>1, 'review_section'=>$this->params['review_section']]), [
                            'title' => Yii::t('yii', '审核通过'),
                            'class'=>'agree',
                        ]);
                    },
                    'disagree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>',
                            [
                                '/finance/finance-settle-apply/review-failed-reason',
                                'id' => $model->id, 
                                'settle_type'=>$this->params['settle_type'],'is_ok'=>0,'review_section'=>$this->params['review_section'],
                            ]
                            ,
                            [
                                'title' => Yii::t('yii', '审核不通过'),
                                'data-toggle' => 'modal',
                                'data-target' => '#reasonModal',
                                'class'=>'disagree',
                                'data-id'=>$model->id,
                            ]);
                    },
                ],
            ],
            ];
        if($searchModel->finance_settle_apply_status != FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED){
            foreach($columns as $key => $value){
                if($value == 'comment'){
                    unset($columns[$key]);
                }
            }
            
        }
        
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
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

        ]); Pjax::end(); 
        
        ?>
         <?php 
         
            $js=<<<JS
                    $(".agree").click(
                        function(){
                            if(confirm("审核通过该笔结算是吗?")){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    );
                    $('.disagree').click(function() {
                        $('#reasonModal .modal-body').html('加载中……');
                        $('#reasonModal .modal-body').eq(0).load(this.href);
                    });
JS;
        $this->registerJs(
                $js
        );
         ?>
     </div>
</div>
<script>
$(function () {
    $('[data-toggle="popover"]').popover();
});
</script>
</form>
<?php echo Modal::widget([
            'header' => '<h4 class="modal-title">请输入审核不通过原因</h4>',
            'id'=>'reasonModal',
            'options'=>[
                'size'=>'modal-sm',
            ],
        ]);
?>