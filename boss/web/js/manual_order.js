/**
 * Created by Administrator on 2015/10/10.
 */
window.onbeforeunload = onbeforeunload_handler;
window.work_status = 1; //1休息 2空闲 3忙碌
window.order_data = new Object();
window.continue_work_count_down = 10;
window.count_down_flag = true; //倒计时结束后标记false代表已经处理订单
$(document).on("click",'#start_work,#continue_work',function(){
    window.work_status = 2;
    $('#work_status').text('空闲');
    $("#work_console").html('<h4 id="get_order" class="col-sm-12">正在分配订单，请稍候……</h4>');
    getWaitManualAssignOrder();
});


$(document).on("click",'#pause_work',function(){
    window.work_status = 1;
    $('#work_status').text('休息');
    $("#work_console").html(
        '<button id="stop_work" class="btn btn-warning" type="button">收工啦</button>' +
        '<button id="continue_work" class="btn btn-warning" type="button">继续</button>'
    );
});

$(document).on("click",'#stop_work',function(){
    window.work_status = 1;
    $('#work_status').text('休息');
    $("#work_console").html(
        '<button id="start_work" class="btn btn-warning" type="button">开工啦</button>'
    );
});


$(document).on("click",'#can_not_assign',function(){
    if(confirm('您确认无法指派此订单？')){
        canNotAssign();
    }
});

function canNotAssign(){
    window.count_down_flag = false;
    $.ajax({
        type: "GET",
        url: "/order/can-not-assign?order_id="+window.order_data.order.id,
        dataType:"json",
        success: function (msg) {
            if(!msg) {
                alert('无法指派状态修改失败！请与系统管理员联系！');
            }
            window.continue_work_count_down = 10;
            $("#work_console").html(
                '<button id="stop_work" class="btn btn-warning" type="button">收工啦</button>' +
                '<button id="pause_work" class="btn btn-warning" type="button">休息</button>' +
                '<button id="continue_work" class="btn btn-warning" type="button">继续（'+window.continue_work_count_down+'s）</button>'
            );
            $("#order_assign").hide();
            $("#work_console").show();

        }
    });
}

function getWaitManualAssignOrder(){
    $.ajax({
        type: "GET",
        url: "/order/get-wait-manual-assign-order",
        dataType:"json",
        success: function (data) {
            if(data){
                window.count_down_flag = true;
                window.work_status = 3;
                $('#work_status').text('忙碌');
                window.order_data = data;
                getCanAssignWorkerList();
                showOrder();
                $("#work_console").hide();
                $("#order_assign").show();
            }else{
                setTimeout(getWaitManualAssignOrder,3000);
            }
        },
        error: function($msg){
            setTimeout(getWaitManualAssignOrder,10000);
        }
    });
}

function getCanAssignWorkerList(){
    $.ajax({
        type: "GET",
        url: "/order/get-can-assign-worker-list?order_id="+window.order_data.order.id,
        dataType:"json",
        success: function (data) {
            if(data){
                $("#worker_list tbody").html('');
                for(var k in data){
                    var v = data[k];
                    $("#worker_list tbody").append('<tr>'+
                    '<td><a href="/worker/view/'+ v.id+'" target="_blank">'+ v.worker_name+'</a></td>'+
                    '<td>'+ v.worker_phone+'</td>'+
                    '<td>'+ v.shop_name+'</td>'+
                    '<td>'+ v.worker_rule_description+'</td>'+
                    '<td>'+ v.order_booked_time_range.join('<br/>')+'</td>'+
                    '<td>'+ v.worker_stat_order_refuse_percent+'</td>'+
                    '<td>'+ v.tag+'</td>'+
                    '<td></td>'+
                    '<td><a href="javascript:void(0);" class="worker_contacted" >已联系</a></td>'+
                    '</tr>');
                }
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
        var count_down = window.order_data.ext_flag.updated_at*1+window.order_data.oper_long_time*1-time;
        $("#count_down").text(sec2time(count_down));
        if(count_down<=0 && window.count_down_flag){
            canNotAssign();
        }
        if($("#work_console").css('display')=='block'){
            window.continue_work_count_down--;
            $("#continue_work").text('继续（'+window.continue_work_count_down+'s）');
            if(window.continue_work_count_down<=0){
                $("#continue_work").click();
            }
        }
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