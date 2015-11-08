<?php
use boss\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use boss\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
	<?php if(Yii::$app->getSession()->hasFlash('default')){
		$msg = Yii::$app->getSession()->getFlash('default');
		echo 'alert("'.$msg.'");';
	}?>
    </script>
</head>
<body class="skin-blue fixed">
    <?php $this->beginBody() ?>
    <header class="header">
        <?php echo Html::a('', Yii::$app->homeUrl, [
            'class'=>'logo',
            'style'=>\Yii::$app->user->identity->isMiniBoxUser()?"background-image: url('/adminlte/img/logo_partner.png');
                background-size:inherit":'',
        ]);?>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top .fixed" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?= Yii::t('app', 'Toggle navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <?= $this->render('//layouts/top-menu.php') ?>
            <?php
            /*$menuItemsMain = [
                ['label' => 'Catalog', 'url' => ['/catalog']],
                ['label' => 'Show', 'url' => ['/show']],
            ];
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => $menuItemsMain,
                'encodeLabels' => false,
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);*/
            ?>

        </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="/adminlte/img/avatar2.png" class="img-circle" >
                    </div>
                    <div class="pull-left info">
                        <p>
                            <?= Yii::t('app', 'Hello, {name}', ['name' => Yii::$app->user->identity->username]) ?>
                        </p>
                        <a>
                            <i class="fa fa-circle text-success"></i>
                            <?php echo implode(',', Yii::$app->user->identity->getRolesLabel());?>
                        </a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?= $this->render('//layouts/sidebar-menu')?>
                <div style="height: 100px;"  ></div>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?= $this->title ?>
                    <?php if (isset($this->params['subtitle'])) : ?>
                        <small><?= $this->params['subtitle'] ?></small>
                    <?php endif; ?>
                </h1>
                <?= Breadcrumbs::widget(
                    [
                        'homeLink' => [
                            'label' => '<i class="fa fa-dashboard"></i> ' . Yii::t('app', 'Home'),
                            'url' => ['/']
                        ],
                        'encodeLabels' => false,
                        'tag' => 'ol',
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                    ]
                ) ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?= Alert::widget() ?>
                <?= $content ?>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div>
    <?php $this->endBody() ?>
<!-- <footer style="height: 100px;">
    开发者：E家洁BOSS攻坚组
</footer> -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
    Copyright © 2015 e家洁.
    All rights reserved. <?php echo Html::a('<span>发布记录</span>', Yii::$app->urlManager->createUrl(['/system/release-notes'],[]), [
                            'title' => Yii::t('yii', '查看发布历史'),'data-pjax'=>'0','target' => '_blank',
                        ]) ?>
    </div>
</footer>
</body>
</html>
<?php $this->endPage() ?>
