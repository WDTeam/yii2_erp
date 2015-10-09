<?php
use yii\helpers\Html;

$this->title = '考试通过';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<?php if($isExam){?>
	<h1>恭喜您已经通过在线考试</h1>
	<p>请等待实操考试</p>
	<p>祝您面试成功，早日加入！</p>
	<?php }else{?>
	<h1>恭喜您考核通过</h1>
	<p>稍后会短信通知您面试消息</p>
	<p>前往面试时请准备好：智能手机、健康证、身份证、银行卡、电子照片</p>
	<p>期待与您的合作！</p>
	<?php }?>
	<div>
		<?php echo Html::a('返回首页', ['site/index'], ['class'=>'btn btn-primary']);?>
	</div>
</div>