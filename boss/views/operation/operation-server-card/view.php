<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCard $model
 */

$this->title = $model->card_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-server-card-view">
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
//            'id',
            'card_name',
			[
				'label' =>'服务卡类型','value'=>$deploy['card_type'][$model->card_type],
			],
            [
				'label' =>'服务卡级别','value'=>$deploy['card_level'][$model->card_level],
			],
            'par_value',
            'reb_value',
            'use_scope',
            'valid_days',
//            'created_at',
//            'updated_at',
			[
				'label' =>'创建时间','value'=>date("Y-m-d",$model->created_at),
			],
			[
				'label' =>'更改时间','value'=>date("Y-m-d",$model->updated_at),
			]
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
