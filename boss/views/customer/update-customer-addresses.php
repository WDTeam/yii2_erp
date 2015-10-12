<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Customer $model
 */

// $this->title = Yii::t('boss', '更新{modelClass}', [
//     'modelClass' => '客户',
// ]) . ' ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="customer-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    
</div>
