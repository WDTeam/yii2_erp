<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\GeneralPay $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'General Pay',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'General Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-pay-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
