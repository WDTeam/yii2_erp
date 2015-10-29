<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceRecordLog $model
 */

$this->title = Yii::t('boss', ' {modelClass}', [
    'modelClass' => '对账统计添加',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Record Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-record-log-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
