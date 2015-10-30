<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinancePayChannel $model
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
    		'buttons1'=>'{update}',
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
        'deleteOptions'=>false,
 
        'enableEditMode'=>true,
    ]) ?>

</div>
