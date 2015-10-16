/**
 * Created by linhongyou on 2015/9/25.
 */
window.coupons = new Array();
window.cards = new Array();
var address_list = new Array();
var goods_list = new Array();


function getCoupons(){
    if(window.customer != undefined) {
        $.ajax({
            type: "GET",
            url: "/order/coupons/?id=" + window.customer.id+"&service_id="+$('#order-order_service_type_id input:checked').val(),
            dataType: "json",
            success: function (coupons) {
                if (coupons.length > 0) {
                    $("#order-coupon_id").html('');
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
$("#order-order_customer_phone").blur(function(){
    var phone = $(this).val();
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
                            if (address.length>0) {
                                address_list = address;
                                $("#order-address_id").html('');
                                for(var k in address){
                                    var v = address[k];
                                    $("#order-address_id").append(
                                        '<div class="radio"><label><input type="radio" value="'+ v.id +'" '
                                        +' name="Order[address_id]"> '+ v.customer_address_detail+' '
                                        + v.customer_address_nickname+' '
                                        + v.customer_address_phone+'</label></div>'
                                    );
                                    if(v.customer_address_status){
                                        $("#order-order_address").val(v.customer_address_detail+' '+ v.customer_address_nickname+' '+ v.customer_address_phone);
                                    }
                                }
                                $("#address_div").show();
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
});


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

$(document).on("change","#order-address_id input",function(){
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


