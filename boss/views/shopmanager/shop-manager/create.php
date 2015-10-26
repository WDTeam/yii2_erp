<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManager $model
 */

$this->title = Yii::t('app', '添加小家政公司');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
