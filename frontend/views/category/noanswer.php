<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = isset($title)?$title:$courseware->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1>该课件暂未安排试题，请联系工作人员</h1>
    <p>你可以进行<?=Html::a('其他技能考试',['category/index'],'')?>或返回<?=Html::a('首页',['site/index'],'')?></p>
</div>