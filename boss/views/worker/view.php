<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */

$this->title = $model->worker_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-view">
    <div class="page-header">
        <!--<h1><?= Html::encode($this->title) ?></h1>-->
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
            'shop_id',
            'worker_name',
            'worker_phone',
            'worker_idcard',
            //'worker_password',
            'worker_photo',
            'worker_level',
            'worker_auth_status',
            'worker_ontrial_status',
            'worker_onboard_status',
            'worker_work_city',
            'worker_work_area',
            'worker_work_street',
            'worker_type',
            'worker_rule_id',
            'worker_is_block',
            'worker_is_blacklist',
            'created_ad',
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
