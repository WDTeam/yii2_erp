<?php
/*
 * BOSS 自动派单运行服务实例
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
    hr { margin-top: 0;}
    .col-md-4 {width: 27%;}
    .col-md-6 {width: 54%;}
    .margin-l-30 {margin-left: 30px;}
    .margin-l-36 {margin-left: 33px;}
</style>
<!-- end -->
<input id="srvIsSuspend" name="srvIsSuspend" value="<?php echo $srvIsSuspend ? 'true':'false' ?>" hidden />
<div class="container">
    <div class="row">
        <div class="col-md-4 form-inline">
            <label>服务器地址：</label>
            <input id="serverip" class="form-control" name="serverip" value="<?php echo !empty($srvInfo['ip'])? $srvInfo['ip'] : ''?>" placeholder="SOCKET服务器地址" />
        </div>
        <div class="col-md-3 form-inline">
            <label>服务器端口：</label>
            <input id="serverport" class="form-control" style="width:30%;" name="serverport" value="<?php echo !empty($srvInfo['port'])? $srvInfo['port'] : ''?>" placeholder="SOCKET服务器端口" />
        </div>
        <div class="col-md-3">
            <button class="btn btn-default" id="connect" name="connect">连接</button>
            <button class="btn btn-default" id="start" name="start">停止自动派单</button>
        </div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col-md-8  form-inline">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;当前状态：&nbsp;&nbsp;</label><div style="display:inline;" id="connectStatus"></div>
        </div>
    </div>
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