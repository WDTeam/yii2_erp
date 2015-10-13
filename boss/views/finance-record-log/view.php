<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceRecordLog $model
 */

$this->title = $model->finance_order_channel_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '账期详情'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-record-log-view">
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
            'finance_order_channel_name',
            'finance_pay_channel_id',
            'finance_pay_channel_name',
            'finance_record_log_succeed_count',
            'finance_record_log_succeed_sum_money',
            'finance_record_log_manual_count',
            'finance_record_log_manual_sum_money',
            'finance_record_log_failure_count',
            'finance_record_log_failure_money',
            'finance_record_log_confirm_name',
    		'finance_record_log_statime:datetime',
    		'finance_record_log_endtime:datetime',
            'finance_record_log_fee',
            'create_time:datetime'
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', '确定需要删除吗?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
