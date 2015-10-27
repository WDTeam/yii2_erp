var complaint_order_id = 0;
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
	
	var $el = $(".dialog");
	$el.hDialog(); //默认调用
	//改变宽和高
	$(".m_quxiao").hDialog({width:600,height: 400});
	$(".m_quxiao").click(function(){
		$(".xuanzhong").attr("checked","checked");
	});	
	$(".submitq").click(function(){        
		$.ajax({
            type: "POST",
            url:  "/order/test",
            data: complaints,
            dataType:"json",
            success: function (msg) {
                if(msg.status){
                    window.continue_work_count_down = 10;
                    $("#work_console").html(
                        '<button id="stop_work" class="btn btn-warning" type="button">收工啦</button>' +
                        '<button id="pause_work" class="btn btn-warning" type="button">休息</button>' +
                        '<button id="continue_work" class="btn btn-warning" type="button">继续（'+window.continue_work_count_down+'s）</button>'
                    );
                    $("#order_assign").hide();
                    $("#work_console").show();
                }else{
                    alert('指派失败！');
                }
            }
        });		
	});	
	
	$(".m_tousu").hDialog({ box:"#HBox2", width:800,height: 600});
	$(".m_tousu").click(function(){
		$(".xuanzhong").attr("checked","checked");
		complaint_order_id = $(this).parents('tr').find('input.order_id').val();
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
		
		complaint_items.push({department: dept_id, type: "2", level: "3"});
		
		$(".m_queren").children("div").prepend('<p><span>' + dept_name + '</span><span>投诉类型：迟到早退</span> <span>投诉级别：A</span><a href="javascript:;">修改</a></p>');
		
		$(".m_queren").show();
		$(".radioLi").hide();
		$(".m_disd").hide();
		$(".m_disd").hide();
		$(".submitBtntt").show();
	});
	
	$(".m_subm").click(function(){
		$(".m_queren").show();
		$(".radioLi").show();
		$(".m_disd").show();
		$(".m_disd").show();
		$(".submitBtntt").hide();
	});
	
	$(".submitBtntt").click(function(){
        var complaints = {
            	order_id: "1000",
            	complaint_detail: "aaaaaaaaaaaaaaaaaa",
            	cumstomer_phone: "13311111111",
            	data: complaint_items
            };
        
		$.ajax({
            type: "POST",
            url:  "order/test",
            data: complaints,
            dataType:"json",
            success: function (msg) {
                if(msg.status){
                    window.continue_work_count_down = 10;
                    $("#work_console").html(
                        '<button id="stop_work" class="btn btn-warning" type="button">收工啦</button>' +
                        '<button id="pause_work" class="btn btn-warning" type="button">休息</button>' +
                        '<button id="continue_work" class="btn btn-warning" type="button">继续（'+window.continue_work_count_down+'s）</button>'
                    );
                    $("#order_assign").hide();
                    $("#work_console").show();
                }else{
                    alert('指派失败！');
                }
            }
        });		
	});
});