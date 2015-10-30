<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Item');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Item'), 'url' => ['/operation/operation-category']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-goods-create">
    <?= $this->render('_form', [
        'model' => $model,
        'status' => 'create',
//        'priceStrategies' => $priceStrategies,
        'OperationCategory' => $OperationCategory,
        'OperationSpec' => $OperationSpec,
    ]) ?>

</div>
