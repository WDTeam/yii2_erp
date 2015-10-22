<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\CustomerCommentTag $model
 */

$this->title = Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Customer Comment Tag',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Customer Comment Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-tag-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
