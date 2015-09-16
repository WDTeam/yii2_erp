<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '学习';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="study-index">
    <?php if($type==0){?>
    <div class="study-options"><?= Html::a('岗前学习', ['study/index', 'type'=>1],['class'=>'btn btn-large btn-block btn-primary'])?></div>
    <div class="study-options"><?= Html::a('新技能学习', ['category/index'],['class'=>'btn btn-large btn-block btn-primary'])?></div>
    <?php }elseif($type==1){?>
    <h1>岗前学习</h1>
    <div>岗前学习是您在面试前先对公司有一定的了解，主要学习的内容有：公司介绍、公司文化、公司制度、服务规范，并有简单的测试。祝您学习愉快！</div>
    <div><?php echo Html::a('开始学习',['study/pre-service',
        'order_number'=>0
    ])?></div>
    <?php }elseif($type==2){?>
    <h1>新技能学习</h1>
    <div>新技能学习,功能未开放，欢迎下期光临……</div>
    <div><?php Html::a('开始学习',['study/new-skills',
        'order_number'=>0
    ])?></div>
    <?php }?>
</div>
