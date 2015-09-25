<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Shop $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shop-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
