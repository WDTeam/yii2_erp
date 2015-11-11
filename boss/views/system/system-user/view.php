<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use core\models\system\SystemUser;

/**
 * @var yii\web\View $this
 * @var boss\models\SystemUser $model
 */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-view">
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
            [
                'attribute'=>'id',
                'type'=>DetailView::INPUT_HIDDEN
            ],
            'username',
//             'auth_key',
//             'password_hash',
//             'password_reset_token',
            'email:email',
            'mobile',
            [
                'attribute'=>'password',
                'value'=>'******',
                'type'=>DetailView::INPUT_PASSWORD
            ],
//             [
//                 'attribute'=>'classify',
//                 'value'=>$model->getClassifyLabel(),
//                 'type'=>DetailView::INPUT_RADIO_LIST,
//                 'items'=>SystemUser::getClassifes(),
//             ],
            [
                'attribute'=>'roles',
                'value'=>implode(',', $model->getRolesLabel()),
                'type'=>DetailView::INPUT_RADIO_LIST,
                'items'=>SystemUser::getArrayRole(),
            ],
            [
                'attribute'=>'status',
                'value'=>$model->getStatusLabel(),
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'audit_status',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => SystemUser::getArrayStatus(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择状态',
                    ]
                ],
            ],
            [
                'attribute'=>'created_at',
                'type'=>DetailView::INPUT_HIDDEN,
                'format'=>'datetime'
            ],
            [
                'attribute'=>'updated_at',
                'type'=>DetailView::INPUT_HIDDEN,
                'format'=>'datetime'
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
