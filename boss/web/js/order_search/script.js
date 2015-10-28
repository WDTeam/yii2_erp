var operating_order_id = 0;
var complaint_customer_phone = '';
var complaint_items = [];

$(document).ready(function($){
	//open popup
	$('.cd-popup-trigger').on('click', function(event){
		event.preventDefault();
		$('.cd-popup').addClass('is-visible');
	});
	
	//close popup
	$('.cd-popup').on('click', function(event){
		if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
			event.preventDefault();
			$(this).removeClass('is-visible');
		}
	});
	
	//close popup when clicking the esc keyboard button
	$(document).keyup(function(event){
    	if(event.which=='27'){
    		$('.cd-popup').removeClass('is-visible');
	    }
    });
	
	$("#list li").click(
       	function(){
			$(this).addClass("cur");
			$(this).siblings("li").removeClass("cur");
	   }
     );	
	
	// 默认初始化对话框
	var $el = $(".dialog");
	$el.hDialog(); 
	
	// 取消订单
	$(".m_quxiao").hDialog({width:600,height: 400});
	$(".m_quxiao").click(function(){
		$(".xuanzhong").attr("checked","checked");
		operating_order_id = $(this).parents('tr').find('input.order_id').val();
	});	
	$(".submitq").click(function(){
		var cancelType = $(":radio[name='radio_cancelType']:checked").val();
		var cancelNote = $("#text_CancelNote").val();
		
		$.ajax({
            type: "POST",
            url:  "/order/order/cancel-order",
            data: {order_id: operating_order_id, cancel_type: cancelType, cancel_note: cancelNote},
            dataType:"json",
            success: function (msg) {alert('11:' + msg);
                if(msg != false){alert('22');
                	$("#HBox2").hide();
                	$("#HOverlay").hide()
                }else{
                    alert('取消订单失败！');
                }
            }
        });		
	});	
	
	// 订单投诉
	$(".m_tousu").hDialog({ box:"#HBox2", width:800,height: 600});
	$(".m_tousu").click(function(){
		$(".xuanzhong").attr("checked","checked");
		operating_order_id = $(this).parents('tr').find('input.order_id').val();
		complaint_customer_phone = $(this).parents('tr').find('input.customer_phone').val();
	});
	$(".jsRadio label").click(function(){
		var indexval=$(this).index();
		$(this).parents(".radioLi").next("li").children(".js_radio_tab").hide();
		$(this).parents(".radioLi").next("li").children(".js_radio_tab").eq(indexval).show();
	});
	
	$(".submitBtn").click(function(){
		var dept_id = $(":radio[name='radio_department']:checked").val();
		var dept_name = $(":radio[name='radio_department']:checked").parent().text();
		var complaint_type_id = $(":radio[name='radio_complaint_type']:checked").val();
		var complaint_type_name = $(":radio[name='radio_complaint_type']:checked").parent().text();
		var complaint_level_id = $(":radio[name='radio_complaint_level']:checked").val();
		var complaint_level_name = $(":radio[name='radio_complaint_level']:checked").parent().text();		
		
		complaint_items.push({department: dept_id, type: complaint_type_id, level: complaint_level_id});
		
		$(".m_queren").children("div").prepend('<p><span>' + dept_name + '</span><span>投诉类型：' + complaint_type_name + '</span> <span>投诉级别：' + complaint_level_name + '</span><a href="javascript:;">修改</a></p>');
		
		$(".m_queren").show();
		$(".radioLi").hide();
		$(".m_disd").hide();
		$(".m_disd").hide();
		$(".submitBtntt").show();
	});
	
	$(".m_add").click(function(){
		$(".m_queren").show();
		$(".radioLi").show();
		$(".m_disd").show();
		$(".m_disd").show();
		$(".submitBtntt").hide();
	});
	
	$(".m_submit").click(function(){
        var complaints = {
            	order_id: operating_order_id,
            	complaint_detail: $('#complaint_detail').val(),
            	cumstomer_phone: complaint_customer_phone,
            	data: complaint_items
            };
        
		$.ajax({
            type: "POST",
            url:  "order-complaint/create",
            data: complaints,
            dataType:"json",
            success: function (msg) {
                if(msg.status){
                    
                }else{
                    alert('提交投诉失败！');
                }
            }
        });		
	});
});