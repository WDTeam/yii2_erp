<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use boss\models\FinanceShopSettleApplySearch;
use boss\widgets\ShopSelect;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '门店人工结算');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i>门店搜索</h3>
        </div>

        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                 'type' => ActiveForm::TYPE_HORIZONTAL,
                 'method' => 'get',
                 ]);


            ?>
           <div class='col-md-4'>
                <?php 
                echo ShopSelect::widget([
                        'model'=>$model,
                        'shop_manager_id'=>'shop_manager_id',
                        'shop_id'=>'shop_id',
                        ]);
                ?>
            </div>
            <div class='col-md-2' >
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
            <div class='col-md-4'>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="panel-heading">
                <h3 class="panel-title">门店信息</h3>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                公司姓名
            </div>
            <div class='col-md-2'>
                门店
            </div>
            <div class='col-md-2'>
                阿姨数量
            </div>
            <div class='col-md-2'>
                联系人
            </div>
            <div class='col-md-2'>
                联系电话
            </div>
            <div class='col-md-2'>
                上次结算时间
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                <?php echo $model->shop_manager_name?>
            </div>
            <div class='col-md-2'>
                <?php echo $shopModel->name?>
            </div>
            <div class='col-md-2'>
                <?php echo $shopModel->worker_count?>
            </div>
            <div class='col-md-2'>
                <?php echo $shopModel->principal?>
            </div>
            <div class='col-md-2'>
                <?php echo $shopModel->tel?>
            </div>
            <div class='col-md-2'>
                <?php echo  date('Y:m:d H:i:s',$shopModel->created_at)?>
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">门店结算明细</label>
        <?php
            if($model->finance_shop_settle_apply_order_count > 0){
                echo Html::a('结算', ['shop-manual-settlement-done?FinanceShopSettleApplySearch[shop_id]='.$model->shop_id.'&FinanceShopSettleApplySearch[shop_manager_id]='.$model->shop_manager_id.'&FinanceShopSettleApplySearch[finance_shop_settle_apply_order_count]='.$model->finance_shop_settle_apply_order_count.'&FinanceShopSettleApplySearch[finance_shop_settle_apply_fee]='.$model->finance_shop_settle_apply_fee], ['class' => 'btn btn-success ']);
            }
         ?>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-6'>
                完成总单量
            </div>
            <div class='col-md-6'>
                管理费
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-6'>
                <?php echo $model->finance_shop_settle_apply_order_count ?>
            </div>
            <div class='col-md-6'>
                <?php echo $model->finance_shop_settle_apply_fee ?>
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">阿姨结算明细</label>
        </div>
        <div>
            
             <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $financeSettleApplyDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'worder_id',
                 'content'=>function($model,$key,$index)
                        {return  Html::a('<u>'.$model->worder_id.'</u>',[Yii::$app->urlManager->createUrl(['order/view/','id' => $model->worder_id])],['target'=>'_blank']);}],
            'worder_tel',
            'worker_type_name',
            'worker_rule_name', 
            'finance_settle_apply_order_count', 
             [
                'header' => Yii::t('app', '服务费'),
                'attribute' => 'finance_settle_apply_order_count',
                'content'=>function($model,$key,$index){
                            return $model->finance_settle_apply_order_count * FinanceShopSettleApplySearch::MANAGE_FEE_PER_ORDER;
                },
            ],                   
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
    ]); Pjax::end(); ?>
        </div>
    </div>
</div>

