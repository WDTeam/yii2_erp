<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\shop\ShopCustomeRelation $model
 */

$this->title = Yii::t('app', '添加用户关系', [
    'modelClass' => '用户关系',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加用户关系'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-custome-relation-create"> 
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
