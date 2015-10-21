<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 */

$this->title = Yii::t('finance', 'Finance Compensate Create', [
    'modelClass' => 'Finance Compensate Create',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Compensates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
</div>
