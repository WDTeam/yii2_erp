<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'e家洁阿姨培训系统';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>e家洁</h1>

        <p class="lead">最值得信赖的家政服务平台</p>

        <p>
          <!-- <a class="btn btn-lg btn-success" href=" <?php Yii::$app->urlManager->createUrl('user/signup') ?> ">在线报名</a> --> 
             <?= Html::a('现在报名', ['site/signup'],['class'=>'btn btn-lg btn-success'])?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>关于e家洁</h2>

                <p>e家洁APP是一款基于地理位置的找小时工应用，主要提供清洁房屋服务。是中国第一家专业提供APP预约保洁服务的互联网应用。e家洁隶属于北京新车云信息技术有限公司，是一家移动互联网创业公司，小伙伴们来自各大互联网公司。因为有梦想让我们在一起奋斗；因为有激情，让我们更加努力拼搏，我们正走在创业的路上。
区别于传统家政公司，创新出互联网架构下基于生活家政服务的全新商业模式。用户可通过e家洁APP与服务人员直接联系。
e家洁在路上，希望一路有您相伴！
我们一直在努力！</p>

                <!-- <p><a class="btn btn-default" href="http://www.1jiajie.com/aboutus.html">更多 &raquo;</a></p> -->
            </div>
            <div class="col-lg-4">
                <h2>合作介绍</h2>

                <p>e家洁拥有规模最大的服务人员团队，通过招募、筛选、培训及建立服务档案，保证服务质量。致力于提供专业、低价、高效、便捷、安全的家政服务。</p>

                <!-- <p><a class="btn btn-default" href="http://www.1jiajie.com/aboutus.html">更多 &raquo;</a></p> -->
            </div>
            <div class="col-lg-4">
                <h2>报名流程</h2>

                <p>阅读报名须知>> 在线报名 >> 岗前学习 >> 现场面试 >> 现场实操 >> 签约 >> 开通接单服务</p>

               <!--  <p><a class="btn btn-default" href="http://www.1jiajie.com/aboutus.html">更多 &raquo;</a></p> -->
            </div>
        </div>

    </div>
</div>
