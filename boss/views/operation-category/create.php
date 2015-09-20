<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\OperationCategory $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Category',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
