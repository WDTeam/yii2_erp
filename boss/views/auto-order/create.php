<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var core\models\Order\Order $model
 */

$this->title = Yii::t('order', 'Create {modelClass}', [
    'modelClass' => 'Order',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
