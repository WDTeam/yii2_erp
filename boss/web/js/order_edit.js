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
});