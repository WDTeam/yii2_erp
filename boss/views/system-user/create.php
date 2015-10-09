<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\SystemUser $model
 */

$this->title = Yii::t('app', 'Create System User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
