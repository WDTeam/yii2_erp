<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardInfo $model
 */

$this->title = $model->service_card_info_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-info-view">
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
        //    'id',
            'service_card_info_name',
//            'service_card_info_card_type',
			[
				'attribute'=>'service_card_info_card_type', 
				'format'=>'raw',
				'value'=>$config['type'][$model->service_card_info_card_type],
				'type'=>DetailView::INPUT_DROPDOWN_LIST,
				'items'=>$config['type'],
				'valueColOptions'=>['style'=>'width:30%']
			],
//            'service_card_info_card_level',
			[
				'attribute'=>'service_card_info_card_level', 
				'format'=>'raw',
				'value'=>$config['level'][$model->service_card_info_card_level],
				'type'=>DetailView::INPUT_DROPDOWN_LIST,
				'items'=>$config['level'],
				'valueColOptions'=>['style'=>'width:30%']
			],
            'service_card_info_par_value',
            'service_card_info_reb_value',
            'service_card_info_use_scope',
            'service_card_info_valid_days',
			[
				'label'=>'创建时间','value'=>date('Y-m-d',$model->created_at)
			],
			[
				'label'=>'更新时间','value'=>date('Y-m-d',$model->updated_at)
			],
//            'created_at:date',
//            'updated_at:date',
//            'is_del',
			
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
