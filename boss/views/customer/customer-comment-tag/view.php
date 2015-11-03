<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerCommentTag $model
 */

$this->title = $model->customer_tag_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '标签管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-tag-view">
    
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
            //'id',
            'customer_tag_name',
    		[
    		'format' => 'raw',
    		'label' => '分类',
    		'attribute'=>'customer_tag_type',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '评价', '2' => '退款','3' => '其他'],
    		],
    		[
    		'format' => 'raw',
    		'label' => '评价等级',
    		'attribute'=>'customer_comment_level',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '满意', '2' => '一般','3' => '不满意'],
    		],
			[
			'format' => 'raw',
			'label' => '开启状态',
			'attribute'=>'is_online',
			'type'=> DetailView::INPUT_RADIO_LIST,
			'items'=>['1' => '开启', '2' => '关闭'],
			],
    		
    		
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
