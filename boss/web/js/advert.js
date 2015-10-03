$(document).on('change', '#operationadvertposition-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertposition-operation_platform_version_id');});
$(document).on('change', '#operationadvertrelease-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertrelease-operation_platform_version_id');});
//$(document).on('change', '#operationadvertcontent-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertcontent-operation_platform_version_id');});

$('#operationadvertcontent-operation_platform_id > label > input[type=checkbox]').click(function(){getVersions($(this));});
$('.platform').click(function(){seachAdvertContent($(this), 'platform');});
$('.version').click(function(){seachAdvertContent($(this), 'version');});
$('.platforma').click(function(){selectPlatform($(this));});

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
            $.post('/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
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
        var url = '/operation-advert-content/ajax-list';
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
    var url = '/operation-advert-content/ajax-list';
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
        $.post('/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
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