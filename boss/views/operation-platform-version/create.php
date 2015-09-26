<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatformVersion */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Platform').Yii::t('app', 'Version');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform'), 'url' => ['operation-platform/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform').Yii::t('app' ,'Version'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-version-create">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'platform_id' => $platform_id
    ]) ?>

</div>
