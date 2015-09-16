<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);//调用模板
echo '<pre>';
 //var_dump($this);小子，你吓我一跳，还以为配置文件又出问题了呢？以后请用 log 日志 deleted by zhanghang
 echo '</pre>';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php 
    if(\Yii::$app->session->hasFlash('default')){
        $msg = \Yii::$app->session->getFlash('default');
        echo "<script>alert('{$msg}')</script>";
    }
    ?>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'e家洁阿姨培训系统',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => '首页', 'url' => ['/site/index']],
                ['label' => '关于', 'url' => ['/site/about']],
                ['label' => '岗前学习', 'url' => ['/study/index','type'=>1]],
                ['label' => '新技能学习', 'url' => ['/category/index','type'=>1]],
                
                // ['label' => '现场考试', 'url' => ['/study/exam-answer']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => '报名', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => '退出 (' . Yii::$app->user->identity->mobile . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; e家洁(内测版) <?= date('Y') ?></p>
        <p class="pull-right">Powered by <a href="http://www.1jiajie.com/" rel="external">e家洁</a></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
