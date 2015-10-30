<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCity $model
 */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update').'城市';
?>
<div class="operation-city-update">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
