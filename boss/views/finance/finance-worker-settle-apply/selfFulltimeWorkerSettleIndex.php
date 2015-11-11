<style>
    .modal-content {
        height: 300px !important;
    }
</style>
<?php

use kartik\grid\GridView;

use core\models\finance\FinanceWorkerSettleApplySearch;
use core\models\finance\FinanceShopSettleApplySearch;

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\finance\FinanceWorkerSettleApplySearch $searchModel
 */
if($searchModel->settle_type == FinanceWorkerSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE){
    $this->title = Yii::t('finance', '自营全职结算');
}
if($searchModel->settle_type == FinanceWorkerSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE){
    $this->title = Yii::t('finance', '自营兼职结算');
}
if($searchModel->settle_type == FinanceWorkerSettleApplySearch::SHOP_WORKER_SETTELE){
    $this->title = Yii::t('finance', '门店阿姨结算');
}
if($searchModel->settle_type == FinanceWorkerSettleApplySearch::ALL_WORKER_SETTELE){
    $this->title = Yii::t('finance', '阿姨结算');
}
$this->params['breadcrumbs'][] = $this->title;
$this->params['settle_type'] = $searchModel->settle_type;
$this->params['review_section'] = $searchModel->review_section;
//是否需要进行财务打款确认
$isFinacePayedConfirm = ($searchModel->finance_worker_settle_apply_status == FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED);
$this->params['isFinacePayedConfirm'] = $isFinacePayedConfirm;
?>
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
                [ 'header' => Yii::t('app', '结算编号'),'attribute'=>'finance_worker_settle_apply_code'],
                [ 'header' => Yii::t('app', '阿姨电话'),'attribute'=>'worker_tel'],
                [ 'header' => Yii::t('app', '阿姨姓名'),'attribute'=>'worker_name'],
                [ 'header' => Yii::t('app', '阿姨类型'),'content'=>function($model,$key,$index){return $model->getWorkerTypeDes($model->worker_type_id,$model->worker_identity_id);}],
                [ 'header' => Yii::t('app', '结算类型'),'attribute'=>'finance_worker_settle_apply_cycle_des'],
                ['header' => Yii::t('app', '申请时间'),'attribute'=>'created_at','content'=>function($model,$key,$index){return date('Y-m-d H:i:s',$model->created_at);}],
                [ 'header' => Yii::t('app', '结算周期'),'attribute'=>'finance_worker_settle_apply_starttime','content'=>function($model,$key,$index){return date('Y-m-d',$model->finance_worker_settle_apply_starttime).'至<br>'.date('Y-m-d',$model->finance_worker_settle_apply_endtime);}],
                [ 'header' => Yii::t('app', '订单总工时'),'attribute'=>'finance_worker_settle_apply_man_hour'], 
                [ 'header' => Yii::t('app', '工时费小计'),'attribute'=>'finance_worker_settle_apply_order_money'], 
                [ 'header' => Yii::t('app', '底薪补贴'),'attribute'=>'finance_worker_settle_apply_base_salary_subsidy'], 
                [ 'header' => Yii::t('app', '完成任务数'),'attribute'=>'finance_worker_settle_apply_task_count'], 
                [ 'header' => Yii::t('app', '完成任务奖励'),'attribute'=>'finance_worker_settle_apply_task_money'], 
                [ 'header' => Yii::t('app', '应结合计'),'attribute'=>'finance_worker_settle_apply_money_except_deduct_cash'], 
                [ 'header' => Yii::t('app', '扣款小计'),'attribute'=>'finance_worker_settle_apply_money_deduction'], 
                [ 'header' => Yii::t('app', '本次应结合计'),'attribute'=>'finance_worker_settle_apply_money_except_cash'], 
                [ 'header' => Yii::t('app', '现金订单'),'attribute'=>'finance_worker_settle_apply_order_cash_count'], 
                [ 'header' => Yii::t('app', '已收现金小计'),'attribute'=>'finance_worker_settle_apply_order_cash_money'], 
                [ 'header' => Yii::t('app', '本次应付合计'),'attribute'=>'finance_worker_settle_apply_money'], 
                ['attribute'=>'comment','hidden'=>$searchModel->finance_worker_settle_apply_status != FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED],
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['/finance/finance-worker-settle-apply/self-fulltime-worker-settle-view', 'FinanceWorkerSettleApplySearch[id]' => $model->id],[]), [
                            'title' => Yii::t('yii', '查看'),'data-pjax'=>'0','target' => '_blank',
                        ]);
                    },
                    'agree' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">审核通过</span>', Yii::$app->urlManager->createUrl(['/finance/finance-worker-settle-apply/self-fulltime-worker-settle-done', 'id' => $model->id, 'settle_type'=>$this->params['settle_type'],'is_ok'=>1,'isFinacePayedConfirm'=>$this->params['isFinacePayedConfirm'], 'review_section'=>$this->params['review_section']]), [
                            'title' => Yii::t('yii', $this->params['isFinacePayedConfirm']?'确认打款':'审核通过'),
                            'class'=>'agree',
                        ]);
                    },
                    'disagree' => function ($url, $model) {
                        return 
                        $this->params['review_section'] == FinanceShopSettleApplySearch::BUSINESS_REVIEW? '':
                        Html::a('<span class="btn btn-primary" style = "display:'.($this->params['isFinacePayedConfirm']?'none':'').'">审核不通过</span>',
                            [
                                '/finance/finance-worker-settle-apply/review-failed-reason',
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
<?php echo Modal::widget([
            'header' => '<h4 class="modal-title">请输入审核不通过原因</h4>',
            'id'=>'reasonModal',
            'options'=>[
                'size'=>'modal-sm',
            ],
        ]);
?>