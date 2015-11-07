//gaofeng status
$(":radio[value=0]").attr('checked', true);

$(".field-couponrule-couponrule_channelname").hide();
$(".field-couponrule-couponrule_code_max_customer_num").hide();
$(".field-couponrule-couponrule_code").hide();
$(".field-couponrule-couponrule_prefix").show();
$(".field-couponrule-couponrule_code_num").show();
$(document).on("change","#couponrule-couponrule_classify input[type='radio']",function(){
    var couponrule_classify = $(this).val();
	//alert(coupon_category);
	//exit;
	switch(couponrule_classify){
		case '1':
			$(".field-couponrule-couponrule_channelname").hide();
		    $(".field-couponrule-couponrule_code_max_customer_num").hide();
			$(".field-couponrule-couponrule_code").hide();
			$(".field-couponrule-couponrule_prefix").show();
			$(".field-couponrule-couponrule_code_num").show();
		break;	
		case '2':
			$(".field-couponrule-couponrule_channelname").show();
		    $(".field-couponrule-couponrule_code_max_customer_num").show();
			$(".field-couponrule-couponrule_code").show();
			$(".field-couponrule-couponrule_prefix").hide();
			$(".field-couponrule-couponrule_code_num").hide();

		break;
		default:
		break;
	}
});


$(":radio[value=0]").attr('checked', true);
$(".field-couponrule-couponrule_service_type_id").hide();
$(".field-couponrule-couponrule_commodity_id").hide();



$(document).on("change","#couponrule-couponrule_type input[type='radio']",function(){
    var couponrule_type = $(this).val();
	
	switch(couponrule_type){
		case '1':
			$(".field-couponrule-couponrule_service_type_id").hide();
			$(".field-couponrule-couponrule_commodity_id").hide();
		break;	
		case '2':
			$(".field-couponrule-couponrule_service_type_id").show();
			$(".field-couponrule-couponrule_commodity_id").hide();
		break;
		case '3':
			$(".field-couponrule-couponrule_service_type_id").hide();
			$(".field-couponrule-couponrule_commodity_id").show();
		break;
		default:
		break;
	}
});



$(".field-CouponRule-province_city_county_town").hide();
$(document).on("change","#couponrule-couponrule_city_limit input[type='radio']",function(){
    var couponrule_city_limit = $(this).val();
	
	switch(couponrule_city_limit){
		case '1':
			$(".field-CouponRule-province_city_county_town").hide();
		break;	
		case '2':
			$(".field-CouponRule-province_city_county_town").show();
		break;
		default:
		break;
	}
});







//gaofeng end
//coupon category
$(":radio[value=0]").attr('checked', true);
$(document).on("change","#coupon-coupon_category input[type='radio']",function(){
    var coupon_category = $(this).val();
	//alert(coupon_category);
	//exit;
	switch(coupon_category){
		case '0':
			$("#coupon-coupon_code_num").val('');
			$("#coupon-coupon_code_num").attr('readonly', false);
		break;	
		case '1':
			$("#coupon-coupon_code_num").val(1);
			$("#coupon-coupon_code_num").attr('readonly', true);
			
		break;
		default:
		break;
	}
});



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






