/**
 * Created by linhongyou on 2015/9/25.
 */
$("#order-order_customer_phone").blur(function(){
    var phone = $(this).val();
    var reg = /^1[3-9][0-9]{9}$/;
    if(reg.test(phone)) {
        $.ajax({
            type: "GET",
            url: "/order/customer/?phone=" + phone,
            dataType: "json",
            success: function (custormer) {
                if (custormer.id) {
                    $("#order-customer_id").val(custormer.id);
                    $.ajax({
                        type: "GET",
                        url: "/order/customer-address/?id=" + custormer.id,
                        dataType: "json",
                        success: function (address) {
                            if (address.length>0) {
                                $("#order-address_id").html('');
                                for(var k in address){
                                    var v = address[k];
                                    $("#order-address_id").append(
                                        '<div class="radio"><label><input type="radio" value="'+ v.id
                                        +'" '+(v.customer_address_status?'checked="checked"':'')
                                        +' name="Order[address_id]"> '+ v.customer_address_detail+' '
                                        + v.customer_address_nickname+' '
                                        + v.customer_address_phone+'</label></div>'
                                    );
                                    if(v.customer_address_status){
                                        $("#order-order_address").val(v.customer_address_detail+' '+ v.customer_address_nickname+' '+ v.customer_address_phone);
                                    }
                                }
                                $("#address_div").show();
                            }
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "/order/customer-used-workers/?id=" + custormer.id,
                        dataType: "json",
                        success: function (worker) {
                            if (worker.length>0) {
                                $("#order-order_booked_worker_id").html('<div class="radio-inline"><label><input type="radio" checked="" value="0" name="Order[order_booked_worker_id]"> 不指定</label></div>');
                                for(var k in worker){
                                    var v = worker[k];
                                    $("#order-order_booked_worker_id").append(
                                        '<div class="radio-inline"><label><input type="radio" value="'+ v.worker_id
                                        +'" name="Order[order_booked_worker_id]"> '+ v.worker.worker_name+'</label></div>'
                                    );
                                }
                            }
                        }
                    });

                }
            }
        });
    }else{
        $("#address_div").hide();
    }
});
$(document).on("change","#order-address_id input",function(){
    $("#order-order_address").val($("#order-address_id input:checked").parent().text());
});
$("#order-order_service_type_id").change(function(){
    $("#order-order_service_type_name").val($(this).find('option:selected').text());
});
$("#order-order_booked_worker_phone").blur(function(){
    var phone = $(this).val();
    var reg = /^1[3-9][0-9]{9}$/;
    if(reg.test(phone)) {
        $.ajax({
            type: "GET",
            url: "/order/worker/?phone=" + phone,
            dataType: "json",
            success: function (worker) {
                if (worker.id) {
                    $("#order-order_booked_worker_id").html(
                        '<div class="radio-inline"><label><input type="radio" value="'+ worker.id
                        +'" checked="checked" name="Order[order_booked_worker_id]"> '+worker.worker_name+'</label></div>'
                    );
                    $("#order-shop_id").val(worker.shop_id);
                    $("#order-order_worker_type_name").val(worker.worker_rule_id);
                }
            }
        });
    }
});
$("#order-order_booked_count").change(function(){
    $("#order-order_money").val($(this).val()/60*$("#order-order_unit_money").val());
});