/**
 * Created by linhongyou on 2015/9/25.
 */
window.coupons = new Array();
window.cards = new Array();
var address_list = new Object();
var goods_list = new Array();

$("#order-order_customer_phone").keyup(function(e){
    if(e.keyCode == 13){
       getCustomerInfo();
    }
});

$("#order-order_customer_phone").blur(getCustomerInfo);





$(document).on("change","#order-order_service_type_id input",function(){
    var goods_id = $("#order-order_service_type_id input:checked").val();
    var goods = new Array();
    for(var k in goods_list){
        if(goods_list[k].operation_goods_id == goods_id){
            goods = goods_list[k];
            break;
        }
    }
    var unit_price = parseFloat(goods.operation_shop_district_goods_price);
    $("#order_unit_money").text(unit_price.toFixed(2));
    $("#order-order_unit_money").val(unit_price.toFixed(2));
    setOrderMoney();
    getCoupons();
});



$(document).on("change","#order-address_id input[type='radio']",function(){
    $("#order-order_address").val($("#order-address_id input:checked").parent().text());
    getGoods();//地址信息变更后去获取商品信息
});


$("#order-order_booked_count input").change(function(){
    setOrderMoney();
    $("#order-orderbookedtimerange").html('');
    for(var i=8;i<=18;i++){
        var hour = i<10?'0'+i:i;
        var hourtime = i+$("#order-order_booked_count input:checked").val()/60;
        var hour2 = parseInt(hourtime)<10?'0'+parseInt(hourtime):parseInt(hourtime);
        var minute = (hourtime%1==0)?'00':'30';
        $("#order-orderbookedtimerange").append('<label class="radio-inline"><input type="radio"  value="'+hour+':00-'+hour2+':'+minute+'" name="Order[orderBookedTimeRange]"> '+hour+':00-'+hour2+':'+minute+'</label>');
    }

});


$('#order-order_pay_type input').change(function(){
        $('[id^=order_pay_type]').hide();
        $('#order_pay_type_'+$('#order-order_pay_type input:checked').val()).show();
});

$(document).on("click","#add_address_btn",function(){
    if($('#address_0').length==0 && $('#order-customer_id').val()!='') {
        $form = '<div class="radio" id="address_0">' + $('#address_form').html() + '</div>';
        $("#order-address_id").append($form);
    }
});

$(document).on("click",".update_address_btn",function(){
    var address_id = $(this).parent().find('input[type=radio]').val();
    $(this).parent().html($("#address_form").html());
    var address = address_list[address_id];
    $('#address_'+address_id+' .province_form').val(address.operation_province_id);
    getCity(address.operation_province_id,address_id,address.operation_city_id,address.operation_area_id);
    $('#address_'+address_id+' .detail_form').val(address.customer_address_detail);
    $('#address_'+address_id+' .nickname_form').val(address.customer_address_nickname);
    $('#address_'+address_id+' .phone_form').val(address.customer_address_phone);
});

$(document).on("change",".province_form",function(){
    var province_id = $(this).val();
    var address_id = $(this).parents('.radio').attr('id').split('_')[1];
    getCity(province_id,address_id,0,0);
});

$(document).on("change",".city_form",function(){
    var city_id = $(this).val();
    var address_id = $(this).parents('.radio').attr('id').split('_')[1];
    getCounty(city_id,address_id,0);
});

$(document).on("click",".cancel_address_btn",function(){
    var address_id = $(this).parents('.radio').attr('id').split('_')[1];
    if(address_id>0) {
        var v = address_list[address_id];
        $("#address_" + address_id).html(
            '<label class="col-sm-7"><input type="radio" value="' + v.id + '" '
            + ' name="Order[address_id]"> '
            + v.operation_province_name + ' '
            + v.operation_city_name + ' '
            + v.operation_area_name + ' '
            + v.customer_address_detail + ' '
            + v.customer_address_nickname + ' '
            + v.customer_address_phone + '</label>' +
            '<label class="col-sm-4" style="color: #FF0000;">' +
            (v.customer_address_longitude * v.customer_address_latitude == 0 ? '该地址没有匹配到经纬度' : '该地址可以下单') +
            '</label>' +
            '<button class="btn btn-xs btn-warning col-sm-1 update_address_btn" type="button">编辑</button>'
        );
    }else{
        $("#address_0").remove();
    }
});

