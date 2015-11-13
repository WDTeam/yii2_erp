/**
 * 用于订单的查看及编辑页面
 */

$(document).ready(function(){
	
	$(".service-info-view").show();
	$(".service-info-edit").hide();

	$(".btn-edit-service-info").click(function(){
		$(".service-info-view").hide();
		$(".service-info-edit").show();
	});

	$('.btn-cancel-service-info').click(function(){
		$(".service-info-view").show();
		$(".service-info-edit").hide();
	});
	
	$(".customer-info-view").show();
	$(".customer-info-edit").hide();

	$('.btn-edit-customer-info').click(function(){
		$(".customer-info-view").hide();
		$(".customer-info-edit").show();
	});

	$(".btn-cancel-customer-info").click(function(){
		$(".customer-info-view").show();
		$(".customer-info-edit").hide();
	});

	//显示编辑
	$(".btn-edit-address-info").click(function(){
		$.ajax({
			type: "GET",
			url: "/order/order/get-address?id=" + $("#address_id").val(),
			dataType: "json",
			success: function (data) {
				if(data) {
					$("#address_form").html(
						'<div class="col-lg-9" style="padding-left:0px;"><div class="input-group"><span class="input-group-addon" id="sizing-addon1">' +
						data.operation_province_name+','+
						data.operation_city_name+','+
						data.operation_area_name+
						'</span><input class="form-control" id="address_detail" value="'+data.customer_address_detail+'">' +
						'<span class="input-group-btn"><button class="btn btn-warning " type="button" id="save_address">保存</button>' +
						'<button class="btn btn-default" type="button" id="cancel_address">取消</button></span></div></div>'
					);
					$("#address_form").show();
					$(".btn-edit-address-info").hide();
					$("#address_static_label").hide();
				}
			}
		});
	});

	$(document).on("click","#cancel_address",function(){
		$(".btn-edit-address-info").show();
		$("#address_static_label").show();
		$("#address_form").hide();
	});

	$(document).on("click","#save_address",function(){
		var address_id = $("#address_id").val();
		var address_detail = $("#address_detail").val();
		var order_code = $("#order-order_code").val();
		//发送数据
		var url = '/order/order/update-order-address?id='+order_code;
		var data = {
			'address_id':address_id,
			'address_detail':address_detail
		};
		$.post(url,data,function(json){
			if(json.status == 1){
				$("#address_static_label").text(json.address);
				$(".btn-edit-address-info").show();
				$("#address_static_label").show();
				$("#address_form").hide();
			}else if(json.status == 2){
				alert(json.address_error);
				$(".btn-edit-address-info").show();
				$("#address_static_label").show();
				$("#address_form").hide();
			}else{
				$(".btn-edit-address-info").show();
				$("#address_static_label").show();
				$("#address_form").hide();
			}
		},'json');
	});

	//保存用户需求
	$(".order_customer_need_save").on('click',function(){
		//获取数据
		var order_code = $("#order-order_code").val();
		var order_customer_memo = $("input[name='Order[order_customer_memo]']").val();
		var order_cs_memo = $("input[name='Order[order_cs_memo]']").val();
		var order_customer_need = [];
		$("input[name='Order[order_customer_need][]']:checked").each(function(){
			order_customer_need.push($(this).val());
		});

		//发送数据
		var url = '/order/order/update-customer-need?id='+order_code;
		var data = {
			'order_customer_memo':order_customer_memo,
			'order_cs_memo':order_cs_memo,
			'order_customer_need':order_customer_need.join(',')
		};
		$.post(url,data,function(json){
			if(json.status == 1){
				$(".customer-info-view").show();
				$(".customer-info-edit").hide();
				$(".order_customer_memo").html(order_customer_memo);
				$(".order_cs_memo").html(order_cs_memo);
				$(".order_customer_need").html(order_customer_need.join(','));
			}
		},'json');

	});


	//保存服务信息
	$(".order_service_info_save").on('click',function()
	{
		var order_booked_date = $("input[name='Order[orderBookedDate]']").val();
		var order_booked_time_range = $("input[name='Order[orderBookedTimeRange]']:checked").val();
		var order_code = $("#order-order_code").val();
		var worker_id = $("input[name='OrderExtWorker[worker_id]']:checked").val();
		//发送数据
		var url = '/order/order/update-booked-time?id='+order_code;
		var data = {
			'worker_id':worker_id,
			'order_booked_date':order_booked_date,
			'order_booked_time_range':order_booked_time_range,
		};
		$.post(url,data,function(json){
			if(json.status == 1){
				$(".service-info-view").show();
				$(".service-info-edit").hide();
				var html = order_booked_date +' '+ order_booked_time_range.split('-')[0];
				html += '~';
				html += order_booked_date +' '+ order_booked_time_range.split('-')[1];
				$(".service_time_html").html(html);
			}
		},'json');
	});

});