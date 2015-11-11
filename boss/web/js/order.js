/**
 * Created by linhongyou on 2015/9/25.
 */
var coupon = new Object();
window.cards = new Array();
var customer = new Object();
var address_list = new Object();
var goods_list = new Object();
var district_id = 0;

var progress = '<div class="progress"> <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div> </div>';

$("#order-order_customer_phone").keyup(function(e){
    var phone = $(this).val();
    var reg = /^1[3-9][0-9]{9}$/;
    if(reg.test(phone)) {
        getCustomerInfo();
    }
});

$("#order-order_customer_phone").blur(getCustomerInfo);


$("#order_create_form").submit(function(){
    if($("#order-pay_channel_id input:checked").val()==20 && parseFloat(customer.customer_balance)-parseFloat($(".order_pay_money").text()) < 0){
        alert("用户余额不足以支付此次订单！");
        return false;
    }
});


$(document).on("change","#order-order_service_item_id input",function(){
    var goods = goods_list[$("#order-order_service_item_id input:checked").val()];
    var unit_price = parseFloat(goods.operation_shop_district_goods_price);
    $("#order_unit_money").text(unit_price.toFixed(2));
    $("#order-order_unit_money").val(unit_price.toFixed(2));
    setOrderMoney();
});



$(document).on("change","#order-address_id input[type='radio']",function(){
    $("#order-order_address").val($("#order-address_id input:checked").parent().text());
    getGoods();//地址信息变更后去获取商品信息
});

$(document).on("change","#order-order_booked_count input",function(){
    setOrderMoney();
    getTimeRange();
});
$(document).on('click','.day',function(){getTimeRange();});


$('#order-channel_id input').change(function(){
        $('[id^=order_pay_channel_]').hide();
        if($(this).val()==20){
            $('#order_pay_channel_1').show();
            $('#order_pay_type_1').show();
        }else{
            $('#order_pay_channel_2').show();
            $('#order_pay_type_2').show();
        }
});
$('#order-pay_channel_id input').change(function(){
        if($(this).val()==20){
            $('#order_coupon_code').show();
        }else{
            $('#order_coupon_code').hide();
        }
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

$(document).on("blur","#order-order_coupon_code",checkCoupon);

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
            //+ v.customer_address_nickname + ' '
            //+ v.customer_address_phone
            + '</label>' +
            '<label class="col-sm-4" style="color: #FF0000;">' +
            (v.customer_address_longitude * v.customer_address_latitude == 0 ? '该地址没有匹配到经纬度' : '该地址可下单') +
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
    if(nickname==''){
        nickname = '客户';
    }
    if(phone == ''){
        phone = $("#order-order_customer_phone").val();
    }
    var customer_id = $('#order-customer_id').val();
    if(address_id==0 && customer_id==''){
        alert('请先选择客户再添加地址！');
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/order/order/save-address/?address_id=" + address_id,
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
                    //+ v.customer_address_nickname + ' '
                    //+ v.customer_address_phone
                    + '</label>' +
                    '<label class="col-sm-4" style="color: #FF0000;">' +
                    (v.customer_address_longitude * v.customer_address_latitude == 0 ? '该地址没有匹配到经纬度' : '该地址可下单') +
                    '</label>' +
                    '<button class="btn btn-xs btn-warning col-sm-1 update_address_btn" type="button">编辑</button>'
                );
            }
        }
    });
});


function getTimeRange()
{
    $("#order-orderbookedtimerange").html(progress);
    setTimeout(function(){$("#order-orderbookedtimerange .progress-bar").css("width","100%");},100);

    $.ajax({
        type: "GET",
        url: "/order/order/get-time-range-list/?order_booked_count=" + $("#order-order_booked_count input:checked").val()+"&district_id="+district_id+"&date="+$("#order-orderbookeddate").val(),
        dataType: "json",
        success: function (data) {
            $("#order-orderbookedtimerange").html('');
            for(var key in data) {
                var val = data[key];
                for (var k in val.timeline) {
                    var v = val.timeline[k];
                    var disabled_class = v.enable?'':'disabled';
                    var disabled = v.enable?'':'disabled="disabled"';
                    $("#order-orderbookedtimerange").append('<label class="radio-inline '+disabled_class+'"><input '+disabled+' type="radio"  value="' + v.time + '" name="Order[orderBookedTimeRange]"> ' + v.time + '</label>');
                }
            }
        }
    });
}


