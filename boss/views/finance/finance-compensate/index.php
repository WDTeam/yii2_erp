<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use core\models\finance\FinanceCompensate;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\finance\FinanceCompensate $searchModel
 */

$this->title = Yii::t('finance', 'Finance Compensates Query');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="panel panel-info">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             'finance_compensate_oa_code',
            'finance_complaint_id',
            'order_id',
            'finance_compensate_reason:ntext', 
            'finance_compensate_money', 
            'finance_compensate_coupon_money', 
            'finance_compensate_total_money',
            'finance_compensate_worker_money',
            'finance_compensate_company_money',
            'finance_compensate_insurance_money',
            'worker_name',
            'worker_tel',
            'finance_compensate_proposer', 
            ['attribute'=>'finance_compensate_status',
                    'content'=> function($model,$key,$index){return $model->getFinanceCompensateStatusDes($model->finance_compensate_status);} ],   
            'comment:ntext', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'view' => function ($url, $model) {
                                return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['/finance/finance-compensate/view','id' => $model->id]), [
                                                'title' => Yii::t('yii', 'View'),'data-pjax'=>'0','target' => '_blank',
                                              ]);},
                'update' => function ($url, $model) {
                                    return 
                                                $model->finance_compensate_status == FinanceCompensate::FINANCE_COMPENSATE_REVIEW_PASSED?'':  
                                                  Html::a('<span class="btn btn-primary">更新</span>', Yii::$app->urlManager->createUrl(['/finance/finance-compensate/update','id' => $model->id]), [
                                                    'title' => Yii::t('yii', 'Edit'),
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
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
    </div>
</div>
