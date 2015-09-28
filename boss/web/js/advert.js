$('#select-city-list > div > ul > li').click(function(){addCityAdvertPosition($(this));});
$(document).on('click', '.closeCity', function(){closeCity($(this));return false;});
$(document).on('click', '.nav > li[id!=select-city-list]', function(){changeTab($(this))});
//$('#add-advert-position').click(function(){addPosition($(this));});
//$(document).on('click', '#advert-position-create', function(){advertPositionCreate($(this));});
$(document).on('change', '#operationadvertposition-operation_platform_id', function(){getPlatformVersion($(this));});
$(document).on('click', '#operationadvertposition-useall', function(){cityListIsUsed($(this));});

//function advertPositionCreate(obj){
//    var o = obj.parent().parent();
//    var url = o.attr('action');
//    var data = getFormData(o);
//    $.post(url, data, function(t){
//        $.win.close($('.closeWin'));
//    }, 'html');
//}


//function getFormData(o){
//    var inputs = o.find('input[type=text]');
//    var checkbox = o.find('input[type=checkbox]');
//    var select = o.find('select');
//    var data = {};
//    for(var i = 0; i < inputs.length; i++){
//        data[$(inputs[i]).attr('name')] = $(inputs[i]).val();
//    }
//    var checkall = true;
//    var citys = '';
//    for(var n = 0; n < checkbox.length; n++){
//        if($(checkbox[n]).attr('id') == 'operationadvertposition-useall' && $(checkbox[n]).prop('checked')){
//            data[$(checkbox[n]).attr('name')] = $(checkbox[n]).val();
//            checkall = false;
//        }
//        if($(checkbox[n]).prop('checked') && checkall){
//            var city_key = $(checkbox[n]).attr('name');
//            citys += $(checkbox[n]).val()+',';
//        }
//    }
//    if(citys != ''){
//        data[city_key] = citys;
//    }
//    for(var j = 0; j < select.length; j++){
//        data[$(select[j]).attr('name')] = $(select[j]).val();
//    }
//    return data;
//}

function cityListIsUsed(obj){
    var checked = obj.prop('checked');
    $('#operationadvertposition-citysList').find('input').prop('disabled', checked);
}

function getPlatformVersion(obj){
    var platform_id = obj.val();
    if(platform_id != 0 && platform_id != ''){
        $.post('/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
            if(t.result){
                var str = '<option value="0">选择版本</option>';
                for(var i in t.data){
                    str += '<option value="'+i+'">'+t.data[i]+'</option>';
                }
                $('#operationadvertposition-operation_platform_version_id').html(str);
                $('#operationadvertposition-operation_platform_version_id').parent().removeClass('hide');
            }else{
                $('#operationadvertposition-operation_platform_version_id').parent().addClass('hide');
            }
        }, 'json');
    }
}

//function addPosition(obj){
//    var url = obj.attr('url');
//    $.win.open('创建广告位置', url);
//}

function changeTab(obj){
    if(obj.attr('class') != 'active'){
        var o = obj.parent().children('li');
        o.removeClass('active');
        obj.addClass('active');
        loadPositionData(obj);
    }
}

function closeCity(obj){
    var o = obj.parent().parent();
    if(o.attr('class') == 'active'){
        o.prev().addClass('active');
        loadPositionData(o.prev());
    }
    o.remove();
}

function loadPositionData(obj){
    var citys = obj.attr('city_id').split('_');
    var len = citys.length;
    var city_id = citys[len-1];
    adPositionShowHtml('<h1 class="text-center">正在加载，请稍后…</h1>');
    var url = '/operation-advert-position/city-advert-position';
    var data = {'operation_city_id': city_id};
    $.post(url, data, function(t){
        if(t != ''){
            adPositionShowHtml(t);
        }else{
            adPositionShowHtml('<h1 class="text-center">加载失败，请重试…</h1>');
        }
    }, 'html');
}

function adPositionShowHtml(t){
    $('#loadBox').html(t);
}

function addCityAdvertPosition(obj){
    addCity(obj);
    loadPositionData(obj);
}

function addCity(obj){
    var city_id = obj.attr('city_id');
    var cur = $('#tab_city_'+city_id);
    if(cur[0] != null){
        $('#select-city-list').parent().children('li').removeClass('active');
        cur.addClass('active');
        return false;
    }
    var city_name = obj.children('a').html();
    $('#select-city-list').parent().children('li').removeClass('active');
    var html = '<li class="active" id="tab_city_'+city_id+'" city_id="'+city_id+'" role="presentation"><a href="#">'+city_name+'<span class="closeCity glyphicon glyphicon-remove"></span></a></li>';
    $('#select-city-list').before(html);
    return true;
}