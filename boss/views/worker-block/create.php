<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\WorkerBlock $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Worker Block',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-block-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
