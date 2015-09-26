$(document).ready(function(){
    $('#select-city-list > div > ul > li').click(function(){addCityAdvertPosition($(this));});
    $('.closeCity').on('click', function(){closeCity($(this));});
});

function closeCity(obj){
    alert('dfsdfs');
    var o = obj.parent().parent();
    o.prev().addClass('active');
    o.remove();
}

function addCityAdvertPosition(obj){
    addCity(obj);
}

function addCity(obj){
    var city_id = obj.attr('city_id');
    var cur = $('#tab_city_'+city_id);
    if(cur[0] != null){
        return false;
    }
    var city_name = obj.children('a').html();
    $('#select-city-list').parent().children('li').removeClass('active');
    var html = '<li class="active" id="tab_city_'+city_id+'" role="presentation"><a href="#">'+city_name+'<span class="closeCity glyphicon glyphicon-remove"></span></a></li>';
    $('#select-city-list').before(html);
}