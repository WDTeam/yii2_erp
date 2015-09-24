<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceHeaderSearch $searchModel
 */

$this->title = Yii::t('boss', '添加账单配置');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-header-index">
<!-- 
<div class="finance-header-search">

    <form id="w0" action="/finance-header/index" method="get">
    <div class="form-group field-financeheadersearch-id">
<label class="control-label" for="financeheadersearch-id">主键</label>
<input type="text" id="financeheadersearch-id" class="form-control" name="FinanceHeaderSearch[id]">

<div class="help-block"></div>
</div>
    <div class="form-group field-financeheadersearch-finance_header_name">
<label class="control-label" for="financeheadersearch-finance_header_name">表头名称</label>
<input type="text" id="financeheadersearch-finance_header_name" class="form-control" name="FinanceHeaderSearch[finance_header_name]">

<div class="help-block"></div>
</div>
    <div class="form-group field-financeheadersearch-finance_order_channel_id">
<label class="control-label" for="financeheadersearch-finance_order_channel_id">订单渠道id</label>
<input type="text" id="financeheadersearch-finance_order_channel_id" class="form-control" name="FinanceHeaderSearch[finance_order_channel_id]">

<div class="help-block"></div>
</div>
    <div class="form-group field-financeheadersearch-finance_order_channel_name">
<label class="control-label" for="financeheadersearch-finance_order_channel_name">订单渠道名称</label>
<input type="text" id="financeheadersearch-finance_order_channel_name" class="form-control" name="FinanceHeaderSearch[finance_order_channel_name]">

<div class="help-block"></div>
</div>
    <div class="form-group field-financeheadersearch-finance_pay_channel_id">
<label class="control-label" for="financeheadersearch-finance_pay_channel_id">支付渠道id</label>
<input type="text" id="financeheadersearch-finance_pay_channel_id" class="form-control" name="FinanceHeaderSearch[finance_pay_channel_id]">

<div class="help-block"></div>
</div>
    
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Search</button>        <button type="reset" class="btn btn-default">Reset</button>    </div>

    </form>
</div>
--> 

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Finance Header',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'finance_header_name',
            'finance_order_channel_id',
            'finance_order_channel_name',
            'finance_pay_channel_id',
//            'finance_pay_channel_name', 
//            'create_time:datetime', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-header/view','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
