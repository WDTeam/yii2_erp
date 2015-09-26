$(document).ready(function(){
	//凸显左导航的当前页面
	$(".sidebar-menu .treeview-menu li a[href='" + window.location.pathname + "']")
	.removeClass("active").addClass("active");
});