function getCity(province_id,address_id,city_id,county_id)
{
    $.ajax({
        type: "GET",
        url: "/order/order/get-city/?province_id=" + province_id,
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
        url: "/order/order/get-county/?city_id=" + city_id,
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
    var money = $("#order-order_booked_count input:checked").val()*$("#order_unit_money").text();
    $("#order-order_money").val(money.toFixed(2));
    $(".order_money").text(money.toFixed(2));
    $(".order_pay_money").text(money.toFixed(2));
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
    $("#order-order_service_item_id").html(progress);
    setTimeout(function(){$("#order-order_service_item_id .progress-bar").css("width","100%");},100);
    $.ajax({
        type: "GET",
        url: "/order/order/get-goods/?lng=" + lng + "&lat=" + lat,
        dataType: "json",
        success: function (data) {
            $("#order-order_service_item_id").html('');
            if(data.code==200){
                district_id = data.district_id;
                for(var k in data.data){
                    var v = data.data[k];
                    goods_list[v.operation_goods_id]=v;
                    $("#order-order_service_item_id").append(
                        '<label class="radio-inline"><input type="radio" value="'+ v.operation_goods_id
                        +'" name="Order[order_service_item_id]"> '+ v.operation_shop_district_goods_name+'</label>'
                    );
                }
            }else{
                $("#order-order_service_item_id").html('<p style="font-size: 14px;" class="form-control-static">'+data.msg+'</p>');
            }
        }
    });


}

function getCards(){
    if(customer.id != undefined) {
        $.ajax({
            type: "GET",
            url: "/order/order/cards/?id=" + customer.id,
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

function checkCoupon(){
    if($("#order-order_service_item_id input:checked").length>0) {
        var service_item_id = $("#order-order_service_item_id input:checked").val();
        var service_type_id = goods_list[service_item_id].operation_category_id;
        var address_id = $("#order-address_id input:checked").val();
        var city_id = address_list[address_id].operation_city_id;
        $.ajax({
            type: "GET",
            url: "/order/order/check-coupon-code/?coupon_code=" + $("#order-order_coupon_code").val() + "&customer_phone=" + $("#order-order_customer_phone").val() + "&service_item_id=" + service_item_id + "&service_type_id=" + service_type_id + "&city_id=" + city_id,
            dataType: "json",
            success: function (data) {
                if (!data) {
                    alert('该优惠码与此次服务不匹配！');
                } else {
                    coupon = data
                    $("#order-coupon_id").val(coupon.id);
                    var order_pay_money = $("#order-order_money").val() - coupon.coupon_userinfo_price;
                    $(".order_pay_money").text(order_pay_money.toFixed(2));
                }
            }
        });
    }
}

function getCustomerInfo(){
    var phone = $("#order-order_customer_phone").val();
    var reg = /^1[3-9][0-9]{9}$/;
    if(reg.test(phone) && (customer.customer_phone==undefined || phone!=customer.customer_phone)) {
        $("#order-address_id").html(progress);
        $.ajax({
            type: "GET",
            url: "/order/order/customer/?phone=" + phone,
            dataType: "json",
            success: function (data) {
                $("#order-address_id .progress-bar").css("width","100%");
                customer=data;
                if (customer.id) {
                    $("#order-customer_id").val(customer.id);
                    $.ajax({
                        type: "GET",
                        url: "/order/order/customer-address/?id=" + customer.id,
                        dataType: "json",
                        success: function (address) {
                            $("#order-address_id").html('');
                            if (address.length==0) {
                                $("#add_address_btn").click();
                            } else {
                                address_list = address;
                                for (var k in address) {
                                    var v = address[k];
                                    $("#order-address_id").append(
                                        '<div class="radio" id="address_' + v.id + '"><label class="col-sm-7"><input type="radio" value="' + v.id + '" '
                                        + ' name="Order[address_id]"> '
                                        + v.operation_province_name + ' '
                                        + v.operation_city_name + ' '
                                        + v.operation_area_name + ' '
                                        + v.customer_address_detail + ' '
                                        //+ v.customer_address_nickname + ' '
                                        //+ v.customer_address_phone
                                        + '</label>' +
                                        '<label class="col-sm-4" style="color: #FF0000;">' +
                                        (v.customer_address_longitude * v.customer_address_latitude == 0 ? '该地址没有匹配到经纬度' : '该地址可下单') +
                                        '</label>' +
                                        '<button class="btn btn-xs btn-warning col-sm-1 update_address_btn" type="button">编辑</button>' +
                                        '</div>'
                                    );
                                }
                            }
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "/order/order/customer-used-workers/?id=" + customer.id,
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


