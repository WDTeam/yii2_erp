<?php


use kartik\grid\GridView;

use core\models\finance\FinanceWorkerSettleApplySearch;
use core\models\finance\FinanceShopSettleApplySearch;

use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\finance\FinanceWorkerSettleApplySearch $searchModel
 */

$this->title = Yii::t('finance', '门店结算');
$this->params['breadcrumbs'][] = $this->title;
$this->params['review_section']=$searchModel->review_section;
//是否需要进行财务打款确认
$isFinacePayedConfirm = ($searchModel->finance_shop_settle_apply_status == FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED);
$this->params['isFinacePayedConfirm'] = $isFinacePayedConfirm;
?>
<form id ="financeSettleApplyForm">
   
<div class="finance-settle-apply-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 门店结算搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
     <div class="panel panel-info">

        <?php 
            Pjax::begin();
            $columns = [
                ['class' => 'yii\grid\SerialColumn'],
                'shop_name',
                'shop_manager_name',
                'finance_shop_settle_apply_order_count',
                'finance_shop_settle_apply_fee_per_order', 
                'finance_shop_settle_apply_fee',
                ['attribute'=>'created_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->created_at),'#');}],
                ['attribute'=>'comment','hidden'=>$searchModel->finance_shop_settle_apply_status != FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED],
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['/finance/finance-shop-settle-apply/view', 'id' => $model->id], ['target'=>'_blank']), [
                            'title' => Yii::t('yii', '查看'),'data-pjax'=>'0','target' => '_blank',
                        ]);
                    },
                    'agree' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">审核通过</span>', Yii::$app->urlManager->createUrl(['/finance/finance-shop-settle-apply/review', 'id' => $model->id,'review_section'=>$this->params['review_section'],'isFinacePayedConfirm'=>$this->params['isFinacePayedConfirm'],'is_ok'=>1]), [
                            'title' => Yii::t('yii', $this->params['isFinacePayedConfirm']?'确认打款':'审核通过'),
                            'class'=>'agree',
                        ]);
                    },
                    'disagree' => function ($url, $model,$review_section) {
                        return 
                        $this->params['review_section'] == FinanceShopSettleApplySearch::BUSINESS_REVIEW? '':
                        Html::a('<span class="btn btn-primary" style = "display:'.($this->params['isFinacePayedConfirm']?'none':'').'">审核不通过</span>',
                            [
                                '/finance/finance-shop-settle-apply/review-failed-reason',
                                'id' => $model->id, 'review_section'=>$this->params['review_section'],'is_ok'=>0,
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
            'columns' =>$columns ,
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
</form>
<?php echo Modal::widget([
            'header' => '<h4 class="modal-title">请输入审核不通过原因</h4>',
            'id'=>'reasonModal',
            'options'=>[
                'size'=>'modal-sm',
            ],
        ]);
?>
