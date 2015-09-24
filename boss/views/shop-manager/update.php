<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\ShopManager $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Shop Manager',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shop-manager-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
