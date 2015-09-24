<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\ShopManager $model
 */

$this->title = Yii::t('app', 'Create Shop Manager');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
