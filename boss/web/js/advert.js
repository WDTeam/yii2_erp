$(document).on('change', '#operationadvertposition-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertposition-operation_platform_version_id');});
$(document).on('change', '#operationadvertrelease-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertrelease-operation_platform_version_id');});
//$(document).on('change', '#operationadvertcontent-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertcontent-operation_platform_version_id');});

$('#operationadvertcontent-operation_platform_id > label > input[type=checkbox]').click(function(){getVersions($(this));});
$('.platform').click(function(){seachAdvertContent($(this), 'platform');});
$('.version').click(function(){seachAdvertContent($(this), 'version');});
$('.platforma').click(function(){selectPlatform($(this));});
$(document).on('click', '.advert-goup', function(){adverGoUp($(this));});
$(document).on('click', '.advert-godown', function(){adverGoDown($(this));});
//$('#insertAdvertContent').click(function(){insertAdvertContent();});
//$('#emptyAdvertContent').click(function(){emptyAdvertContent();});
$(document).on('click', '.selectContent', function(){selectContent($(this));});
$(document).on('click', '.cancel', function(){$.win.close($('#closeWin'));});
$(document).on('click', '.selectQuery', function(){selectQuery();});

$('#operationadvertrelease-city_id').change(function(){getPlatforms();});
$(document).on('click', '.step2 > label > input[type=checkbox]', function(){getPlatformVersions($(this));});
$(document).on('click', '.platform_versions > label > input[type=checkbox]', function(){getAdverts($(this).parent().parent().attr('platform_id'), $(this).val());});
$('#saveOrders').click(function(){saveOrders();});

//保存广告顺序
$('#saveReleaseAdvOrders').click(function(){
    saveReleaseAdvOrders();
});

//上线城市第一步，反选城市名称
$('#adv_city_reverse').click(function(){

    $("#adv_city_selected").find('input').each(function () {  
        $(this).prop("checked", !$(this).prop("checked"));  
    });  
});

function saveReleaseAdvOrders(){
    var objs = $('.advert_release_orders_input');
    var len = objs.length;
    var data = {};
    for(var i = 0; i < len; i++){
        data[$(objs[i]).attr('id')] = $(objs[i]).val();
    }
    $.post('/operation/operation-advert-release/save-orders', data,
            function(t){
                //alert(t);
                console.log(t);
            }, 'html');
    window.location.reload(true);
}

function saveOrders(){
    var objs = $('.operation_advert_content_orders_input');
    var len = objs.length;
    var data = {};
    for(var i = 0; i < len; i++){
        data[$(objs[i]).attr('content_id')] = $(objs[i]).val();
    }
    $.post('/operation/operation-advert-content/save-orders', data, function(t){alert(t);}, 'html');
}


function getAdverts(platform_id, version_id){
    if(version_id != '' && version_id != null){
        var data = {'version_id':version_id, 'platform_id':platform_id}
    }else{
        var data = {'platform_id':platform_id}
    }
    $.post('/operation/operation-advert-content/adverts', {'version_id':version_id, 'platform_id':platform_id}, function(t){
        if($('#step3').html() == ''){
            t = '<label class="control-label" for="operationadvertrelease-city_id">第四步：选择要发布的广告</label>'+t;
        }
        $('#step4').append(t);
    },'html');
}

function getPlatformVersions(obj){
    var checked = obj.prop('checked');
    var platform_id = obj.val();
    if(checked){
        $.post('/operation/operation-platform-version/platform-versions', {platform_id : platform_id}, function(t){
            if(t != ''){
                if($('#step3').html() == ''){
                    t = '<label class="control-label" for="operationadvertrelease-city_id">第三步：选择要的目标版本</label>'+t;
                }
                $('#step3').append(t);
            }else{
                getAdverts(platform_id);
            }
        }, 'html');
    }else{
        try{$('.step3_versions_'+platform_id).remove();}catch(e){}
    }
}

function getPlatforms(){
    $.get('/operation/operation-platform/platforms', {}, function(t){
        $("#step2").html(t);
    }, 'html');
}

function selectQuery(){
    var objs = $('.selectBox').find('a[class="selectContent select"]');
    if(objs.length <= 0){
        alert('请您选择广告内容');
    }else{
        var str = '';
        for(var i = 0; i < objs.length; i++){
            str += '<div class="list-group-item" content_id="'+$(objs[i]).attr('content_id')+'">';
            str += '    <div class="col-md-10"><input type="hidden" value="'+$(objs[i]).attr('content_id')+'" name="OperationAdvertRelease[operation_advert_contents][]" />'+$(objs[i]).children('span').html()+'</div>';
            str += '    <div class="btn-group col-md-2">';
            str += '        <button title="向上" class="advert-goup btn btn-primary badge col-md-6" type="button"><span class="glyphicon glyphicon-arrow-up"></span></button>';
            str += '        <button title="向下" class="advert-godown btn btn-success badge col-md-6" type="button"><span class="glyphicon glyphicon-arrow-down"></span></button>';
            str += '    </div>';
            str += '    <div class="clearfix"></div>';
            str += '</div>';
        }
        $('#advertListContent').append(str);
        $.win.close($('#closeWin'));
    }
}

