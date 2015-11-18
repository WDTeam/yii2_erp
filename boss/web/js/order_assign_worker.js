/**
 * Created by Administrator on 2015/10/10.
 */
var refuse_worker_id = 0;
var cancel_worker_id = 0;
$(document).on("click",'#can_not_assign',function(){
    if(confirm('您确认无法指派此订单？')){
        canNotAssign();
    }
});

$(document).on("click",'.worker_assign',function(){
    if(confirm('您确认此阿姨接单？')){

        var worker_id = $(this).parents('tr').find('input').val();
        $.ajax({
            type: "POST",
            url:  "/order/order/do-assign",
            data: "order_id="+$("#order_id").val()+"&worker_id="+worker_id,
            dataType:"json",
            success: function (msg) {
                if(msg.status){
                   history.back();
                }else{
                    alert('指派失败！');
                }
            }
        });
    }
});

$(document).on("click",'.worker_assign_cancel',function(){
    cancel_worker_id = $(this).parents('tr').find('input').val();
});

$(document).on("click",'#worker_cancel_memo_submit',function(){
    var memo = $("#worker_cancel_memo").val();
    if(memo.length<=0){
        alert('取消指派原因不能为空！');
    }else{
        $.ajax({
            type: "POST",
            url:  "/order/order/worker-cancel",
            data: "order_id="+$("#order_id").val()+"&worker_id="+cancel_worker_id+"&memo="+encodeURIComponent(memo),
            dataType:"json",
            success: function (msg) {
                if(msg){
                    $("#worker_memo_"+refuse_worker_id).text('已取消指派：'+memo);
                    $("#worker_status_"+refuse_worker_id).text('取消指派');
                    $("#worker_cancel_modal .close").click();
                }
            }
        });
    }
});



$(document).on("click",'.worker_refuse',function(){
    refuse_worker_id = $(this).parents('tr').find('input').val();
});



$(document).on("click",'.worker_contact_failure',function(){
    var worker_id = $(this).parents('tr').find('input').val();
    $.ajax({
        type: "POST",
        url:  "/order/order/worker-contact-failure",
        data: "order_id="+$("#order_id").val()+"&worker_id="+worker_id,
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
            data: "order_id="+$("#order_id").val()+"&worker_id="+refuse_worker_id+"&memo="+encodeURIComponent(memo),
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
            url = "/order/order/search-assign-worker?order_id="+$("#order_id").val()+"&phone=" + $param;
        } else {
            url = "/order/order/search-assign-worker?order_id="+$("#order_id").val()+"&worker_name=" + $param;
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
    history.back();
}

function getCanAssignWorkerList(){
    $.ajax({
        type: "GET",
        url: "/order/order/get-can-assign-worker-list?order_id="+$("#order_id").val(),
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
            }else if(data.code==500){
                alert(data.msg);
            }
        }

    });
}
getCanAssignWorkerList();
