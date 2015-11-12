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

	$(".btn-cancel-service-info").click(function(){
		$(".service-info-view").show();
		$(".service-info-edit").hide();
	});
	
	$(".customer-info-view").show();
	$(".customer-info-edit").hide();
	
	$(".btn-edit-customer-info").click(function(){
		$(".customer-info-view").hide();
		$(".customer-info-edit").show();
	});

	$(".btn-cancel-customer-info").click(function(){
		$(".customer-info-view").show();
		$(".customer-info-edit").hide();
	});

	//显示编辑
	$(".address-edit-btn").on('click',function(){
		$(".address-save-btn").show();
		$(".address-edit-btn").hide();
	});

	//显示文本
	$(".btn_cancel_address-info").on('click',function(){
		$(".address-info-view").show();
		$(".address-info-edit").hide();
	});

	//保存用户需求
	$(".order_customer_need_save").on('click',function(){
		//获取数据
		var id = $("input[name='Order[id]']").val();
		var order_customer_memo = $("input[name='Order[order_customer_memo]']").val();
		var order_cs_memo = $("input[name='Order[order_cs_memo]']").val();
		var order_customer_need = '';
		$("input[name='Order[order_customer_need][]']:checked").each(function(){
			order_customer_need += $(this).val() + ',';
		});
		order_customer_need = order_customer_need.substring(0,order_customer_need.length-1);

		//发送数据
		var url = '/order/order/modify';
		var data = {
			'id':id,
			'order_customer_memo':order_customer_memo,
			'order_cs_memo':order_cs_memo,
			'order_customer_need':order_customer_need
		};
		$.post(url,data,function(json){
			if(json.status == 1){
				$(".customer-info-view").show();
				$(".customer-info-edit").hide();
				$(".order_customer_memo").html(order_customer_memo);
				$(".order_cs_memo").html(order_cs_memo);
				$(".order_customer_need").html(order_customer_need);
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

	//保存地址信息
	$(".address-save-btn").on("click",function(){
		var address_id = $("input[name='Order[address_id]']:checked").val();
		var id = $("input[name='Order[id]']").val();
		//发送数据
		var url = '/order/order/modify';
		var data = {
			'id':id,
			'address_id':address_id,
		};
		$.post(url,data,function(json){
			if(json.status == 1){
				$(".address-save-btn").hide();
				$(".address-edit-btn").show();
			}
		},'json');
	});
});