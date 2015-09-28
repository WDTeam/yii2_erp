<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertPosition */

//$this->title = Yii::t('app', 'Create').Yii::t('app', 'Advert Position');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Position'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-position-create">

    <?= $this->render('_form', [
        'model' => $model,
        'citys' => $citys,
        'platforms' => $platforms,
    ]) ?>

</div>
