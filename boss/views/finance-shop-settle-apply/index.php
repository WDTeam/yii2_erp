<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\FinanceWorkerNonOrderIncomeSearch;
use boss\models\FinanceSettleApplySearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceSettleApplySearch $searchModel
 */

$this->title = Yii::t('finance', '门店结算');
$this->params['breadcrumbs'][] = $this->title;
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

        <?php Pjax::begin(); echo GridView::widget([
            'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'shop_name',
                'shop_manager_name',
                'finance_shop_settle_apply_order_count',
                'finance_shop_settle_apply_fee_per_order', 
                'finance_shop_settle_apply_fee', 
                'created_at', 
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['/finance-settle-apply/self-parttime-worker-settle-view', 'id' => $model->id, 'finance_settle_apply_status' => FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED],['target'=>'_blank']), [
                            'title' => Yii::t('yii', '查看'),
                        ]);
                    },
                    'agree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', Yii::$app->urlManager->createUrl(['/finance-settle-apply/self-parttime-worker-settle-done', 'id' => $model->id, 'finance_settle_apply_status' => FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED]), [
                            'title' => Yii::t('yii', '审核通过'),
                        ]);
                    },
                    'disagree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>',
                            [
                                '/finance-settle-apply/self-fulltime-worker-settle-done',
                                'id' => $model->id, 
                                'finance_settle_apply_status' => FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED
                            ]
                            ,
                            [
                                'title' => Yii::t('yii', '审核不通过'),
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
</form>
