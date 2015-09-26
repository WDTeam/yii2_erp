$('#select-city-list > div > ul > li').click(function(){addCityAdvertPosition($(this));});
$(document).on('click', '.closeCity', function(){closeCity($(this));return false;});
$(document).on('click', '.nav > li[id!=select-city-list]', function(){changeTab($(this))});

function changeTab(obj){
    var o = obj.parent().children('li');
    o.removeClass('active');
    obj.addClass('active');
    loadPositionData(obj);
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
    $('#loadBox').html('<h1 class="text-center">正在加载，请稍后…</h1>');
    var url = '/operation-advert-position/city-advert-position';
    var data = {'city_id': city_id};
    $.post(url, data, function(t){
        if(t != ''){
            $('#loadBox').html(t);
        }else{
            $('#loadBox').html('<h1 class="text-center">加载失败，请重试…</h1>');
        }
    }, 'html');
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
}