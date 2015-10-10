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
});

function selectCategoryChecked(){
    var status = false;
    $("input[name='categorylist[]']").each(function(){
        categoryGoods($(this));
    });
}

function categoryGoods(obj){
    var status = obj.is(':checked');
    var value = obj.attr('value');
    if(status){
        var url = '/operation-city/getcategorygoods'
        var data = {'categoryid': value};
        $.post(url, data, function(t){
            $('#categoryGoodsContent').append(t);
        }, 'html');
    }else{
        $('#goods_'+value).remove();
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