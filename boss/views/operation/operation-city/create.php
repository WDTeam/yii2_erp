<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCity $model
 */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-city-create">
<!--    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>-->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
