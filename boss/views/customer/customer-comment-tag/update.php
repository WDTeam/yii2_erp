<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerCommentTag $model
 */

$this->title = Yii::t('boss', 'Update {modelClass}: ', [
    'modelClass' => 'Customer Comment Tag',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Customer Comment Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="customer-comment-tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
