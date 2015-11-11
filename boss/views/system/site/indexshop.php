<?php
/* @var $this yii\web\View */


use boss\assets\AppAsset;
AppAsset::addCss($this, 'css/shop/bootstrap.min.css');
AppAsset::addCss($this, 'css/shop/index.css');
$this->title = '欢迎进入MIIN BOSS业务运营支撑系统';

$shops = Yii::$app->user->identity->getShopList();
// $shop = $shops[0];
?>
 <div class="container">
        <?php if(isset($shop)){?>
        <div class="row header"><?php echo $shop->name;?></div>
        <div class="row bar">
            <div class="order-num border-margin">
                <span>200</span><br/><span>已接单</span>
            </div>
            <div class="order-num">
                <span>200</span><br/><span>已接单</span>
            </div>
            <div class="order-num">
                <span>200</span><br/><span>已接单</span>
            </div>
            <div class="order-num">
                <span>200</span><br/><span>已接单</span>
            </div>
            <div class="order-num">
                <span>200</span><br/><span>已接单</span>
            </div>
        </div>
        <?php }?>
        <div class="row content">
            <div class="col-md-5 content-margin">
                <div class="row content-bar">
                    <div class="col-md-5">基本资料</div>
                </div>
                <div class="row bor-der min-heigh">
                    <div class="col-md-12">
                        <div class="row text-left-padding">
                            <div></div>
                            <span>负责人：</span><span><?php echo \Yii::$app->user->identity->username;?></span>
                        </div>
                        <div class="row text-left-padding">
                            <span>联系电话：</span><span><?php echo \Yii::$app->user->identity->mobile;?></span>
                        </div>
                        <div class="row text-left-padding">
                            <span>办公地址：</span><span>光华路soho</span>
                        </div>
                        <div class="row text-left-padding">
                            <span>门店数量：</span><span>10000</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-7 content-bar">系统通知</div>
                </div>
                <div class="row bor-der min-heigh">
                    <div class="col-md-12">
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                        <div class="row text-right-padding">
                            <div class="col-md-8"><span>标签</span></div>
                            <div class="col-md-4"><span>2015-10-13 &nbsp; 10:11</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
