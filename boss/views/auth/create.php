<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model core\models\auth\Auth */

$this->title = Yii::t('app', 'Create Auth');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auths'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
