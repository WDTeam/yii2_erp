<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Shop $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Shop',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
