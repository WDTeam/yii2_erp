<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Advert Release');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-update">

    <?= $this->render('_form', [
        'model' => $model,
        'positions' => $positions,
        'contents' => $contents,
    ]) ?>

</div>
