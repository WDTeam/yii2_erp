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

<div class="container">
    <div class="row">
        <div class="col-md-4 form-inline">
            <label>智能派单服务器地址：</label>
            <input id="serverip" class="form-control" name="serverip" value="<?php echo !empty($srvInfo['ip'])? $srvInfo['ip'] : ''?>" placeholder="SOCKET服务器地址" />
        </div>
        <div class="col-md-4 form-inline">
            <label>智能派单服务器端口：</label>
            <input id="serverport" class="form-control" name="serverport" value="<?php echo !empty($srvInfo['port'])? $srvInfo['port'] : ''?>" placeholder="SOCKET服务器端口" />
        </div>
        <div class="col-md-2"><button class="btn btn-default" id="connect" name="connect">连接派单服务器</button><button class="btn btn-default" id="runService" name="runService">启动派单服务</button></div>
        <div class="col-md-2"><button class="btn btn-default" id="start" name="start">开始自动派单</button><button class="btn btn-default" id="stop" name="stop">停止自动派单</button></div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-6  form-inline">
            <label>全职阿姨派单时间：</label>
            <input id="qstart" class="form-control margin-l-36" name="qstart" value="0" readonly="true" /> 至 <input id="qend" class="form-control" name="qend" value="<?php echo !empty($config['FULLTIME_WORKER_TIMEOUT'])? $config['FULLTIME_WORKER_TIMEOUT'] : ''?>" />分钟
        </div>
        <div class="col-md-2">
            <button class="btn btn-default" id="update" name="update">更新配置（即时生效）</button>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-6  form-inline">
            <label>兼职阿姨派单时间：</label>
            <input id="jstart" class="form-control margin-l-36" name="jstart" value="5" readonly="true" /> 至 <input id="jend" class="form-control" name="jend" value="<?php echo !empty($config['FREETIME_WORKER_TIMEOUT'])? $config['FREETIME_WORKER_TIMEOUT'] : ''?>" />分钟
        </div>
        <div class="col-md-2">
            <button class="btn btn-default" id="reload" name="reload">重新启动</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  form-inline">
        <label>执行结果：</label><div id="connectStatus"></div>
        </div>
    </div>
    <hr/>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>订单编号</th>
                <th>状态</th>
                <th>短信状态</th>
                <th>ivr状态</th>
                <th>App推送</th>
                <th>创建时间</th>
                <th>处理时间</th>
            </tr>
        </thead>
        <tbody id="tbody">
            <tr>
                <td>222</td>
                <td>进行中</td>
                <td>没有</td>
                <td>正在进行中</td>
                <td>没推送</td>
                <td>2015-10-11</td>
                <td>2015-10-12</td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript" src="/autoassign/js/jquery.js"></script>
<script type="text/javascript" src="/autoassign/js/index.js"></script>