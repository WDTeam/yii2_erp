<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCard $model
 */

$this->title = Yii::t('app', '新增服务卡', [
    'modelClass' => 'Server Card',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡信息管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-server-card-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
		'deploy' => $deploy,
    ]) ?>

</div>
