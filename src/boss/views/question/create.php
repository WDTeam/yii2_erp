<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Question */

$this->title = '创建试题';
$this->params['breadcrumbs'][] = ['label' => '试题管理', 'url'=> ['index','courseware_id'=>$model->courseware_id]];
//$this->params['breadcrumbs'][] = ['label' => '试题管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?> 

</div>
