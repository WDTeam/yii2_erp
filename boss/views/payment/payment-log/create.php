<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\PaymentLog $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'General Pay Log',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'General Pay Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-log-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
