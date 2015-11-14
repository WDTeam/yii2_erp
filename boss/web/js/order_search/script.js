var operating_order_id = 0;
var operating_order_code = 0;
var complaint_customer_phone = '';

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
	
	// 辅助函数，验证传入函数的字符串是否全为数字
	function CheckDigit(str)
	{
		var letters="0123456789";
		for(var i=0;i<str.length;i++)
		{
			if(letters.indexOf (str[i])==-1)
				return false;
		}
		return true ;
	}
	
	// 默认初始化对话框
	var $el = $(".dialog");
	$el.hDialog(); 
	
	// 输入条件校验
//	$('.button-search').click(function(){
//		var customerPhone = $('.input_customer_phone').val();
//		
//		if (CheckDigit(customerPhone) == false){
//			$(this).hDialog({box:'#HBox_error'});
//			return false;
//		}
//		return true;
//	});
	
	// 取消订单
	$(".m_quxiao").hDialog({width:600,height: 400});
	$(".m_quxiao").click(function(){
		$(".xuanzhong").attr("checked","checked");
		operating_order_id = $(this).parents('tr').find('input.order_id').val();
		operating_order_code = $(this).parents('tr').find('input.order_code').val();
	});
	$(".submitq").click(function(){
		var cancelType = $(":radio[name='radio_cancelType']:checked").val();
		var cancelDetailType = $(":radio[name='radio_cancelDetailType']:checked").val();
		var cancelNote = $("#text_CancelNote").val();
		
		if (cancelType == undefined || cancelDetailType == undefined){
			alert("请选择取消原因！");
			return;
		}
		
		$.ajax({
            type: "POST",
            url:  "/order/order/cancel-order",
            data: {order_id: operating_order_id,order_code:operating_order_code, cancel_type: cancelDetailType, cancel_note: cancelNote},
            dataType:"json",
            success: function (msg) {
                if(msg.status){
                	alert('取消订单成功！');
                	window.location.reload();
                }else{
                    alert('取消订单失败！错误代码：['+msg.error_code+']');
                }
            }
        });		
	});	

    //订单响应开始 -----------------------------------------------------------
	$(".m_response").hDialog({ box:"#HBox4", width:800,height: 600});

	$(".m_response").click(function(){
		$(".xuanzhong").attr("checked","checked");
		operating_order_id = $(this).parents('tr').find('input.order_id').val();
		operating_order_code = $(this).parents('tr').find('input.order_code').val();

        //把上次添加的记录清除，重新添加
        $(".response_record").children("div").empty();
        $(".response_times").children("div").empty();
        //$(".response_times").children("div").prepend('<span>正在加载...</span>'); 

        //获取响应记录
		$.ajax({
            type: "POST",
            url:  "/order/order-response/get-order-response-times",
            data: {
                order_id: operating_order_id,
                order_code: operating_order_code,
            },
            dataType:"json",
            success: function (msg) {
                if (msg.code == 201) {
                    for (i = 3; i >= 1; i --) {
                        $(".response_times").children("div").prepend(
                                '<label class="mr10"> <input type="radio" name="radio_responsetimes" value="' + i + '" id=times_' + i + ' />' + i + '次' + '</label>'
                        ); 
                    }

                    if (msg.data == 1) {
                        $('#times_1').attr("disabled",true);
                        $('#times_3').attr("disabled",true);
                    }
                    if (msg.data == 2) {
                        $('#times_2').attr("disabled",true);
                        $('#times_1').attr("disabled",true);
                    }
                    if (msg.data == 3) {
                        $('#times_3').attr("disabled",true);
                        $('#times_2').attr("disabled",true);
                        $('#times_1').attr("disabled",true);
                    }

                } else {
                    for (i = 3; i >= 1; i --) {
                        $(".response_times").children("div").prepend(
                                '<label class="mr10"> <input type="radio" name="radio_responsetimes" value="' + i + '" id=times_' + i + ' />' + i + '次' + '</label>'
                        ); 
                    }

                    $('#times_3').attr("disabled",true);
                    $('#times_2').attr("disabled",true);
                }

            }
        });		


        //获取响应记录
		$.ajax({
            type: "POST",
            url:  "/order/order-response/get-order-response",
            data: {
                order_id: operating_order_id,
                order_code: operating_order_code,
            },
            dataType:"json",
            success: function (msg) {
                if (msg.code == 201) {
                    var len = msg.data.length;
                    for (i = 0; i < len; i ++) {
                        $(".response_record").children("div").prepend(
                            '<span>时间:' + msg.data[i].created_at + '</span>' + ';' +
                            '<span>操作人:' + msg.data[i].order_operation_user + '</span>' +  ';' +

                            '<span>响应次数:' + msg.data[i].order_response_times + '</span>' +  ';' +

                            '<span>接听结果:' + msg.data[i].order_reply_result + '</span>' +  ';' +

                            '<span>是否响应:' + msg.data[i].order_response_or_not + '</span>' +  ';' +

                            '<span>响应结果:' + msg.data[i].order_response_result + '</span>' +  ';' + '<br />'
                        ); 
                    }

                } else {
                    $(".response_record").children("div").prepend('<span>暂无记录</span>'); 
                }
            }
        });		
	});	

    //每次点击响应，响应结果都先隐藏
	$(".m_response").click(function(){
        $("#responseresult_show").hide();
    });

    //是否响应
	$(".radio_responseornot").click(function(){
		var responseornot_id = $(":radio[name='radio_responseornot']:checked").val();

        if (responseornot_id == 0) {
            $("#responseresult_show").show();
        } else if (responseornot_id == 1) {
            $("#responseresult_show").hide();
        }
    });

    //信息是否选中
	$(".responseSubmitBtn").click(function(){
		var responsetimes_id = $(":radio[name='radio_responsetimes']:checked").val();
		//var responsetimes_name = $(":radio[name='radio_responsetimes']:checked").parent().text();

		var replyresult_id = $(":radio[name='radio_replyresult']:checked").val();
		//var replyresult_name = $(":radio[name='radio_replyresult']:checked").parent().text();

		var responseornot_id = $(":radio[name='radio_responseornot']:checked").val();
		//var responseornot_name = $(":radio[name='radio_responseornot']:checked").parent().text();

		var responseresult_id = $(":radio[name='radio_responseresult']:checked").val();
		//var responseresult_name = $(":radio[name='radio_responseresult']:checked").parent().text();

		if (responsetimes_id == undefined) {
			alert("响应次数必须选择！");
			return;
		}

		if (replyresult_id == undefined) {
			alert("接听结果必须选择！");
			return;
		}

		if (responseornot_id == undefined) {
			alert("是否响应必须选择！");
			return;
		}

        if ((responseornot_id == 0) && (responseresult_id == undefined)) {
			alert("响应结果必须选择！");
			return;
        }

        if (responseornot_id == 1) {
            responseresult_id = 0;
        }

        //保存数据库
		$.ajax({
            type: "POST",
            url:  "/order/order-response/save-order-response",
            data: {
                order_response_times: responsetimes_id,
                order_reply_result: replyresult_id,
                order_response_or_not: responseornot_id,
                order_response_result: responseresult_id,
                order_id: operating_order_id,
                order_code: operating_order_code,
            },
            dataType:"json",
            success: function (msg) {
                if(msg.code == 201){
                    alert(msg.msg);
                    $("#HBox4").hide();
                    $("#HOverlay").hide()
                }else{
                    alert(msg.msg);
                }
            }
        });		

	});
    //订单响应结束 -----------------------------------------------------------
	
	// 订单投诉
	$(".m_tousu").hDialog({ box:"#HBox2", width:800,height: 600});
	$(".m_tousu").click(function(){
		$(".xuanzhong").attr("checked","checked");
		operating_order_id = $(this).parents('tr').find('input.order_id').val();
		operating_order_code = $(this).parents('tr').find('input.order_code').val();
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
		
		if (dept_id == undefined || complaint_type_id == undefined || complaint_level_id == undefined){
			alert("投诉部门、投诉类型、投诉级别都必须选择！");
			return;
		}
		
		$(".m_queren").children("div").prepend('<p><input type="hidden" value="' + dept_id 
				+ '"><input type="hidden" value="' + complaint_type_id + '"><input type="hidden" value="' + complaint_level_id 
				+ '"><span>投诉部门：' + dept_name + '</span><span>投诉类型：' + complaint_type_name + '</span> <span>投诉级别：' 
				+ complaint_level_name + '</span><a href="#" class="m_edit">修改</a></p>');
		
		$(".m_edit").unbind();
		$(".m_edit").click(function(){
			var current_row = $(this).parent();
			$(":radio[name='radio_department']").each(function (){
				if ($(this).val() == current_row.children('input:eq(0)').val())
					$(this).parent().click();
			});

			$(":radio[name='radio_complaint_type']").each(function (){
				if ($(this).val() == current_row.children('input:eq(1)').val())
					$(this).prop('checked', true);
			});
			
			$(":radio[name='radio_complaint_level']").each(function (){
				if ($(this).val() == current_row.children('input:eq(2)').val())
					$(this).prop('checked', true);
			});
			
			$(this).parent().remove();
			
			$(".m_queren").show();
			$(".radioLi").show();
			$(".m_disd").show();
			$(".submitBtntt").hide();
			
			return false;
		});		
		
		$(".m_queren").show();
		$(".radioLi").hide();
		$(".m_disd").hide();
		$(".submitBtntt").show();

		$(":radio[name='radio_department']").removeAttr("checked");
		$(":radio[name='radio_complaint_type']").removeAttr("checked");
		$(":radio[name='radio_complaint_level']").removeAttr("checked");
		
		$(":radio[name='radio_department']:eq(0)").parent().click();
	});
	
	$(".m_add").click(function(){
		$(".m_queren").show();
		$(".radioLi").show();
		$(".m_disd").show();
		$(".submitBtntt").hide();
		
		$(":radio[name='radio_department']").removeAttr("checked");
		$(":radio[name='radio_complaint_type']").removeAttr("checked");
		$(":radio[name='radio_complaint_level']").removeAttr("checked");
		
		$(":radio[name='radio_department']:eq(0)").parent().click();
	});
	
	$(".m_submit").click(function(){
		var complaint_items = [];
		
		$(".m_queren div p").each(function(){
			complaint_items.push({department: $(this).children('input:eq(0)').val(), type: $(this).children('input:eq(1)').val(), level: $(this).children('input:eq(2)').val()});
		});
		
        var complaints = {
            	order_id: operating_order_id,
            	order_code: operating_order_code,
            	complaint_detail: $('#complaint_detail').val(),
            	cumstomer_phone: complaint_customer_phone,
            	data: complaint_items
            };
        
		$.ajax({
            type: "POST",
            url:  "/order/order-complaint/back",
            data: complaints,
            dataType:"json",
            success: function (msg) {
                if(msg){
                	alert('提交投诉成功！');
                	//$("#HBox2").hide();
                	//$("#HOverlay").hide();
                	window.location.reload();
                }else{
                    alert('提交投诉失败！');
                }
            }
        });		
	});
});
