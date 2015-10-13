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
    selectCategoryChecked();






    $("input[name='shopdistrict[]']").change(function(){
        shopdistrict($(this));
    });

    $(".settingGoodsinfo").click(function(){
        settingGoodsinfo();
    });
});

function shopdistrict(obj){
    var val = obj.attr('value');
    var status = obj.is(':checked');
    if(status){
        $('.shopdistrictgoods'+val).show();
    }else{
        $('.shopdistrictgoods'+val).hide();
    }
}

function settingGoodsinfo(){
    var operation_goods_market_price_demo = $('.operation_goods_market_price_demo').val();
    var operation_goods_price_demo = $('.operation_goods_price_demo').val();
    var operation_goods_lowest_consume_demo = $('.operation_goods_lowest_consume_demo').val();
    var operation_goods_start_time_demo = $('.operation_goods_start_time_demo').val();
    var operation_goods_end_time_demo = $('.operation_goods_end_time_demo').val();
    $('.operation_goods_market_price').val(operation_goods_market_price_demo);
    $('.operation_goods_price').val(operation_goods_price_demo);
    $('.operation_goods_lowest_consume').val(operation_goods_lowest_consume_demo);
    $('.operation_goods_start_time').val(operation_goods_start_time_demo);
    $('.operation_goods_end_time').val(operation_goods_end_time_demo);
}






function selectCategoryChecked(){
    var status = false;
    $("input[name='categorylist[]']").each(function(){
        categoryGoods($(this));
    });
}

function categoryGoods(obj){
    var status = obj.is(':checked');
    var value = obj.attr('value');
    $('.goods_list').remove();
    if(status){
        var url = '/operation-city/getcategorygoods';
        var city_id = $('.city_id').val();
        var data = {'categoryid': value, 'city_id' : city_id};
        $.post(url, data, function(t){
            $('#categoryGoodsContent').append(t);
        }, 'html');
    }else{
        $('#goods_'+value).remove();
    }
}

function alllist(obj){
    var status = obj.is(':checked');
    if(obj.attr('val') == 'shopdistrict'){
        if(status){
            $("input[name='"+obj.attr('val')+"[]']").prop('checked',true);
        }else{
            $("input[name='"+obj.attr('val')+"[]']").prop('checked',false);
        }
        $("input[name='" + obj.attr('val') + "[]']").each(function(){
            shopdistrict($(this));
        });
    }else {
        if (status) {
            $("input[name='" + obj.attr('val') + "[]']").prop('checked', true);
        } else {
            $("input[name='" + obj.attr('val') + "[]']").prop('checked', false);
        }
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