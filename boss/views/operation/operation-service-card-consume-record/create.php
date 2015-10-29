<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardConsumeRecord $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Service Card Consume Record',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Service Card Consume Records'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-consume-record-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
