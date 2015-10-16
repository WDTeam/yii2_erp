<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceHeader $model
 */

$this->title = Yii::t('boss', ' {modelClass}', [
    'modelClass' => '添加表头',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '配置对账表头'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-header-create">
    <?= $this->render('_form', [
        'model' => $model,'htmlordeinfo' => $ordeinfo,'htmlpayinfo' =>$payinfo,
    ]) ?>

</div>
