<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerTransRecordLog $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Customer Trans Record Log',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Trans Record Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-trans-record-log-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
