<?php

use yii\helpers\Html;
use yii\base\View;

/* @var $this yii\web\View */
/* @var $model common\models\Area */
$this->registerAssetFiles("jquery");
$this->title = '分配授权项';
$this->params['breadcrumbs'][] =['label' => '管理授权项', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.col-xs-3,.col-xs-1,.col-xs-4{
	text-align: center;
}
.tab-pane{
	padding-top: 10px;
}
</style>
<div class="rbac-auth">
    <ul class="nav nav-tabs" role="tablist" id="myTab">
      <li role="presentation" class="active"><a href="#userOper" role="tab" data-toggle="tab">用户</a></li>
      <li role="presentation"><a href="#roleOper" role="tab" data-toggle="tab">角色</a></li>
      <?php if ( false ):?><li role="presentation"><a href="#permissionOper" role="tab" data-toggle="tab">权限</a></li><?php endif; ?>
    </ul>
    
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="userOper">
            <div class="panel panel-primary">
                <div class="panel-heading">分配各角色给各用户</div>
                <div class="panel-body">
                        <div class="col-xs-2">
                            <div class="panel-heading">用户</div>
                            <div class="panel-body">
                                <select id="user"  size="30" class="form-control">
                                <?php foreach ($data['users'] as $user):?>
                                <option value="<?=$user['id']; ?>"><?=$user['username']; ?></option>
                                <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="panel-heading">已经分配角色</div>
                            <div class="panel-body">
                                <select id="user-role-yes"  multiple="multiple" size="30"   class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-xs-2" style="margin-top: 250px; text-align: center;">
                            <div class="btn-group">
                                <button id="user-role-l" class="btn btn-primary btn-lg" >&lt;&lt;</button>
                                <button id="user-role-r" class="btn btn-default btn-lg" >&gt;&gt;</button>
                            </div>
                            <h1 id="user-role-loading" style="display: none;"><span class="label label-primary">加载中</span></h1>
                        </div>
                        <div class="col-xs-4">
                            <div class="panel-heading">尚未分配角色</div>
                            <div class="panel-body">
                                <select id="user-role-no"  multiple="multiple" size="30"   class="form-control"></select>
                            </div>
                        </div>
                </div>
            </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="roleOper">
            <div class="panel panel-primary">
                <div class="panel-heading">分配各权限给各角色</div>
                <div class="panel-body">
                        <div class="col-xs-2">
                            <div class="panel-heading">角色</div>
                            <div class="panel-body">
                                <select id="role"  size="30"  class="form-control">
                                <?php foreach ($data['roles'] as $k=>$v):?>
                                <option value="<?=$k; ?>"><?=$k; ?></option>
                                <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="panel-heading">已经分配权限</div>
                            <div class="panel-body">
                                <select id="role-permission-yes"  multiple="multiple" size="30"   class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-xs-2" style="margin-top: 250px;text-align: center;">
                            <div class="btn-group">
                                <button id="role-permission-l" class="btn btn-primary btn-lg" >&lt;&lt;</button>
                                <button id="role-permission-r" class="btn btn-default btn-lg" >&gt;&gt;</button>
                            </div>
                            <h1 id="role-permission-loading" style="display: none;"><span class="label label-primary">加载中</span></h1>
                        </div>
                        <div class="col-xs-4">
                            <div class="panel-heading">尚未分配权限</div>
                            <div class="panel-body">
                                <select id="role-permission-no"  multiple="multiple" size="30"   class="form-control"></select>
                            </div>
                        </div>
                </div>
            </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="permissionOper">
            <div class="panel panel-primary">
                <div class="panel-heading">分配各权限给各权限</div>
                <div class="panel-body">
                        <div class="col-xs-3">
                            <div class="panel-heading">权限</div>
                            <div class="panel-body">
                                <select id="permission"  size="30"  class="form-control">
                                <?php foreach ($data['permissions'] as $k=>$v):?>
                                <option value="<?=$k; ?>"><?=$k; ?></option>
                                <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="panel-heading">已经分配权限</div>
                            <div class="panel-body">
                                <select id="permission-permission-yes"  multiple="multiple" size="30"   class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top: 250px;">
                            <div class="btn-group">
                                <button id="permission-permission-l" class="btn btn-primary btn-lg" >&lt;&lt;</button>
                                <button id="permission-permission-r" class="btn btn-default btn-lg" >&gt;&gt;</button>
                            </div>
                            <h1 id="permission-permission-loading" style="display: none;"><span class="label label-primary">加载中</span></h1>
                        </div>
                        <div class="col-xs-4">
                            <div class="panel-heading">尚未分配权限</div>
                            <div class="panel-body">
                                <select id="permission-permission-no"  multiple="multiple" size="30"   class="form-control"></select>
                            </div>
                        </div>
                </div>
            </div>
      </div>
    </div>
</div>
<?php 
$this->registerJs('
    function auth(autha,authb,ar){
        $("#"+autha+"-"+authb+"-loading").show();
        var data = autha+"="+$("#"+autha).val();
        if(ar){
            if(ar=="tol") data += "&"+authb+"s[assign]="+$("#"+autha+"-"+authb+"-no").val();
            if(ar=="tor") data += "&"+authb+"s[revoke]="+$("#"+autha+"-"+authb+"-yes").val();
    	}
        $.ajax({
    		   type: "POST",
    		   url: "/rbac/"+autha+"-"+authb+"",
    		   data: data,
    		   dataType:"json",
    		   success: function(msg){
    		     if(msg.code==200){
                    $("#"+autha+"-"+authb+"-no,#"+autha+"-"+authb+"-yes").html("");
            	     for(var k in msg.data.a){
                	     $("<option/>").val(k).text(k).appendTo("#"+autha+"-"+authb+"-no");
            	     }
            	     for(var k in msg.data.b){
                	     $("<option/>").val(k).text(k).appendTo("#"+autha+"-"+authb+"-yes");
            	     }
                    $("#"+autha+"-"+authb+"-loading").hide();
    			 }
    		   }
    		}); 
    }
    $("#user").change(function(){auth("user","role",false);});
    $("#user-role-l").click(function(){
        if($("#user-role-no").val()!=null) auth("user","role","tol");
     });
    $("#user-role-r").click(function(){
        if($("#user-role-yes").val()!=null) auth("user","role","tor");
    });
    if($("#user").val()>0){
        auth("user","role",false);
    }
    
    $("#role").change(function(){auth("role","permission",false);});
    $("#role-permission-l").click(function(){
        if($("#role-permission-no").val()!=null) auth("role","permission","tol");
    });
    $("#role-permission-r").click(function(){
        if($("#role-permission-yes").val()!=null) auth("role","permission","tor");
    });
    if($("#role").val()>0){
        auth("role","permission",false);
    }
    
    $("#permission").change(function(){auth("permission","permission",false);});
    $("#permission-permission-l").click(function(){
        if($("#permission-permission-no").val()!=null) auth("permission","permission","tol");
    });
    $("#permission-permission-r").click(function(){
        if($("#permission-permission-yes").val()!=null) auth("permission","permission","tor");
    });
    if($("#permission").val()>0){
        auth("permission","permission",false);
    }
');
?>