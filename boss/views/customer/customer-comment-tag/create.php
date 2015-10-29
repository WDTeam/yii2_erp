<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerCommentTag $model
 */

$this->title = Yii::t('boss', ' 添加{modelClass}', [
    'modelClass' => '标签',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '标签管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-tag-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
