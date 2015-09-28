(function ($) {
        $.fn.slideShow = function (options) {

//           Supplying default options

            options = $.extend({
                timeOut: 3000,
                showNavigation: true,
                pauseOnHover: true,
                swipeNavigation: true
            }, options);


//        Variables
            var intervals = [],
                slideshowImgs = [],
                originalSrc,
                img,
                cont,
                width,
                height,

//        Creates an object with all the elements with a 'data-slideshow' attribute

                container = this.filter(function () {
                    return $(this).data('slideshow');
                });

//        Cycle through all the elements from the container object
//        Later on we'll use the "i" variable to distinguish the separate slideshows from one another

            for (var i = 0; i < container.length; i++) {

                cont = $(container[i]);

                width = window.screen.availWidth;
                height = window.screen.availHeight - 61;


//            For every separate slideshow, create a helper <div>, each with its own ID.
//            In those we'll store the images for our slides.

                var helpdiv = $('<div id="slideshow-container-' + i + '" class="slideshow" >');

                helpdiv.height(height);
                helpdiv.width(width);


//            Append the original image to the helper <div>

                originalSrc = cont.attr('src');
                img = $('<div class="slide" style="background-image: url(' + originalSrc + ')">');
                img.appendTo(helpdiv);

//            Append the images from the data-slideshow attribute

                slideshowImgs[i] = cont.attr('data-slideshow').split("|");

                for (var j = 0; j < slideshowImgs[i].length; j++) {

                    img = $('<div class="slide"  ' + 'index=' + j + ' style="background-image: url(' + slideshowImgs[i][j] + ')">');
                    img.appendTo(helpdiv);

                }

//            Replace the original element with the helper <div>

                cont.replaceWith(helpdiv);

//            Activate the slideshow

                automaticSlide(i)

            }


//          Slideshow auto switch

            function automaticSlide(index) {

                // Hide all the images except the first one
                $('#slideshow-container-' + index + ' .slide:gt(0)').hide();

                // Every few seconds fade out the first image, fade in the next one,
                // then take the first and append it to the container again, where it becomes last

                intervals[index] = setInterval(function () {
                        $('#slideshow-container-' + index + ' .slide:first').fadeOut("slow")
                            .next('.slide').fadeIn("slow")
                            .end().appendTo('#slideshow-container-' + index + '');
                    },
                    options.timeOut);
            }

            //function hoverStop(id) {
            //    $('#' + id + '').off('mouseenter.hover mouseleave.hover');
            //}
        }
        $('div, img').slideShow({
            timeOut: 6000,
            showNavigation: true,
            pauseOnHover: true,
            swipeNavigation: true
        });
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
    }(jQuery)
)
;
