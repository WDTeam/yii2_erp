<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceShopSettleApply $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Shop Settle Applies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-shop-settle-apply-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'shop_id',
            'shop_name',
            'shop_manager_id',
            'shop_manager_name',
            'finance_shop_settle_apply_order_count',
            'finance_shop_settle_apply_fee_per_order',
            'finance_shop_settle_apply_fee',
            'finance_shop_settle_apply_status',
            'finance_shop_settle_apply_cycle',
            'finance_shop_settle_apply_cycle_des:ntext',
            'finance_shop_settle_apply_reviewer',
            'finance_shop_settle_apply_starttime:datetime',
            'finance_shop_settle_apply_endtime:datetime',
            'isdel',
            'updated_at',
            'created_at',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
