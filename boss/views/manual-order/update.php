<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var core\models\Order\Order $model
 */

$this->title = Yii::t('order', 'Update {modelClass}: ', [
    'modelClass' => 'Order',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('order', 'Update');
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
