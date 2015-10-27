<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardRecord $model
 */

$this->title = Yii::t('app', '新增', [
    'modelClass' => 'Operation Server Card Record',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡付款管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-server-card-record-create">
  
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
