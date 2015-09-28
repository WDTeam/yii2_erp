/**
 * Created by ejiajie on 2015/9/27.
 */
(function ($) {
    $('div, img').slideShow({
        timeOut: 6000,
        showNavigation: true,
        pauseOnHover: true,
        swipeNavigation: true
    });
    var navbar=$('.navbar')
    navbar.animate({top: '-100px'}, function () {
        navbar.hide();
    });
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