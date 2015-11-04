$(document).on("click",".btn-block",function(){
    //alert('asdfasdf');
    var block_reason = $('#block_reason').val();
    if(block_reason == ""){
        $('#block_error').text('不能为空');
        exit;
    }
    if(block_reason.length > 300){
        $('#block_error').text('不能超过300字符');
        exit;
    }
    
    var id = $(':hidden[name=id]').val();
    $.ajax({
        type: "GET",
        url: "/customer/customer/do-add-to-block?block_reason="+ block_reason +"&id=" + id,
        dataType: "json",
        success: function (data) {
            if(data){
                $('div.modal-dialog').remove();
                alert('封号成功');
                location.href = "/customer/customer/index";
            }else{
                alert('封号失败');
            }
        }
    });
});




