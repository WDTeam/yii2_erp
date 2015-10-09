
<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = $category->catename;
$this->params['breadcrumbs'][] = Yii::t('app', 'New skills learning');//Html::a(Yii::t('app', 'New skills learning'), ['index']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1>暂无可用视频资料</h1>
    <p>你可以进行<?=Html::a('其他技能考试',['index'],'')?>或返回<?=Html::a('首页',['site/index'],'')?></p>
</div>