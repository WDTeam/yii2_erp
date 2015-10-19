<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use boss\models\FinanceWorkerOrderIncomeSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨结算');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">

    <div id = "manualSettleInfo">
        <div class="panel-heading">
                <h3 class="panel-title">阿姨信息</h3>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                阿姨姓名
            </div>
            <div class='col-md-2'>
                手机号
            </div>
            <div class='col-md-2'>
                入职日期
            </div>
            <div class='col-md-2'>
                阿姨类型
            </div>
            <div class='col-md-2'>
                结算周期
            </div>
            <div class='col-md-2'>
                上次结算时间
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                <?=  $model->worder_name; ?>
            </div>
            <div class='col-md-2'>
                <?=  $model->worder_tel; ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y:m:d H:i:s',$model->workerOnboardTime); ?>
            </div>
            <div class='col-md-2'>
                <?=  $model->workerTypeDes; ?>
            </div>
            <div class='col-md-2'>
                <?=  $model->finance_settle_apply_cycle_des; ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y:m:d H:i:s',$model->latestSettleTime); ?>
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">结算明细</label>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='settleDetail'>
                完成总单量
            </div>
            <div class='settleDetail'>
                现金订单
            </div>
            <div class='settleDetail'>
                收取现金
            </div>
            <div class='settleDetail'>
                非现金订单
            </div>
            <div class='settleDetail'>
                工时费应结
            </div>
            <div class='settleDetail'>
                完成任务
            </div>
            <div class='settleDetail'>
                任务奖励
            </div>
            <div class='settleDetail'>
                小保养订单
            </div>
            <div class='settleDetail'>
                小保养
            </div>
            <div class='settleDetail'>
                应结
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='settleDetail'>
                
                <?php
                    if($model->finance_settle_apply_order_count > 0){
                        echo Html::a('<u>'.$model->finance_settle_apply_order_count.'</u>',[Yii::$app->urlManager->createUrl(['/finance/finance-settle-apply/worker-manual-settlement-index','id' => $model->id,'settle_type'=>$model->settle_type,'review_section'=>$model->review_section])]);
                    }else{
                        echo $model->finance_settle_apply_order_count;
                    }
                ?>
            </div>
            <div class='settleDetail'>
                 <?php
                    if($model->finance_settle_apply_order_cash_count > 0){
                        echo Html::a('<u>'.$model->finance_settle_apply_order_cash_count.'</u>',[Yii::$app->urlManager->createUrl(['/finance/finance-settle-apply/worker-manual-settlement-index','id' => $model->id,'finance_worker_order_income_type'=>FinanceWorkerOrderIncomeSearch::CASH_INCOME,'settle_type'=>$model->settle_type,'review_section'=>$model->review_section])]);
                    }else{
                        echo $model->finance_settle_apply_order_cash_count;
                    }
                ?>
            </div>
            <div class='settleDetail'>
                <?php echo $model->finance_settle_apply_order_cash_money ?>
            </div>
            <div class='settleDetail'>
                <?php
                    if($model->finance_settle_apply_order_noncash_count > 0){
                        echo Html::a('<u>'.$model->finance_settle_apply_order_noncash_count.'</u>',[Yii::$app->urlManager->createUrl(['/finance/finance-settle-apply/worker-manual-settlement-index','id' => $model->id,'finance_worker_order_income_type'=>FinanceWorkerOrderIncomeSearch::ONLINE_INCOME,'settle_type'=>$model->settle_type,'review_section'=>$model->review_section])]);
                    }else{
                        echo $model->finance_settle_apply_order_noncash_count;
                    }
                ?>
            </div>
            <div class='settleDetail'>
                <?php echo $model->finance_settle_apply_order_money_except_cash ?>
            </div>
            <div class='settleDetail'>
                0
            </div>
            <div class='settleDetail'>
                0
            </div>
            <div class='settleDetail'>
                0
            </div>
            <div class='settleDetail'>
                0
            </div>
            <div class='settleDetail'>
                0
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">订单明细</label>
        </div>
        <div>
            
<?php 
                    Pjax::begin(); echo GridView::widget([
               'dataProvider' => $orderDataProvider,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   ['attribute'=>'id',
                       'header' => Yii::t('app', '订单号'),
                        'content'=>function($model,$key,$index)
                               {return  Html::a('<u>'.$model->id.'</u>',[Yii::$app->urlManager->createUrl(['order/view/','id' => $model->id])],['data-pjax'=>'0','target' => '_blank',]);}],
                   [
                       'header' => Yii::t('app', '支付方式'),
                        'attribute' => 'order_pay_type',
                       'content'=>function($model,$key,$index){
                                   return $model->order_pay_type==null?'':$model->order_pay_type;
                       },
                    ],
                   'order_money',
                   'order_booked_begin_time:datetime', 
                   'order_booked_count', 
               ],
               'responsive'=>true,
               'hover'=>true,
               'condensed'=>true,
               'floatHeader'=>true,
           ]); Pjax::end(); 
            ?>
        </div>
        </div>
    </div>
</div>
         <?php 
         
            $js=<<<JS
                    $(document).ready(
                        function(){
                            var worder_tel = $('#worder_tel').val();
                            if(worder_tel == ''){
                                $('#manualSettleInfo').html('<h4  class="col-sm-12">请输入查询条件进行人工结算</h4>');
                            }
                        }
                    );
JS;
        $this->registerJs(
                $js
        );
         ?>

