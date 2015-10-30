$(document).ready(function(){
    $('#start').attr('disabled', true);
    $('#stop').attr('disabled', true);
    $('#reload').attr('disabled', true);
    $('#update').attr('disabled', true);
    $('#runService').hide();
    
    $('#qend').blur(function(){ $('#jstart').val( $('#qend').val());});
    $('#start').click(function(){execCommand(1);});
    $('#stop').click(function(){execCommand(2);});
    $('#reload').click(function(){execCommand(3);});
    $('#update').click(function(){execCommand(4);});
    $('#connect').click(function(){websocketConnect();});
    $('#connectStatus').html('正在连接派单服务器...');
    $('#connect').click();
});

function websocketConnect() {
    var ip = $('#serverip').val();
    var port = $('#serverport').val();
    if (ip != '' && port != '') {
        wsServer = 'ws://' + ip + ':' + port;
        websocket = new WebSocket(wsServer);
        websocket.onopen = function (evt) {
            console.log("Connected to WebSocket server." + evt.data);
            $('#connectStatus').html('连接成功！');
            $('#connect').attr('disabled', true);
            $('#runService').hide();
            $('#start').attr('disabled', false);
            $('#stop').attr('disabled', false);
            $('#reload').attr('disabled', false);
            $('#update').attr('disabled', false);
        };
        websocket.onclose = function (evt) {
            console.log("Disconnected");
            $('#connectStatus').html('链接断开！请检查服务器地址是否正确，或被网络防火墙禁止访问');
            $('#connect').attr('disabled', false);
            $('#runService').show();
            $('#start').attr('disabled', true);
            $('#stop').attr('disabled', true);
            $('#reload').attr('disabled', true);
            $('#update').attr('disabled', true);
        };

        websocket.onmessage = function (evt) {
            console.log('Retrieved data from server: ' + evt.data);
            showOrders(evt.data);
        };

        websocket.onerror = function (evt, e) {
            console.log('Error occured: ' + evt.data);
            $('#connectStatus').html('连接错误，请检查网络环境是否正常！');
            $('#connect').attr('disabled', false);
            $('#runService').show();
            $('#start').attr('disabled', true);
            $('#stop').attr('disabled', true);
            $('#reload').attr('disabled', true);
            $('#update').attr('disabled', true);
        };
    }
}

function showOrders(data){
    var order = $.parseJSON(data);
    if (order.order_id==null || order.order_id=='')
    {
        return;
    }
    var id = 'order_'+order.order_id;
    var obj = $('#'+id);
    order.status = getStatus(order.status);
    if(order.sms == true){
        order.sms = '已发送';
    }else{
        order.sms = '已发送';
    }
    if(order.ivr == true){
        order.ivr = '已发送';
    }else{
        order.ivr = '未发送';
    }
    
    if(order.jpush == true){
        order.jpush = '已发送';
    }else{
        order.jpush = '未发送';
    }
    
    if(order.updated_at == null){
        order.updated_at = '';
    }
    if(obj[0] == null){
        var str = '<tr id="'+id+'"><td>'+order.order_id+'</td><td>'+order.status+'</td><td>'+order.ivr+'</td><td>'+order.jpush+'</td><td>'+order.created_at+'</td><td>'+order.updated_at+'</td></tr>';
        $('#tbody').append(str);
    }else{
        $($('#'+id).children('td')[1]).html(order.status);
        $($('#'+id).children('td')[2]).html(order.ivr);
        $($('#'+id).children('td')[3]).html(order.jpush);
    }
}

function getStatus(status){
    if(status == null){
        return '正在指派给全职阿姨';
    }else{
        //: （0-5分）: 1，(5-10)：2，已失败转到人工处理：1001
        switch(status){
            case '1': return '正在指派给全职阿姨';break;
            case '2': return '正在指派给兼职阿姨';break;
            case '1001' : return '系统派单失败转人工处理'; break;
        }
    }
}

function execCommand(cmd){
    var data = cmd +','+$('#qstart').val()+','+$('#qend').val()+','+$('#jstart').val()+','+$('#jend').val()+','+status;
    websocket.send(data);
    $('#connectStatus').html('自动派单开始！');
    $('#start').attr('disabled', false);
}