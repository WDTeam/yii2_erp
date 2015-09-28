$(document).ready(function(){
	//凸显左导航的当前页面
	// $(".sidebar-menu .treeview-menu li a[href='" + window.location.pathname + "']")
	// .removeClass("active").addClass("active");

	for (var i = 0; i < $(".sidebar-menu .treeview-menu li a").length; i++) {
		if ($(".sidebar-menu .treeview-menu li a").eq(i).attr("href").substr(0, $(".sidebar-menu .treeview-menu li a").eq(i).attr("href").indexOf('?') > 0 ? $(".sidebar-menu .treeview-menu li a").eq(i).attr("href").indexOf('?') : $(".sidebar-menu .treeview-menu li a").eq(i).attr("href").length)
			==window.location.pathname) {
			$(".sidebar-menu .treeview-menu li a").eq(i).removeClass("active").addClass("active");
		}
	}

	(function ($) {
		$('div, img').slideShow({
			timeOut: 6000,
			showNavigation: true,
			pauseOnHover: true,
			swipeNavigation: true
		});
		//var navbar=$('.navbar')
		//navbar.animate({top: '-100px'}, function () {
		//	navbar.hide();
		//});
	}(jQuery));
	$("#list li p i").click(
		function(){
			var classname=$(this).attr("class");
			if(classname=="ckb")
			{
				$(this).addClass("cur");
				$(this).removeClass("ckb");
			}
			else
			{
				$(this).addClass("ckb");
				$(this).removeClass("cur");
			}


		}
	);
});