$(document).on("click",".save_address_btn",function(){
    var address_id = $(this).parents('.radio').attr('id').split('_')[1];
    var province_id = $('#address_'+address_id+' .province_form').val();
    var city_id = $('#address_'+address_id+' .city_form').val();
    var county_id = $('#address_'+address_id+' .county_form').val();
    var county_name = $('#address_'+address_id+' .county_form option:selected').text();
    var city_name = $('#address_'+address_id+' .city_form option:selected').text();
    var province_name = $('#address_'+address_id+' .province_form option:selected').text();
    var detail = $('#address_'+address_id+' .detail_form').val();
    var nickname = $('#address_'+address_id+' .nickname_form').val();
    var phone = $('#address_'+address_id+' .phone_form').val();
    var customer_id = $('#order-customer_id').val();
    if(address_id==0 && customer_id==''){
        alert('请先选择客户再添加地址！');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/order/save-address/?address_id=" + address_id,
        data: "province_id="+province_id+"&city_id="+city_id+"&county_id="+county_id+"&county_name="+county_name+"&city_name="+city_name+"&province_name="+province_name+"&detail="+detail+"&nickname="+nickname+"&phone="+phone+"&customer_id="+customer_id,
        dataType: "json",
        success: function (msg) {
            if(msg.code==200) {
                var v = msg.data;
                address_list[v.id] = v;
                $("#address_"+address_id).attr('id','address_'+ v.id);
                $("#address_" + v.id).html(
                    '<label class="col-sm-7"><input type="radio" value="' + v.id + '" '
                    + ' name="Order[address_id]"> '
                    + v.operation_province_name + ' '
                    + v.operation_city_name + ' '
                    + v.operation_area_name + ' '
                    + v.customer_address_detail + ' '
                    + v.customer_address_nickname + ' '
                    + v.customer_address_phone + '</label>' +
                    '<label class="col-sm-4" style="color: #FF0000;">' +
                    (v.customer_address_longitude * v.customer_address_latitude == 0 ? '该地址没有匹配到经纬度' : '该地址可以下单') +
                    '</label>' +
                    '<button class="btn btn-xs btn-warning col-sm-1 update_address_btn" type="button">编辑</button>'
                );
            }
        }
    });
});

$(document).on('change','#order-coupon_id',function(){
    if($(this).val()!=''){
        var order_pay_money = $("#order-order_money").val()-window.coupons[$(this).val()];
        $(".order_pay_money").text(order_pay_money.toFixed(2));
    }
});

function getCity(province_id,address_id,city_id,county_id)
{
    $.ajax({
        type: "GET",
        url: "/order/get-city/?province_id=" + province_id,
        dataType: "json",
        success: function (city) {
            $('#address_'+address_id+' .city_form').html('<option value="">请选择城市</option>');
           for(var k in city){
               $('#address_'+address_id+' .city_form').append('<option '+(k==city_id?'selected="selected"':'')+' value="'+k+'">'+city[k]+'</option>');
           }
            if(city_id>0) {
                getCounty(city_id, address_id, county_id);
            }
        }
    });
}

function getCounty(city_id,address_id,county_id)
{
    $.ajax({
        type: "GET",
        url: "/order/get-county/?city_id=" + city_id,
        dataType: "json",
        success: function (county) {
            $('#address_'+address_id+' .county_form').html('<option value="">请选择区县</option>');
            for(var k in county){
                $('#address_'+address_id+' .county_form').append('<option '+(k==county_id?'selected="selected"':'')+' value="'+k+'">'+county[k]+'</option>');
            }
        }
    });
}


//计算订单金额填写到表单
function setOrderMoney(){
    $money = $("#order-order_booked_count input:checked").val()/60*$("#order_unit_money").text();
    $("#order-order_money").val($money.toFixed(2));
    $(".order_money").text($money.toFixed(2));
}

