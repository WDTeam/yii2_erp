<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use core\models\finance\FinanceSettleApplySearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\finance\FinanceSettleApplySearch $searchModel
 */

$this->title = Yii::t('finance', '门店结算查询');
$this->params['breadcrumbs'][] = $this->title;
$this->params['review_section']=$searchModel->review_section;
?>
<form id ="financeSettleApplyForm">
   
<div class="finance-settle-apply-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 门店结算搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_query_search', ['model' => $searchModel]); ?>
        </div>
    </div>
     <div class="panel panel-info">

        <?php 
            Pjax::begin(); echo GridView::widget([
            'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'shop_name',
                'shop_manager_name',
                'finance_shop_settle_apply_order_count',
                'finance_shop_settle_apply_fee_per_order', 
                'finance_shop_settle_apply_fee', 
                ['attribute'=>'finance_shop_settle_apply_status',
                    'content'=> function($model,$key,$index){return $model->getShopSettleApplyStatusDes($model->finance_shop_settle_apply_status);} ],   
                ['attribute'=>'comment','content'=>function($model,$key,$index){return $model->comment == null?'':$model->comment;}],
                ['attribute'=>'created_at','content'=>function($model,$key,$index){return Html::a(date('Y-m-d H:i:s',$model->created_at),'#');}],
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['/finance/finance-shop-settle-apply/view', 'id' => $model->id], ['target'=>'_blank']), [
                            'title' => Yii::t('yii', '查看'),'data-pjax'=>'0','target' => '_blank',
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
            'after'=>false,
            'showFooter' => false
        ],

        ]); Pjax::end(); ?>
     </div>
</div>
</form>
