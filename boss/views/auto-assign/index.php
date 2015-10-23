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
<div class="container">
    <div class="row">
        <div class="col-md-7 form-group">
            <input id="serverip" class="form-control" name="serverip" value="<?php echo !empty($data['ip'])? $data['ip'] : ''?>" placeholder="SOCKET服务器地址" />
        </div>
        <div class="col-md-3 form-group">
            <input id="serverport" class="form-control" name="serverport" value="<?php echo !empty($data['port'])? $data['port'] : ''?>" placeholder="SOCKET服务器端口" />
        </div>
        <div class="col-md-2"><button class="btn btn-default form-control" id="connect" name="connect">连接派单服务器</button></div>
    </div>
    <hr>
    <div class="row form-inline">
        <div class="col-md-6  form-group"><label>全职阿姨派单：</label><input id="qstart" class="form-control" name="qstart" value="0" /> 至 <input id="qend" class="form-control" name="qend" value="5" />分钟</div>
        <div class="col-md-6  form-group"><label>兼职阿姨派单：</label><input id="jstart" class="form-control" name="qstart" value="5" /> 至 <input id="jend" class="form-control" name="qend" value="10" />分钟</div>
    </div>
    <hr>
    <div class="row form-inline">
        <div class="col-md-4 form-group"><label>频率：</label><input id="interval" class="form-control" name="interval" value="6" /><label>秒</label></div>
        <div class="col-md-4 form-group"><label>任务：</label>
            <select id="taskName" class="form-control" name="taskName">
                <option value="">选择任务</option>
                <option value="orders">派单任务</option>
            </select>
        </div>
        <div class="col-md-4"><label>线程数：</label><input id="theadNum" class="form-control" name="theadNum" /></div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4 btn-group">
            <button class="btn btn-default" id="start" name="start">开始自动派单</button>
            <button class="btn btn-default" id="stop" name="start">停止自动派单</button>
        </div>
        <div id="connectStatus" class="col-md-4"></div>
        <div id="connectStatus" class="col-md-4">
            <?php
//            if(!empty($data['time'])){
//                if(time() - $data['time'] > 300){
//                    echo '服务已停止，请先检查服务！';
//                }else{
//                    echo '服务正在运行';
//                }
//            }
            ?>
        </div>
    </div>
    <hr>
    <table class="table table-hover">
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
<!--                    <tr>
                <td>1</td>
                <td>1000</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>-->
        </tbody>
    </table>
</div>

<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript" src="/static/js/index.js"></script>