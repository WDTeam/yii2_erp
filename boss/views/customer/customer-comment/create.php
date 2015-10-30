<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerComment $model
 */

$this->title = Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Customer Comment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Customer Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
