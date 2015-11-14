$(document).ready(function(){
    $('#start').click(function(){execCommand(1);});
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
            $('#connectStatus').html('<font color="#41A317">连接成功！</font>');
            if ($('#srvIsSuspend').val()==true)
            {
                $('#start').html('开始智能派单');
                $('#start').attr('disabled', true);
                $('#connectStatus').html('<font color="#41A317">连接成功，</font><font color="#FF0000">智能派单已暂停！</font>');
                getCurrentOrders(0);
            }else{
                $('#start').attr('disabled', false);
                $('#start').html('停止智能派单');
                $('#connectStatus').html('<font color="#41A317">连接成功，智能派单已启动！</font>');
            }
        };
        websocket.onclose = function (evt) {
            console.log("Disconnected");
            $('#connectStatus').html('<font color="#FF0000">链接已断开！</font>');
            $('#start').html('开始智能派单');
            $('#start').attr('disabled', true);
        };

        websocket.onmessage = function (evt) {
            console.log('Retrieved data from server: ' + evt.data);
            var msg = $.parseJSON(evt.data);
            var srv_continue = 1;
            var srv_suspend = 2;
            if( msg == srv_continue){
                $('#connectStatus').html('<font color="#41A317">连接成功，智能派单已启动！</font>');
            }else if(msg == srv_suspend)
            {
                $('#connectStatus').html('<font color="#41A317">连接成功，</font><font color="#FF0000">智能派单已暂停！</font>');
            }else if(msg == "Assign Server is OK"){

            }else{
                showOrders(evt.data);
            }
        };

        websocket.onerror = function (evt, e) {
            console.log('Error occured: ' + evt.data);
            $('#connectStatus').html('<font color="#FF0000">连接错误，请检查网络环境是否正常！</font>');
            $('#start').html('开始智能派单');
            $('#start').attr('disabled', true);
        };
    }
}

function showOrders(data){
    var order = $.parseJSON(data);
    order = eval('(' + order + ')');
    if (order.order_code==null || order.order_code=='')
    {
        return;
    }
    var id = 'order_'+order.order_code;
    var obj = $('#'+id);
    order.status = getStatus(order.status);
    if(order.ivr > 0){
        order.ivr = '已发送';
    }else{
        order.ivr = '未发送';
    }
    
    if(order.jpush > 0 ){
        order.jpush = '已发送';
    }else{
        order.jpush = '未发送';
    }
    
    if(order.updated_at == null){
        order.updated_at = '';
    }
    if(obj[0] == null){
        var str = '<tr id="'+id+'"><td>'+order.order_code+'</td><td>'+order.status+'</td><td>'+order.ivr+'</td><td>'+order.jpush+'</td><td>'+order.created_at+'</td><td>'+order.updated_at+'</td></tr>';
        $('#tbody').append(str);
    }else{
        $($('#'+id).children('td')[1]).html(order.status);
        $($('#'+id).children('td')[2]).html(order.ivr);
        $($('#'+id).children('td')[3]).html(order.jpush);
        $($('#'+id).children('td')[4]).html(order.created_at);
        $($('#'+id).children('td')[5]).html(order.updated_at);
    }
}

function getStatus(status){
    if(status == null){
        return '正在指派给全职阿姨';
    }else{
        //: （0-5分）: 1，(5-10)：2，已失败转到人工处理：1001
        switch(status){
            case 1: return '正在指派给全职阿姨';break;
            case 2: return '正在指派给兼职阿姨';break;
            case 1001 : return '系统派单失败转人工处理'; break;
        }
    }
}

function getCurrentOrders(cmd){
    websocket.send(cmd);
}

function execCommand(cmd){
    var data = cmd ;
    if($('#start').html() == '开始智能派单'){
        $('#start').html('停止智能派单');
    }else if($('#start').html() == '停止智能派单'){
        $('#start').html('开始智能派单');
    }
    websocket.send(data);
}