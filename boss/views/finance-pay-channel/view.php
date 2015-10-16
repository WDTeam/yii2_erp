<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinancePayChannel $model
 */

$this->title = $model->finance_pay_channel_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '支付渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pay-channel-view">

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
           // 'id',
            'finance_pay_channel_name',
            'finance_pay_channel_rank',
    		[
    		'format' => 'raw',
    		'label' => '状态',
    		'attribute'=>'finance_pay_channel_is_lock',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '开启', '2' => '关闭'],
    		],
           // 'create_time:datetime',
          //  'is_del',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', '你确定删除吗?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
