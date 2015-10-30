<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardInfo $model
 */

$this->title = Yii::t('app', '创建服务卡', [
    'modelClass' => 'Operation Service Card Info',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-info-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
		'config' => $config,
    ]) ?>

</div>