function selectContent(obj){
    if(obj.attr('class') == 'selectContent'){
        obj.addClass('select');
    }else{
        obj.removeClass('select');
    }
}
//
//function insertAdvertContent(){
//    var obj = $('#advertListContent');
//    var objs = obj.children('div[class="list-group-item"]');
//    var data = [];
//    if(objs.length > 0){
//        for(var i = 0; i < objs.length; i++){
//            data[i] = $(objs[i]).attr('content_id');
//        }
//    }
//    var url = '/operation/operation-advert-content/get-list';
//    if(data.length > 0){
//        url += '?data='+data;
//    }
//    $.win.open('选择广告内容', url);
//}

//function emptyAdvertContent(){
//    $('#advertListContent').html('');
//}

function adverGoUp(obj){
    var o = obj.parent().parent();
    var p = o.parent();
    var prev = o.prev();
    if(prev[0] == null){
        alert('已经在是第一个了');
    }else{
        prev.before(o);
    }
}

function adverGoDown(obj){
    var o = obj.parent().parent();
    var p = o.parent();
    var next = o.next();
    if(next[0] == null){
        alert('已经在是最后一个了');
    }else{
        next.after(o);
    }
}

function selectPlatform(obj){
    var o = obj.parent();
    var dropMenu = obj.next();
    var active = o.parent().children('li');
    active.removeClass('active')
    active.addClass('dropdown');
    o.removeClass('dropdown')
    o.addClass('active');
}

function getVersions(obj){
    var check = obj.prop('checked');
    var platform_id = obj.val();
    var obox = $('#platformVersion');
    if(check){
        if(platform_id != 0 && platform_id != ''){
            $.post('/operation/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
                if(t.result){
                    var str = '<div id="versions_list_'+platform_id+'">';
                    for(var i in t.data){
                        str += '<label><input type="checkbox" name="OperationAdvertContent[operation_platform_version_id]['+platform_id+'][]" value="'+i+'" />'+t.data[i]+'</label>';
                    }
                    str += '</div>';
                    obox.append(str);
                    var len = obox.children('div').length;
                    if(len > 0){obox.parent().attr('style', 'display:block !important');}
                }
            }, 'json');
        }
    }else{
        var o = $('#versions_list_'+platform_id);
        if(o[0] != null){o.remove();}
        var len = obox.children('div').length;
        if(len <= 0){obox.parent().hide();}
    }
}

function seachAdvertContent(obj, type){
    if(type == 'platform'){
        SearchPlatform(obj);
    }else{
        SearchVersion(obj);
    }
}

function SearchPlatform(obj){
    var ul = obj.children('ul');
    if(ul[0] == null){
        var platform_id = obj.attr('platform_id');
        if(platform_id == null){platform_id = 'all';}
        var data = {'platform_id':platform_id};
        var url = '/operation/operation-advert-content/ajax-list';
        $.post(url, data, function(t){
            $('#searchTable').html(t);
        }, 'html');
    }
}

function searchTable(t){
    $('#searchTable').html(t);
}

function SearchVersion(obj){
    
    var platform_id = obj.attr('platform_id');
    var version_id = obj.attr('version_id');
    var data = {'platform_id':platform_id, 'version_id':version_id};
    var url = '/operation/operation-advert-content/ajax-list';
    $.post(url, data, function(t){
        $('#searchTable').html(t);
        var o1 = obj.parent().prev().children('span[class=version-display]');
        var o = obj.children('a');
        var ts = o1.html().split('(');
        var txt = o.html();
        if(ts.length > 1){var p = ts[0];}else{var p = ts;}
        txt = p+'('+txt+')';
        o1.html(txt);
    }, 'html');
}

function getPlatformVersion(obj, sourceid){
    var platform_id = obj.val();
    if(platform_id != 0 && platform_id != ''){
        $.post('/operation/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
            if(t.result){
                var str = '<option value="0">选择版本</option>';
                for(var i in t.data){
                    str += '<option value="'+i+'">'+t.data[i]+'</option>';
                }
                $('#'+sourceid).html(str);
                $('#'+sourceid).parent().removeClass('hide');
            }else{
                $('#'+sourceid).parent().addClass('hide');
            }
        }, 'json');
    }
}
