$(document).ready(function(){
    $('#cityid').change(function(){
//        getCityShopDistrict($(this));
    });
    
    $('#alllist').change(function(){
        alllist($(this));
    });

    $("input[name='categorylist[]']").click(function(){
        categoryGoods($(this));
    });
    
    $('#goodssetting').click(function(){
        goodssetting($(this));
    });
    
    selectCategoryChecked();
});

/**
 * 统一设置
 * @returns {undefined}
 */
function goodssetting(obj){
    $(".operation_goods_market_price").attr('value', $(".operation_goods_market_price_demo").attr('value')); //市场价格
    $(".operation_goods_price").attr('value', $(".operation_goods_price_demo").attr('value'));//销售价格
    $(".operation_goods_lowest_consume").attr('value', $(".operation_goods_lowest_consume_demo").attr('value'));//最低消费数量
    $(".operation_goods_start_time").attr('value', $(".operation_goods_start_time_demo").attr('value'));//开始时间
    $(".operation_goods_end_time").attr('value', $(".operation_goods_end_time_demo").attr('value'));//结束时间
}

function selectCategoryChecked(){
    var status = false;
    $("input[name='categorylist[]']").each(function(){
        categoryGoods($(this));
    });
}

var categoryid = 0;
function categoryGoods(obj){
    var status = obj.is(':checked');
    var value = obj.attr('value');
    if(value != categoryid){
        categoryid = value;
        if(status){
            $('#categoryGoodsContent').html(" ");
            var url = '/operation-city/getcategorygoods';
            var data = {'categoryid': value};
            $.post(url, data, function(t){
                $('#categoryGoodsContent').append(t);
            }, 'html');
        }
    }
}

function alllist(obj){
    var status = obj.is(':checked');
    if(status){
        $("input[name='"+obj.attr('val')+"[]']").prop('checked',true);
    }else{
        $("input[name='"+obj.attr('val')+"[]']").prop('checked',false);
    }
}

//function getCityShopDistrict(obj){
//    var value = obj.val();
//    if(value){
//        var url = '/operation-city/getcityshopdistrict';
//        var data = {'city_info': value};
//        $.post(url, data, function(t){
//            $('#cityshopdistrict').html(t);
//        }, 'html');
//    }
//}