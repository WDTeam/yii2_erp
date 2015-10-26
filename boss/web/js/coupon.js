//coupon service type
$(":radio[value=0]").attr('checked', true);
$(".field-coupon-coupon_service_type_id, .field-coupon-coupon_service_id").hide();
$(document).on("change","#coupon-coupon_type input[type='radio']",function(){
    var coupon_type = $(this).val();
	switch(coupon_type){
		case '0':
			$(".field-coupon-coupon_service_type_id, .field-coupon-coupon_service_id").hide();
		break;	
		case '1':
			$(".field-coupon-coupon_service_type_id").show();
			$(".field-coupon-coupon_service_id").hide();

			//send ajax to get service types
			
		break;
		case '2':
			//send ajax to get services
			$(".field-coupon-coupon_service_type_id").show();
			$(".field-coupon-coupon_service_id").hide();
		break;
		default:
		break;
	}
});

//coupon city 
$(":radio[value=0]").attr('checked', true);
$(".field-coupon-coupon_city_id").hide();
$(document).on("change","#coupon-coupon_city_limit input[type='radio']",function(){
    var coupon_city_limit = $(this).val();
	switch(coupon_city_limit){
		case '0':
			$(".field-coupon-coupon_city_id").hide();
		break;	
		case '1':
			//send ajax to get operation cities
			$(".field-coupon-coupon_city_id").show();
		break;
		default:
		break;
	}
});

//coupon time type
$(":radio[value=0]").attr('checked', true);
$(".field-coupon-coupon_end_at").show();
$(".field-coupon-coupon_get_end_at, .field-coupon-coupon_use_end_days").hide();
$(document).on("change","#coupon-coupon_time_type input[type='radio']",function(){
    var coupon_time_type = $(this).val();
	switch(coupon_time_type){
		case '0':
			$(".field-coupon-coupon_end_at").show();
			$(".field-coupon-coupon_get_end_at, .field-coupon-coupon_use_end_days").hide();
		break;	
		case '1':
			$(".field-coupon-coupon_end_at").hide();
			$(".field-coupon-coupon_get_end_at, .field-coupon-coupon_use_end_days").show();
		break;
		default:
		break;
	}
});

//coupon promote type
$(":radio[value=0]").attr('checked', true);
$(".field-coupon-coupon_order_min_price").hide();
$(document).on("change","#coupon-coupon_promote_type input[type='radio']",function(){
    var coupon_promote_type = $(this).val();
	switch(coupon_promote_type){
		case '0':
			$(".field-coupon-coupon_order_min_price").hide();
		break;	
		case '1':
			$(".field-coupon-coupon_order_min_price").show();
		break;
		default:
		break;
	}
});






