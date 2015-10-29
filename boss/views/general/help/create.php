<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Help $model
 */

$this->title = Yii::t('boss', '创建 {modelClass}', [
    'modelClass' => '帮助',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Helps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
