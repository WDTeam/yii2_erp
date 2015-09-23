<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceSettleApplySearch $searchModel
 */

$this->title = Yii::t('finance', 'Finance Settle Applies');
$this->params['breadcrumbs'][] = $this->title;
?>
<form id ="financeSettleApplyForm">
<div class="finance-settle-apply-index">
    <div class="page-header">
            <button type="button" class="btn btn-default btn-lg active">门店财务管理</button>
            <button type="button" class="btn btn-default btn-lg active">线下运营审核</button>
            <button type="button" class="btn btn-default btn-lg active">财务审核</button>
            <button type="button" class="btn btn-default btn-lg active">财务确认结算</button>
            <button type="button" class="btn btn-default btn-lg active">结算统计</button>
            <button type="button" class="btn btn-default btn-lg active">申请结算</button>
    </div>
    <br/>
    <p></p>
    <div class = "container">
        <button type="button" onclick="checkResult(1)" class="btn btn-default">审核通过</button>
        <button type="button" onclick="checkResult(0)" class="btn btn-default">审核不通过</button>
    </div>
        <input type="hidden" id="finance_settle_apply_status" name="FinanceSettleApplySearch[finance_settle_apply_status]"/>
        <input type="hidden" id="ids" name="FinanceSettleApplySearch[ids]"/>
    <script>
        function checkResult(checkStatus){
            //勾选的结算记录id
            var ids = $('#w1').yiiGridView('getSelectedRows');
            if(ids == ''){
                alert("请勾选结算记录");
                return;
            }
            $("#finance_settle_apply_status").val(checkStatus);
            $("#ids").val(ids);
//            var url = '/finance-settle-apply/review?finance_settle_apply_status='+checkStatus+'&ids='+ids;
var url = '/finance-settle-apply/review';
            $('#financeSettleApplyForm').attr('action',url);
            $('#financeSettleApplyForm').submit();
        }
    </script>
    <p>
    </p>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
//            'id',
            'worder_id',
            'worder_tel',
//            'worker_type_id',
            'worker_type_name',
            'created_at', 
            'finance_settle_apply_cycle',
            'finance_settle_apply_money', 
            'finance_settle_apply_man_hour', 
            'finance_settle_apply_order_money', 
            'finance_settle_apply_order_cash_money', 
            'finance_settle_apply_non_order_money', 
//            'finance_settle_apply_status', 
            'finance_settle_apply_reviewer', 
//            'isdel', 
            'updated_at', 
            
            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        
    ]); Pjax::end(); ?>

</div>
</form>
