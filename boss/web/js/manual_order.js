/**
 * Created by Administrator on 2015/10/10.
 */
window.onbeforeunload = onbeforeunload_handler;
window.work_status = 1; //1休息 2空闲 3忙碌
window.order_data = new Object();
$('#start_work').click(function(){
    window.work_status = 2;
    $('#work_status').text('空闲');
    $("#work_console").html('<h4 id="get_order" class="col-sm-12">正在分配订单，请稍候……</h4>');
    getWaitManualAssignOrder();
});

function getWaitManualAssignOrder(){
    $.ajax({
        type: "GET",
        url: "/order/get-wait-manual-assign-order",
        dataType:"json",
        success: function (data) {
            if(data){
                window.work_status = 3;
                $('#work_status').text('忙碌');
                window.order_data = data;
                showOrder();
                $("#work_console").hide();
                $("#order_assign").show();
            }else{
                setTimeout(getWaitManualAssignOrder,1000);
            }
        }
    });
}

function onbeforeunload_handler(){
    return "确认退出？";
}
function timer(){
    if( window.work_status == 3) {
        var now = new Date();
        var time = parseInt(now.getTime()/1000);
        $("#create_to_current_time").text(sec2time(time-window.order_data.order.created_at));
        $("#current_to_begin_service_time").text(sec2time(window.order_data.order.order_booked_begin_time-time));
        $("#count_down").text(sec2time(window.order_data.ext_flag.updated_at*1+window.order_data.oper_long_time*1-time));
        setTimeout(timer, 1000);
    }
}

function showOrder(){
    timer();
    var order = window.order_data;
    $("#booked_time_range").text(order.booked_time_range);
    $("#order_address").text(order.order.order_address);
    if(order.ext_pay.order_pay_type==1){
        $("#must_pay_info").text('需收取'+order.order.order_money+'元');
        $("#pay_info").text('总金额'+order.order.order_money+'元');
    }else if(order.ext_pay.order_pay_type==2){
        $("#must_pay_info").text('需收取'+(order.order.order_money - order.ext_pay.order_pay_money - order.ext_pay.order_money -
        order.ext_pay.order_use_acc_balance - order.ext_pay.order_use_acc_balance - order.ext_pay.order_use_card_money -
        order.ext_pay.order_use_promotion_money)+'元');
        $("#pay_info").text('总金额'+order.order.order_money+'元，线上支付'+order.ext_pay.order_pay_money+'元，优惠券'+order.ext_pay.order_money+'元，余额支付'+order.ext_pay.order_use_acc_balance+'元，服务卡支付'+order.ext_pay.order_use_card_money
        +'元，促销金额'+order.ext_pay.order_use_promotion_money+'元');
    }else{
        $("#must_pay_info").text('需收取'+(order.order.order_money-order.ext_pop.order_pop_order_money-order.ext_pop.order_pop_operation_money)+'元');
        $("#pay_info").text('总金额'+order.order.order_money+'元，预付款'+order.ext_pop.order_pop_order_money+'元，渠道运营费'+order.ext_pop.order_pop_operation_money+'元');
    }
    $("#order_customer_need").text('用户需求：'+order.ext_customer.order_customer_need);
    $("#order_customer_memo").text('用户备注：'+order.ext_customer.order_customer_memo);
    $("#order_cs_memo").text('客服备注：'+order.order.order_cs_memo);
}

function sec2time(time){
        var days =0;
        var hours =0;
        var minutes =0;
        var seconds =0;
        if(time >= 86400){
            days = parseInt(time/86400);
            time = time%86400;
        }
        if(time >= 3600){
            hours = parseInt(time/3600);
            time = time%3600;
        }
        if(time >= 60){
            minutes = parseInt(time/60);
            time = time%60;
        }
        seconds = time;
        var t=(days>0)?days+"天":"";
        t+=(hours>0||days>0)?hours+"小时":"";
        t+=(minutes>0||hours>0||days>0)?minutes+"分":"";
        t+= seconds+"秒";
        return t;
}