/* STICK-HEADER START*/
window.onscroll = function() { myFunction() };

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
}

/* STICK-HEADER START*/



/* RESPONSIVE MENU START*/


(function($) {
    "use strict";

    $(function() {
        var header = $(".start-style");
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 10) {
                header.removeClass('start-style').addClass("scroll-on");
            } else {
                header.removeClass("scroll-on").addClass('start-style');
            }
        });
    });

    //Animation

    $(document).ready(function() {
        $('body.hero-anime').removeClass('hero-anime');
    });

    //Menu On Hover

    $('body').on('mouseenter mouseleave', '.nav-item', function(e) {
        if ($(window).width() > 750) {
            var _d = $(e.target).closest('.nav-item');
            _d.addClass('show');
            setTimeout(function() {
                _d[_d.is(':hover') ? 'addClass' : 'removeClass']('show');
            }, 1);
        }
    });

    //Switch light/dark

    $("#switch").on('click', function() {
        if ($("body").hasClass("dark")) {
            $("body").removeClass("dark");
            $("#switch").removeClass("switched");
        } else {
            $("body").addClass("dark");
            $("#switch").addClass("switched");
        }
    });

})(jQuery);

/* RESPONSIVE MENU END*/

$(".selectBox").on("click", function(e) {
    $(this).toggleClass("show");
    var dropdownItem = e.target;
    var container = $(this).find(".selectBox__value");
    container.text(dropdownItem.text);
    $(dropdownItem)
        .addClass("active")
        .siblings()
        .removeClass("active");
});






$('#slider-overlay .slides').slick({
    lazyLoad: 'progressive',
      arrows: true,
      // fade: true,
      centerPadding: '0px',
      infinite: true,
      centerMode: true,
      speed: 500,
      swipe: false,
      cssEase: 'ease-in-out',
      slidesToShow: 3,
      adaptiveHeight: true,
    asNavFor: '.slick-dots-thumb, #slider .slides',
    autoplaySpeed: 2000,
    responsive: [{
        breakpoint: 1200,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 1008,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 800,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 600,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 414,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
        // settings: "unslick"
    }

]
  });
  $('.slick-dots-thumb').slick({
    lazyLoad: 'progressive',
       arrows: false,
       infinite: true,
       slidesToShow: 3,
       centerMode: false,
       centerPadding: '53px',
       adaptiveHeight: true,
       cssEase: 'ease-in-out',
    asNavFor: '#slider-overlay .slides, #slider .slides',
    focusOnSelect: true,
    autoplaySpeed: 2000,
    responsive: [{
        breakpoint: 1200,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 1008,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 800,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 600,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 414,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
        // settings: "unslick"
    }

]
  });
  


  $('.slider2').slick({
    infinite: true,
    slidesToShow: 2,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 1008,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 414,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            // settings: "unslick"
        }

    ]
});




$('.slider3').slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 5,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 1008,
            settings: {
                slidesToShow: 5,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 414,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            // settings: "unslick"
        }

    ]
});


$('.slider-demo').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    dots: false,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 2000,

    responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 990,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            // settings: "unslick"
        }
    ]
});




// youtube popup//
jQuery(document).ready(function() {
    jQuery('.popup-gallery').magnificPopup({
      delegate: 'a',
      type: 'image',
      callbacks: {
        elementParse: function(item) {
          // Function will fire for each target element
          // "item.el" is a target DOM element (if present)
          // "item.src" is a source that you may modify
          console.log(item.el.context.className);
          if(item.el.context.className == 'video') {
            item.type = 'iframe',
            item.iframe = {
               patterns: {
                 youtube: {
                   index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
  
                   id: 'v=', // String that splits URL in a two parts, second part should be %id%
                    // Or null - full URL will be returned
                    // Or a function that should return %id%, for example:
                    // id: function(url) { return 'parsed id'; } 
  
                   src: '//www.youtube.com/embed/%id%?&loop=1' // URL that will be set as a source for iframe. 
                 },
                 vimeo: {
                   index: 'vimeo.com/',
                   id: '/',
                   src: '//player.vimeo.com/video/%id%?autoplay=1'
                 },
                 gmaps: {
                   index: '//maps.google.',
                   src: '%id%&output=embed'
                 }
               }
            }
          } else {
             item.type = 'image',
             item.tLoading = 'Loading image #%curr%...',
             item.mainClass = 'mfp-img-mobile',
             item.image = {
               tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
             }
          }
  
        }
      },
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
      }
      
    });
  
  });
    // youtube popup//