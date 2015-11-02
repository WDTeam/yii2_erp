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

	//保存用户需求
	$(".order_edit_save").on('click',function(){
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
});