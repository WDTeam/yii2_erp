$(document).ready(function(){
    $('#start').click(function(){startAutoAssignOrder();});
    $('#connect').click(function(){websocketConnect();});
});

function websocketConnect(){
    var ip = $('#serverip').val();
    var port = $('#serverport').val();
    if(ip != '' && port != ''){
        var wsServer = 'ws://'+ip+':'+port;
        websocket = new WebSocket(wsServer);
        websocket.onopen = function (evt) { 
            console.log("Connected to WebSocket server."+evt.data);
            $('#connect').attr('disabled', true);
            $('#connectStatus').html('连接成功！');
            startAutoAssignOrder();
        }; 
    }
}

function showOrders(data){
    var order = $.parseJSON(data);
    var id = 'order_'+order.order_id;
    var obj = $('#'+id);
    order.status = getStatus(order.status);
    if(order.sms == null){
        order.sms = '未发送';
    }
    if(order.ivr == null){
        order.ivr = '未发送';
    }
    
    if(order.jpush == null){
        order.jpush = '未发送';
    }
    
    if(order.updated_at == null){
        order.updated_at = '';
    }
    if(obj[0] == null){
        var str = '<tr id="'+id+'"><td>'+order.order_id+'</td><td>'+order.status+'</td><td>'+order.sms+'</td><td>'+order.ivr+'</td><td>'+order.jpush+'</td><td>'+order.created_at+'</td><td>'+order.updated_at+'</td></tr>';
        $('#tbody').append(str);
    }else{
        $($('#'+id).children('td')[1]).html(order.status);
        $($('#'+id).children('td')[2]).html(order.sms);
        $($('#'+id).children('td')[3]).html(order.ivr);
        $($('#'+id).children('td')[4]).html(order.jpush);
    }
}

function getStatus(status){
    if(status == null){
        return '正在指派给全职阿姨';
    }else{
        //: （0-5分）: 1，(5-10)：2，已失败转到人工处理：
        switch(status){
            case '1': return '正在指派给全职阿姨';break;
            case '2': return '正在指派给兼职阿姨';break;
            case '1001' : return '已失败转到人工处理'; break;
        }
    }
}

function startAutoAssignOrder(){
    var data = $('#interval').val()+','+$('#taskName').val()+','+$('#theadNum').val()+','+$('#qstart').val()+','+$('#qend').val()+','+$('#jstart').val()+','+$('#jend').val();
    websocket.send(data);
    $('#connectStatus').html('自动派单开始！');
    $('#start').attr('disabled', false);
}


var ip = $('#serverip').val();
var port = $('#serverport').val();
if(ip != '' && port != ''){
    var wsServer = 'ws://'+ip+':'+port;
    var websocket = new WebSocket(wsServer);
    websocket.onopen = function (evt) {
        console.log("Connected to WebSocket server."+evt.data);
        $('#connect').attr('disabled', true);
        $('#connectStatus').html('连接成功！');
    };

    websocket.onclose = function (evt) { 
        console.log("Disconnected"); 
        $('#connectStatus').html('链接断开！');
        $('#connect').attr('disabled', false);
    }; 

    websocket.onmessage = function (evt) {
        console.log('Retrieved data from server: ' + evt.data); 
        showOrders(evt.data);
    };

    websocket.onerror = function (evt, e) {
        console.log('Error occured: ' + evt.data);
        $('#connect').attr('disabled', false);
    };
}
