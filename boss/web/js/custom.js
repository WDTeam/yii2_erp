$(document).ready(function(){
	//凸显左导航的当前页面
	$(".sidebar-menu .treeview-menu li a[href='" + window.location.pathname + window.location.search + "']")
	.removeClass("active").addClass("active");
	$(".sidebar-menu .treeview-menu li a[href='" + window.location.pathname + '#' + window.location.search + "']")
	.removeClass("active").addClass("active");

	// for (var i = 0; i < $(".sidebar-menu .treeview-menu li a").length; i++) {
	// 	if ($(".sidebar-menu .treeview-menu li a").eq(i).attr("href").substr(0, $(".sidebar-menu .treeview-menu li a").eq(i).attr("href").indexOf('?') > 0 ? $(".sidebar-menu .treeview-menu li a").eq(i).attr("href").indexOf('?') : $(".sidebar-menu .treeview-menu li a").eq(i).attr("href").length)
	// 		==window.location.pathname) {
	// 		$(".sidebar-menu .treeview-menu li a").eq(i).removeClass("active").addClass("active");
	// 	}
	// }

	//滚动了一部分后，给content-header添加样式
	$(window).scroll(function(){
		var windowScroll = this.pageYOffset|| document.documentElement.scrollTop || document.body.scrollTop;

		if (windowScroll > 20) {
			$(".right-side .content-header").removeClass('scroll').addClass('scroll');
		} else{
			$(".right-side .content-header").removeClass('scroll');
		}
	});

	//给所有单选框添加样式
	// $('.MyRadioStyle input').iCheck({
	// 	checkboxClass: 'icheckbox_square-blue',
	// 	radioClass: 'iradio_square-blue',
	// 	increaseArea: '10%' // optional
	// });

	$('.MyRadioStyle input').each(function(){
		ApplyToRadio($(this));
	});


	 //触发单选框的change事件
	 $('.MyRadioStyle input').on('ifChecked', function(event){
	 	$(this).trigger("change");
	 });
	 $('.MyRadioStyle input').on('ifUnchecked', function(event){
	 	$(this).trigger("change");
	 });

	//给所有的type=file外层添加一个<a>
	$("input[type='file']").each(function() {
		$(this).replaceWith('<a class="a-upload">' + $(this).prop('outerHTML') + '点击上传文件</a><label class="uploadMsg"></label>');
	});
	//选择完上传文件后，显示出来
	$("input[type='file']").on("change", function(){
		// alert($(this).val());
		$(this).parent().next().html($(this).val());
	});

	//评价标签管理部分js
	//增加form-inline样式，是按钮横向排列
	$("div.customer-comment-tag-create .form-group").addClass("form-inline");
	var _good = $("div.customer-comment-tag-create .form-group label");
	var check_list = $("div.customer-comment-tag-create .col-sm-12").eq(2).addClass("check");
	var assess_list = $("div.customer-comment-tag-create .col-sm-12").eq(3).addClass("assess_list");
	_good.eq(5).addClass("good-job");
	_good.eq(6).addClass("just-so-so");
	_good.eq(7).addClass("assess");
	$(".good-job,.just-so-so").click(function(){$(".check").css("display","none")});
	$(".assess").click(function(){$(".assess_list").css("display","none")});
	$(".check").css("display","none");
	$(".assess").click(function(){
		$(".assess_list").css("display","none");
		$(".check").css("display","block");

	});
	$(".just-so-so,.good-job").click(function(){
		$(".assess_list").css("display","block");
		$(".check").css("display","none");

	});

	//时间插件

    //Datemask dd/mm/yyyy
                $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
                //Datemask2 mm/dd/yyyy
                $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
                //Money Euro
                $("[data-mask]").inputmask();

                //Date range picker
                $('#reservation').daterangepicker();
                //Date range picker with time picker
                $('#order-time').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
                $('#doing-time').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
                //Date range as a button
                $('#daterange-btn').daterangepicker(
                        {
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                                'Last 7 Days': [moment().subtract('days', 6), moment()],
                                'Last 30 Days': [moment().subtract('days', 29), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                            },
                            startDate: moment().subtract('days', 29),
                            endDate: moment()
                        },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
                );
                //Colorpicker
                $(".my-colorpicker1").colorpicker();
                //color picker with addon
                $(".my-colorpicker2").colorpicker();

                //Timepicker
                $(".timepicker").timepicker({
                    showInputs: false
                });


>>>>>>> fanyuanhe
	
});

function ApplyToRadio(self){
	var label = self;
	var label_text = label.parent().text();

	////截取文字长度
	//if(label_text.length > 6) {
	//	label.parent().attr('title', label_text);
	//	label_text = label_text.substr(0,6) + '...';
	//}

	label.parent().html('').append(self);

	self.iCheck({
		checkboxClass: 'icheckbox_line-blue',
		radioClass: 'iradio_line-blue',
		insert: '<div class="icheck_line-icon"></div>' + label_text
	});
}

  