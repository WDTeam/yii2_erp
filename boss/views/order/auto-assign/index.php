<?php
/*
 * BOSS 智能派单运行服务实例
 * @author 张旭刚<zhangxugang@corp.1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
*/
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('order', '智能派单');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', '订单'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 新增样式表begin -->
<style type="text/css">
    .col-md-4 {width: 27%;}
    .col-md-6 {width: 54%;}
    .marginstyle{
        margin:0 auto;
    }
</style>
<!-- end -->
<input id="srvIsSuspend" name="srvIsSuspend" value="<?php echo $srvIsSuspend ? 'true':'false' ?>" hidden />
<div class="container">
    <input id="serverip" class="form-control" name="serverip" type = "hidden" value="<?php echo !empty($config['SWOOLE_SERVER_IP'])? $config['SWOOLE_SERVER_IP']: ''?>"  />
             <input id="serverport" class="form-control" style="width:30%;" name="serverport" type = "hidden" value="<?php echo !empty($config['SERVER_LISTEN_PORT'])? $config['SERVER_LISTEN_PORT'] : ''?>" />
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>派单服务器地址</th>
                <th>Redis服务器地址</th>
                <th>全职阿姨时间段</th>
                <th>兼职阿姨时间段</th>
                <th>当前状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody >
            <tr>
                <td  style="vertical-align:middle;"><?php echo !empty($config['SWOOLE_SERVER_IP'])? $config['SWOOLE_SERVER_IP'].':'.$config['SERVER_LISTEN_PORT'] : ''?></td>
                <td style="vertical-align:middle;"><?php echo !empty($config['REDIS_SERVER_IP'])? $config['REDIS_SERVER_IP'].':'.$config['REDIS_SERVER_PORT '] : ''?></td>
                <td style="vertical-align:middle;">0 - <?php echo !empty($config['FULLTIME_WORKER_TIMEOUT'])? $config['FULLTIME_WORKER_TIMEOUT'] : ''?>分钟</td>
                <td style="vertical-align:middle;"><?php echo !empty($config['FULLTIME_WORKER_TIMEOUT'])? $config['FULLTIME_WORKER_TIMEOUT'] : ''?> - <?php echo !empty($config['FREETIME_WORKER_TIMEOUT'])? $config['FREETIME_WORKER_TIMEOUT'] : ''?>分钟</td>
                <td style="vertical-align:middle;"><div style="display: inline;" id="connectStatus"></div></td>
                <td style="vertical-align:middle;"><button class="btn btn-default" id="connect" name="connect">连接派单服务器</button>
                    <button class="btn btn-default" id="start" name="start">停止智能派单</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="row">&nbsp;</div>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>订单编号</th>
                <th>状态</th>
                <th>ivr状态</th>
                <th>App推送</th>
                <th>创建时间</th>
                <th>处理时间</th>
            </tr>
        </thead>
        <tbody id="tbody">
        </tbody>
    </table>
</div>

<script type="text/javascript" src="/autoassign/js/jquery.js"></script>
<script type="text/javascript" src="/autoassign/js/index.js"></script>