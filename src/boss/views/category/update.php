<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = Yii::t('app','Update Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cateid, 'url' => ['view', 'id' => $model->cateid]];
//$this->params['breadcrumbs'][] = Yii::t('app','Update Categories');
?>
<div class="category-update">

    <!--<h1><?php // Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
