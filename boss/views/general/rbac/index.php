<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '管理授权项';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'name','label'=>'名称'],
            ['attribute'=>'type','label'=>'类型','content'=>function($model,$key,$index){return $model->type==1?"角色":"权限";}],
            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}','header'=>'操作'],
        ],
        'layout' => "{items}\n{pager}\n{summary}",
    ]); ?>
</div>