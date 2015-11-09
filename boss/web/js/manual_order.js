/**
 * Created by Administrator on 2015/10/10.
 */
window.onbeforeunload = onbeforeunload_handler;
window.work_status = 1; //1休息 2空闲 3忙碌 4小休
window.order_data = new Object();
window.continue_work_count_down = 10;
window.count_down_flag = true; //倒计时结束后标记false代表已经处理订单
var refuse_worker_id = 0;
$(document).on("click",'#start_work,#continue_work',function(){
    alert(1);
    window.work_status = 2;
    $('#work_status').text('空闲');
    $("#work_console").html('<h4 id="get_order" class="col-sm-12">正在分配订单，请稍候……</h4>');
    getWaitManualAssignOrder();
    $('#startId').click();
});


$(document).on("click",'#pause_work',function(){
    window.work_status = 4;
    $('#work_status').text('小休');
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

$(document).on("click",'.worker_assign',function(){
    if(confirm('您确认此阿姨接单？')){

        refuse_worker_id = $(this).parents('tr').find('input').val();
        $.ajax({
            type: "POST",
            url:  "/order/order/do-assign",
            data: "order_id="+window.order_data.order.id+"&worker_id="+refuse_worker_id,
            dataType:"json",
            success: function (msg) {
                if(msg.status){
                    window.continue_work_count_down = 10;
                    $("#work_console").html(
                        '<button id="stop_work" class="btn btn-warning" type="button">收工啦</button>' +
                        '<button id="pause_work" class="btn btn-warning" type="button">休息</button>' +
                        '<button id="continue_work" class="btn btn-warning" type="button">继续（'+window.continue_work_count_down+'s）</button>'
                    );
                    $("#order_assign").hide();
                    $("#work_console").show();
                }else{
                    alert('指派失败！');
                }
            }
        });
    }
});

$(document).on("click",'.worker_refuse',function(){
    refuse_worker_id = $(this).parents('tr').find('input').val();
});

$(document).on("click",'.worker_contact_failure',function(){
    refuse_worker_id = $(this).parents('tr').find('input').val();
    $.ajax({
        type: "POST",
        url:  "/order/order/worker-contact-failure",
        data: "order_id="+window.order_data.order.id+"&worker_id="+refuse_worker_id,
        dataType:"json",
        success: function (msg) {
            if(msg){
                $("#worker_memo_"+refuse_worker_id).text('人工指派未响应');
                $("#worker_status_"+refuse_worker_id).text('人工指派未响应');
            }
        }
    });
});

$(document).on("click",'#worker_refuse_memo_submit',function(){
    var memo = $("#worker_refuse_modal input[name=worker_refuse_memo]:checked").val();
    if(memo==0) memo = $("#worker_refuse_memo_other").val();
    if(memo.length<=0){
        alert('拒绝原因不能为空！');
    }else{
        $.ajax({
            type: "POST",
            url:  "/order/order/worker-refuse",
            data: "order_id="+window.order_data.order.id+"&worker_id="+refuse_worker_id+"&memo="+encodeURIComponent(memo),
            dataType:"json",
            success: function (msg) {
                if(msg){
                    $("#worker_memo_"+refuse_worker_id).text('已拒单：'+memo);
                    $("#worker_status_"+refuse_worker_id).text('人工指派拒单');
                    $("#worker_refuse_modal .close").click();
                }
            }
        });
    }
});

$(document).on("click",'#worker_search_submit',function(){
    $param = $("#worker_search_input").val();
    if($param != '') {
        var reg = /^1[3-9][0-9]{9}$/;
        var url = '';
        if (reg.test($param)) {
            url = "/order/order/search-assign-worker?order_id="+window.order_data.order.id+"&phone=" + $param;
        } else {
            url = "/order/order/search-assign-worker?order_id="+window.order_data.order.id+"&worker_name=" + $param;
        }
        $.ajax({
            type: "GET",
            url: url,
            dataType:"json",
            success: function (data) {
                if(data.code==200){
                    for(var k in data.data){
                        var v = data.data[k];
                        $("#worker_list tbody").prepend('<tr>'+
                        '<td><input type="hidden" value="'+ v.id+'" /><a href="/worker/view/'+ v.id+'" target="_blank">'+ v.worker_name+'</a></td>'+
                        '<td>'+ v.worker_phone+'</td>'+
                        '<td>'+ v.shop_name+'</td>'+
                        '<td>'+ v.worker_identity_description+'</td>'+
                        '<td>'+ v.order_booked_time_range.join('<br/>')+'</td>'+
                        '<td>'+ v.worker_stat_order_refuse_percent+'</td>'+
                        '<td>'+ v.tag+'</td>'+
                        '<td id="worker_status_'+ v.id+'">'+ v.status.join(',')+'</td>'+
                        '<td id="worker_memo_'+ v.id+'">'+ (v.memo.length>0?v.memo.join(','):'<a href="javascript:void(0);" class="worker_assign">派单</a> <a href="javascript:void(0);" data-toggle="modal" data-target="#worker_refuse_modal" class="worker_refuse">拒单</a> <a href="javascript:void(0);" class="worker_contact_failure">未接通</a>')+'</td>'+
                        '</tr>');
                    }
                }else{
                    alert(data.msg);
                }
            }

        });
    }
});



function canNotAssign(){
    window.count_down_flag = false;
    $.ajax({
        type: "GET",
        url: "/order/order/can-not-assign?order_id="+window.order_data.order.id,
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
        url: "/order/order/get-wait-manual-assign-order",
        dataType:"json",
        success: function (data) {
            if(data){
                window.count_down_flag = true;
                window.work_status = 3;
                $('#work_status').text('忙碌');
                window.order_data = data;
                for(var k in data.booked_workers){
                    var v = data.booked_workers[k];
                    $("#worker_list thead").append('<tr>'+
                        '<td><input type="hidden" value="'+ v.id+'" /><a href="/worker/view/'+ v.id+'" target="_blank">'+ v.worker_name+'</a></td>'+
                        '<td>'+ v.worker_phone+'</td>'+
                        '<td>'+ v.shop_name+'</td>'+
                        '<td>'+ v.worker_identity_description+'</td>'+
                        '<td>'+ v.order_booked_time_range.join('<br/>')+'</td>'+
                        '<td>'+ v.worker_stat_order_refuse_percent+'</td>'+
                        '<td>'+ v.tag+'</td>'+
                        '<td id="worker_status_'+ v.id+'">'+ v.status.join(',')+'</td>'+
                        '<td id="worker_memo_'+ v.id+'">'+ (v.memo.length>0?v.memo.join(','):'<a href="javascript:void(0);" class="worker_assign">派单</a> <a href="javascript:void(0);" data-toggle="modal" data-target="#worker_refuse_modal" class="worker_refuse">拒单</a> <a href="javascript:void(0);" class="worker_contact_failure">未接通</a>')+'</td>'+
                        '</tr>');
                }
                getCanAssignWorkerList();
                showOrder();
                $("#work_console").hide();
                $("#order_assign").show();
                $('#waitId').click();
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
        url: "/order/order/get-can-assign-worker-list?order_id="+window.order_data.order.id,
        dataType:"json",
        success: function (data) {
            if(data.code==200){
                $("#worker_list tbody").html('');
                for(var k in data.data){
                    var v = data.data[k];
                    $("#worker_list tbody").append('<tr>'+
                    '<td><input type="hidden" value="'+ v.id+'" /><a href="/worker/view/'+ v.id+'" target="_blank">'+ v.worker_name+'</a></td>'+
                    '<td>'+ v.worker_phone+'</td>'+
                    '<td>'+ v.shop_name+'</td>'+
                    '<td>'+ v.worker_identity_description+'</td>'+
                    '<td>'+ v.order_booked_time_range.join('<br/>')+'</td>'+
                    '<td>'+ v.worker_stat_order_refuse_percent+'</td>'+
                    '<td>'+ v.tag+'</td>'+
                    '<td id="worker_status_'+ v.id+'">'+ v.status.join(',')+'</td>'+
                    '<td id="worker_memo_'+ v.id+'">'+ (v.memo.length>0?v.memo.join(','):'<a href="javascript:void(0);" class="worker_assign">派单</a> <a href="javascript:void(0);" data-toggle="modal" data-target="#worker_refuse_modal" class="worker_refuse">拒单</a> <a href="javascript:void(0);" class="worker_contact_failure">未接通</a>')+'</td>'+
                    '</tr>');
                }
            }else{
                alert(data.msg);
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
        var count_down = window.order_data.ext_flag.order_flag_lock_time*1+window.order_data.operation_long_time*1-time;
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
    $("#booked_time_range").html(order.booked_time_range);
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
    $("#order_check_worker").text('是否可更换阿姨：'+(order.ext_flag.order_flag_check_booked_worker ? '是' : '否' ));
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