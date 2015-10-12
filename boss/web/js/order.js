/**
 * Created by linhongyou on 2015/9/25.
 */
window.coupons = new Array();
window.cards = new Array();
function formatDate(now) {
    var   year=now.getFullYear();
    var   month=now.getMonth()+1;
    var   date=now.getDate();
    var   hour=now.getHours();
    var   minute=now.getMinutes();
    var   second=now.getSeconds();
    if(month<10) month ='0'+month;
    if(date<10) date ='0'+date;
    if(hour<10) hour ='0'+hour;
    if(minute<10) minute ='0'+minute;
    if(second<10) second ='0'+second;
    return   year+"-"+month+"-"+date+" "+hour+":"+minute+":"+second;
}
function strtotime(datetime){
    var tmp_datetime = datetime.replace(/:/g,'-');
    tmp_datetime = tmp_datetime.replace(/ /g,'-');
    var arr = tmp_datetime.split("-");
    var now = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
    return parseInt(now.getTime()/1000);
}
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
                                $("#order-address_id").html('');
                                for(var k in address){
                                    var v = address[k];
                                    $("#order-address_id").append(
                                        '<div class="radio"><label><input type="radio" value="'+ v.id
                                        +'" '+(v.customer_address_status?'checked="checked"':'')
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
                                $("#order-order_booked_worker_id").html('<div class="radio-inline"><label><input type="radio" checked="" value="0" name="Order[order_booked_worker_id]"> 不指定</label></div>');
                                for(var k in worker){
                                    var v = worker[k];
                                    $("#order-order_booked_worker_id").append(
                                        '<div class="radio-inline"><label><input type="radio" value="'+ v.worker_id
                                        +'" name="Order[order_booked_worker_id]"> '+ v.worker_name+'</label></div>'
                                    );
                                }
                            }
                        }
                    });

                    getCoupons();
                    getCards();

                }
            }
        });
    }else{
        $("#address_div").hide();
    }
});


$(document).on("change","#order-order_service_type_id input",function(){getCoupons();});

$(document).on("change","#order-address_id input",function(){
    $("#order-order_address").val($("#order-address_id input:checked").parent().text());
});

$("#order-order_booked_worker_phone").blur(function(){
    var phone = $(this).val();
    var reg = /^1[3-9][0-9]{9}$/;
    if(reg.test(phone)) {
        $.ajax({
            type: "GET",
            url: "/order/worker/?phone=" + phone,
            dataType: "json",
            success: function (worker) {
                if (worker.id) {
                    $("#order-order_booked_worker_id").html(
                        '<div class="radio-inline"><label><input type="radio" value="'+ worker.id
                        +'" checked="checked" name="Order[order_booked_worker_id]"> '+worker.worker_name+'</label></div>'
                    );
                }
            }
        });
    }
});
$("#order-order_booked_count input").change(function(){
    $money = $("#order-order_booked_count input:checked").val()/60*$("#order_unit_money").text();
    $("#order-order_money").val($money.toFixed(2));
    $(".order_money").text($money.toFixed(2));
    $("#order-orderbookedtimerange").html('');
    for(var i=8;i<=18;i++){
        var hour = i<10?'0'+i:i;
        var hourtime = i+$("#order-order_booked_count input:checked").val()/60;
        var hour2 = parseInt(hourtime)<10?'0'+parseInt(hourtime):parseInt(hourtime);
        var minute = (hourtime%1==0)?'00':'30';
        $("#order-orderbookedtimerange").append('<label class="radio-inline"><input type="radio" checked="" value="'+hour+':00-'+hour2+':'+minute+'" name="Order[orderBookedTimeRange]"> '+hour+':00-'+hour2+':'+minute+'</label>');
    }

});

$("#order-order_booked_begin_time,#order-order_booked_count input").change(function(){
    var stringTime = $("#order-order_booked_begin_time").val();
    var timestamp = strtotime(stringTime);
    timestamp = timestamp + $("#order-order_booked_count input:checked").val() * 60;
    $("#order-order_booked_end_time").val(formatDate(new Date(timestamp * 1000)));

});

$('#order-order_pay_type input').change(function(){
        $('[id^=order_pay_type]').hide();
        $('#order_pay_type_'+$('#order-order_pay_type input:checked').val()).show();
});

