<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '结算查询');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i>结算查询</h3>
        </div>

        <div class="panel-body">
            <?php

            echo $this->render('_search', ['model' => $searchModel]);
            ?>

        </div>
        <div class="panel-heading">
            <h3 class="panel-title">结算列表</h3>
        </div>
        <div>
            
            <?php Pjax::begin(); echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'worder_tel',
                'worker_type_name',
                'finance_settle_apply_cycle_des',
                'finance_settle_apply_money', 
                'finance_settle_apply_man_hour', 
                'finance_settle_apply_order_money', 
                'finance_settle_apply_order_cash_money', 
                'finance_settle_apply_order_money_except_cash',
                ['attribute'=>'finance_settle_apply_subsidy',
                 'content'=>function($model,$key,$index){return '<a class="btn btn-default"  id = "subsidyButton" data-container="body" data-toggle="popover" data-placement="bottom" data-popover-content="'.$model->id.'">'.$model->finance_settle_apply_subsidy.'</a>';}],
                 ['attribute'=>'finance_settle_apply_status',
                    'content'=> function($model,$key,$index){return $model->getSettleApplyStatusDes($model->finance_settle_apply_status);} ],     
//                'finance_settle_apply_reviewer', 
                ['attribute'=>'updated_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->updated_at),'#');}],
                [
                    'class' => 'yii\grid\ActionColumn',
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'floatHeader'=>false,
                        
             'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before' =>$this->render('_query_links', ['model' => $searchModel]),
            'after'=>false,
            'showFooter'=>false
        ],
        ]); Pjax::end(); ?>
        </div>
    </div>
    <p>
    </p>
