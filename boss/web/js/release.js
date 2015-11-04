$(document).ready(function(){
    $('#cityid').change(function(){
//        getCityShopDistrict($(this));
    });
    
    $('#alllist').change(function(){
        alllist($(this));
    });
    
    $("input[name='categorylist[]']").change(function(){
        categoryGoods($(this));
    });
    selectCategoryChecked();


    $("input[name='shopdistrict[]']").change(function(){
        shopdistrict($(this));
    });

    $(".settingGoodsinfo").click(function(){
        settingGoodsinfo();
    });
    
    $('.addshopdistrictcoordinate').click(function(){
        addshopdistrictcoordinate();
    });

    //经度输入框验证
    $('.longitude').focusout(function(){
        validateItude($(this));
    });

    //纬度输入框验证
    $('.latitude').focusout(function(){
        validateItude($(this));
    });

    
    $(document).on("click",".delshopdistrictcoordinate",function(){
        delshopdistrictcoordinate($(this));
    }); 
});

function shopdistrict(obj){
    var val = obj.attr('value');
    var status = obj.is(':checked');
    if(status){
        //$('.shopdistrictgoods'+val).show();
    }else{
        //$('.shopdistrictgoods'+val).hide();
    }
}

function settingGoodsinfo(){
    var operation_goods_market_price_demo = $('.operation_goods_market_price_demo').val();
    var operation_goods_price_demo = $('.operation_goods_price_demo').val();
    var operation_goods_lowest_consume_demo = $('.operation_goods_lowest_consume_demo').val();
    var operation_goods_start_time_demo = $('.operation_goods_start_time_demo').val();
    var operation_goods_end_time_demo = $('.operation_goods_end_time_demo').val();
    if(operation_goods_market_price_demo != ''){
        $('.operation_goods_market_price').val(operation_goods_market_price_demo);
    }
    if(operation_goods_price_demo != ''){
        $('.operation_goods_price').val(operation_goods_price_demo);
    }
    if(operation_goods_lowest_consume_demo != ''){
        $('.operation_goods_lowest_consume').val(operation_goods_lowest_consume_demo);
    }
    if(operation_goods_start_time_demo != ''){
        $('.operation_goods_start_time').val(operation_goods_start_time_demo);
    }
    if(operation_goods_end_time_demo != ''){
        $('.operation_goods_end_time').val(operation_goods_end_time_demo);
    }
}

//增加经纬度
function addshopdistrictcoordinate(){
    var content = '<div class="form-group "><div class="col-md-10"> 开始经度：<input type="text" style="width:50px;" value="" name="operation_shop_district_coordinate_start_longitude[]" > 开始纬度：<input type="text" style="width:50px;" value="" name="operation_shop_district_coordinate_start_latitude[]" > 结束经度：<input type="text" style="width:70px;" value="" name="operation_shop_district_coordinate_end_longitude[]" > 结束纬度：<input type="text" style="width:50px;" value="" name="operation_shop_district_coordinate_end_latitude[]" ><div class="glyphicon glyphicon-minus delshopdistrictcoordinate" ></div></div></div>';
    $('.shopdistrictcoordinatelist').append(content);
}

//删除经纬度
function delshopdistrictcoordinate(obj){
    obj.parent().remove();
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
        var url = '/operation/operation-city/getcategorygoods';
        var city_id = $('.city_id').val();
        var data = {'categoryid': value, 'city_id' : city_id};
        $.post(url, data, function(t){
            $('#categoryGoodsContent').append(t);

            //radio应用icheck样式
            $('#categoryGoodsContent input').each(function(){
                ApplyToRadio($(this));
            });
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

function validateItude(obj){
    obj.css("border-color","gray");
    var itude = obj.val();

    if (itude == '' || itude == undefined) {
        obj.css("border-color","red");
    } else {
        var res = IsNum(itude);

        if (res) {
            if (itude > 180 || itude < 0) {
                obj.css("border-color","red");
                alert('输入值超出范围');
            }
        } else {
            obj.css("border-color","red");
            alert('输入值格式不正确,只能为数字');
        }
    }
}

//判断是否为数字
function IsNum(s){
    if (s != null && s != "")
    {
        return !isNaN(s);
    }
    return false;
}

//function getCityShopDistrict(obj){
//    var value = obj.val();
//    if(value){
//        var url = '/operation/operation-city/getcityshopdistrict';
//        var data = {'city_info': value};
//        $.post(url, data, function(t){
//            $('#cityshopdistrict').html(t);
//        }, 'html');
//    }
//}
