/**
 * Created by Administrator on 2015/10/10.
 */
$('#start_work').click(function(){
    $(this).hide();
    $("#get_order").show();
    setTimeout(function(){
        $("#get_order").hide();
        $("#order_assign").show();
    },5000);
});