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
		console.log(windowScroll);
	});

	$('input').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '10%' // optional
	});
});

