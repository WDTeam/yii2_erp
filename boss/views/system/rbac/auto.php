<?php

use yii\helpers\Html;
use yii\base\View;

/* @var $this yii\web\View */
/* @var $model dbbase\models\Area */
$this->registerAssetFiles("jquery");
$this->title = '自动创建授权项';
$this->params['breadcrumbs'][] =['label' => '管理授权项', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-6">
<div class="panel panel-primary">
  <div class="panel-heading">控制器</div>
  <!-- Table -->
<?php foreach ($controllers as $key=>$val):?>
  <table class="table table-bordered">
    <thead style="background-color: #d3dfee;"><tr><th><?=$key;?></th><th style="text-align: center;">操作</th></tr></thead>
    <tbody>
    <?php foreach ($val as $v):?>
        <tr><td><?=$v; ?></td><td align="center">
        <button id="select_<?=$key."@@".$v; ?>" type="button" class="btn btn-primary">扫描授权项</button>
        <button id="delete_<?=$key."@@".$v; ?>" type="button" class="btn btn-primary">删除授权项</button>
        </td></tr>
     <?php endforeach;?>
    </tbody>
  </table>
<?php endforeach;?>
</div>
</div>
<div class="col-xs-6">
<div class="panel panel-primary">
  <div class="panel-heading">授权项</div>
   <table id="actions" class="table table-bordered">
  </table>
  <div class="panel-footer"><button class="btn btn-primary" id="create-item">添加</button></div>
</div>
</div>
<?php 
$this->registerJs('
    $("button[id^=select_]").click(function(){
            $.ajax({
    		   type: "POST",
    		   url: "/rbac/select-auth-item",
    		   data: "controller="+$(this).attr("id").replace("select_",""),
    		   dataType:"json",
    		   success: function(msg){
    		     if(msg.code==200){
                    var v="";
                    $("#actions").html("");
                    for( var k in msg.data.actions){
                        v = msg.data.actions[k];
                        $("#actions").append("<tr><td>"+v+"</td></tr>");
                    }
    			 }
    		   }
    		}); 
    });
    $("button[id^=delete_]").click(function(){
        if(confirm("确定要删除此控制器中的所有授权项吗？")){
            $.ajax({
    		   type: "POST",
    		   url: "/rbac/delete-auth-item",
    		   data: "controller="+$(this).attr("id").replace("delete_",""),
    		   dataType:"json",
    		   success: function(msg){
    		     if(msg.code==200){
                    alert(msg.data);
    			 }
    		   }
    		});
        } 
    });
    $("#create-item").click(function(){
            var actions = new Array();
            $("#actions td").each(function(){
                 actions.push($(this).text());   
            });
            if(actions.length>0){
                $.ajax({
        		   type: "POST",
        		   url: "/rbac/create-permission",
        		   data: "actions="+actions.join(","),
        		   dataType:"json",
        		   success: function(msg){
        		     if(msg.code==200){
                          $("#actions").html("");
                          $("#actions").append("<tr><th>添加完毕！</th></tr>");
        			 }
        		   }
        		}); 
            }
    });
');
?>