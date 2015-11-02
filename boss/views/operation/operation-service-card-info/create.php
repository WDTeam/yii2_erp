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
    <?= $this->render('_form', [
        'model' => $model,
		'config' => $config,
    ]) ?>

</div>
