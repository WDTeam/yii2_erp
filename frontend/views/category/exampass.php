<?php
use yii\helpers\Html;

$this->title = $errorNum>0 ? '考试未通过':'考试通过';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<?php if($errorNum > 0){?>
	<h1>对不起，您没有通过“<?=$courseware->name?>”考核！</h1>
	<p>你可以进行<?=Html::a('重新考试',['service', 'id'=>$category->cateid],'')?>或返回<?=Html::a('首页',['site/index'],'')?></p>
	<?php }elseif($errorNum == 'completed'){?>
        <h1>恭喜您顺利通过“<?=$category->catename?>”的所有考核</h1>
	<p>你可以进行<?=Html::a('其他技能考试',['index'],'')?>或返回<?=Html::a('首页',['site/index'],'')?></p>
        <?php }else{?>
	<h1>恭喜您顺利通过了“<?=$courseware->name?>”的考核</h1>
	<p>你可以进行<?=Html::a('下一个课件',['service', 'id' => $courseware->classify_id, 'order_number' => $courseware->order_number],'')?>的学习</p>
	<?php }?>
</div>