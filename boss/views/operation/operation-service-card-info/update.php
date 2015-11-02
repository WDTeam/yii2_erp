<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardInfo $model
 */

$this->title = Yii::t('app', '修改服务卡信息: ', [
    'modelClass' => 'Operation Service Card Info',
]) . ' ' . $model->service_card_info_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->service_card_info_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="operation-service-card-info-update">

    <?= $this->render('_form', [
        'model' => $model,
		'config' => $config,
    ]) ?>

</div>
