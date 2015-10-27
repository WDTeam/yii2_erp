$(document).ready(function(){
    $('#operationSpec').change(function(){operationSpec($(this))});
});

function operationSpec(obj){
/*    index = obj.get(0).selectedIndex;*/
    var value = obj.val();
    if(value){
        var url = '/operation/operation-goods/getspecinfo';
        var data = {'spec_id': value};
        $.post(url, data, function(t){
            $('#SpecInfo').html(t);
        }, 'html');
    }   
}
