<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Question */

$this->title = '编辑试题: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => [
    'index',
    'courseware_id'=>$model->courseware_id
]];
$this->params['breadcrumbs'][] = ['label' => '编辑试题:'.$model->title, 'url' => ['view', 'id' => $model->id]];
?>
<div class="question-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