function getGoods(){
    var address_id = $("#order-address_id input:checked").val();
    var lng = 0;
    var lat = 0;
    for(var k in address_list){
        if(address_list[k].id == address_id){
            lng = address_list[k].customer_address_longitude;
            lat = address_list[k].customer_address_latitude;
            break;
        }
    }

    $.ajax({
        type: "GET",
        url: "/order/get-goods/?lng=" + lng + "&lat=" + lat,
        dataType: "json",
        success: function (data) {
            if(data.code==200){
                $("#order-order_service_type_id").html('');
                goods_list = data.data;
                for(var k in goods_list){
                    var v = goods_list[k];
                    $("#order-order_service_type_id").append(
                        '<label class="radio-inline"><input type="radio" value="'+ v.operation_goods_id
                        +'" name="Order[order_service_type_id]"> '+ v.operation_shop_district_goods_name+'</label>'
                    );
                }
            }else{
                alert(data.msg);
            }
        }
    });


}

function getCoupons(){
    if(window.customer != undefined) {
        $.ajax({
            type: "GET",
            url: "/order/coupons/?id=" + window.customer.id+"&service_id="+$('#order-order_service_type_id input:checked').val(),
            dataType: "json",
            success: function (coupons) {
                if (coupons.length > 0) {
                    $("#order-coupon_id").html('<option value="">请选择优惠券</option>');
                    for (var k in coupons) {
                        var v = coupons[k];
                        window.coupons[v.id] = v.coupon_money;
                        $("#order-coupon_id").append(
                            '<option value="' + v.id + '" > ' + v.coupon_name + '</option>'
                        );
                    }
                }
            }
        });
    }
}
function getCards(){
    if(window.customer != undefined) {
        $.ajax({
            type: "GET",
            url: "/order/cards/?id=" + window.customer.id,
            dataType: "json",
            success: function (cards) {
                if (cards.length > 0) {
                    $("#order-card_id").html('');
                    for (var k in cards) {
                        var v = cards[k];
                        window.cards[v.id] = v.card_money;
                        $("#order-card_id").append(
                            '<option value="' + v.id + '" > ' + v.card_code + '</option>'
                        );
                    }
                }
            }
        });
    }
}

function getCustomerInfo(){
    var phone = $("#order-order_customer_phone").val();
    var reg = /^1[3-9][0-9]{9}$/;
    if(reg.test(phone)) {
        $.ajax({
            type: "GET",
            url: "/order/customer/?phone=" + phone,
            dataType: "json",
            success: function (customer) {
                window.customer=customer;
                if (customer.id) {
                    $("#order-customer_id").val(customer.id);
                    $("#customer_balance").text(customer.customer_balance);
                    $.ajax({
                        type: "GET",
                        url: "/order/customer-address/?id=" + customer.id,
                        dataType: "json",
                        success: function (address) {
                            address_list = address;
                            $("#order-address_id").html('');
                            for(var k in address){
                                var v = address[k];
                                $("#order-address_id").append(
                                    '<div class="radio" id="address_'+ v.id +'"><label class="col-sm-7"><input type="radio" value="'+ v.id +'" '
                                    +' name="Order[address_id]"> '
                                    + v.operation_province_name+' '
                                    + v.operation_city_name+' '
                                    + v.operation_area_name+' '
                                    + v.customer_address_detail+' '
                                    + v.customer_address_nickname+' '
                                    + v.customer_address_phone+'</label>' +
                                    '<label class="col-sm-4" style="color: #FF0000;">' +
                                    (v.customer_address_longitude* v.customer_address_latitude==0?'该地址没有匹配到经纬度':'该地址可以下单')+
                                    '</label>' +
                                    '<button class="btn btn-xs btn-warning col-sm-1 update_address_btn" type="button">编辑</button>' +
                                    '</div>'
                                );
                            }
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "/order/customer-used-workers/?id=" + customer.id,
                        dataType: "json",
                        success: function (worker) {
                            if (worker.length>0) {
                                $("#order-order_booked_worker_id").html('<label class="radio-inline"><input type="radio" checked="" value="0" name="Order[order_booked_worker_id]"> 不指定</label>');
                                for(var k in worker){
                                    var v = worker[k];
                                    $("#order-order_booked_worker_id").append(
                                        '<label class="radio-inline"><input type="radio" value="'+ v.worker_id
                                        +'" name="Order[order_booked_worker_id]"> '+ v.worker_name+'</label>'
                                    );
                                }
                            }
                        }
                    });

                    getCards();

                }
            }
        });
    }else{
        $("#address_div").hide();
    }
}


