$(document).ready(function(){
    $('#allcitylist').change(function(){
        allcitylist($(this));
    });
});

function allcitylist(obj){
    var status = obj.is(':checked');
    if(status){
//        $("input[name='citylist[]']").attr("checked",true);
//        $("input[name='citylist[]']").attr("checked","checked");
        $("input[name='citylist[]']").prop('checked',true);
    }else{
//        $("input[name='citylist[]']").attr("checked",false);
//        $("input[name='citylist[]']").attr("checked",false);
        $("input[name='citylist[]']").prop('checked',false);
    }
}