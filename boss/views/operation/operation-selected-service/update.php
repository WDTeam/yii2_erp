<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Item') . ' ';
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Selected Service'), 'url' => ['operation/operation-selected-service']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="operation-selected-service-update">
    <?= $this->render('_form', [
        'model' => $model,
        'status' => 'update',
        //'OperationCategory' => $OperationCategory,
//        'priceStrategies' => $priceStrategies,
        //'OperationSpec' => $OperationSpec,
    ]) ?>

</div>
