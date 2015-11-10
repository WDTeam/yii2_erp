<?php

use kartik\detail\DetailView;

$this->title = $model->finance_header_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '表头管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-header-view">
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
            'finance_header_name',
            'finance_order_channel_name',
            'finance_pay_channel_name',
    		[
    		'attribute' => 'finance_header_where',
    		'type' => DetailView::INPUT_WIDGET,
    		'widgetOptions' => [
    		'name'=>'比对字段名称',
    		'class'=>\kartik\widgets\Select2::className(),
    		'data' => [0=>'请选择','order_channel_order_num'=> '订单号', 'order_money'=>'支付金额','order_channel_promote'=>'渠道营销费','refund'=>'退款金额','decrease'=>'手续费','function_way'=>'状态分类'],
    		'hideSearch' => true,
    				],
    				],
        ],		
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', '你确定你要删除这个项目吗?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
