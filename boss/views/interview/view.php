<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SystemUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'password_hash',
            'password_reset_token',
            'email:email',
            'idnumber',
            'age',
            'birthday',
            'mobile',
            'ecn',
            'city',
            'province',
            'district',
            'whatodo',
            'where',
            'when',
            'status',
            'created_at',
            'updated_at',
            'isdel',
            'study_status',
            'study_time:datetime',
            'notice_status',
            'online_exam_time:datetime',
            'online_exam_score',
            'exam_result',
            'operation_time:datetime',
            'operation_score',
            'test_status',
            'test_situation',
            'test_result',
            'sign_status',
        ],
    ]) ?>

</div>
