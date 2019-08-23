$(document).ready(function () {
    // Helper function
    function convertIntToBool(int) {
        if (int == 1) {
            return true;
        } else {
            return false;
        }
    }

    // get slider
    $slickSlider = $('.slick-carousel ul.carousel-item-container');

    // get parameters from slider
    slidesToShowOnSmall = parseInt($slickSlider.attr("data-small-visible"));
    slidesToShowOnMedium = parseInt($slickSlider.attr("data-medium-visible"));
    slidesToShowOnLarge = parseInt($slickSlider.attr("data-large-visible"));
    infinite = convertIntToBool($slickSlider.attr("data-infinite"));
    dots = !convertIntToBool($slickSlider.attr("data-dots"));
    arrows = !convertIntToBool($slickSlider.attr("data-arrows"));

    // init Slider
    initSlick()

});

function initSlick(params) {
    $slickSlider.slick({
        mobileFirst: true,
        slidesToShow: slidesToShowOnSmall,
        dots: dots,
        arrows: arrows,
        infinite: infinite,
        responsive: [{
                breakpoint: 639,
                settings: {
                    slidesToShow: slidesToShowOnMedium
                }
            },
            {
                breakpoint: 1023,
                settings: {
                    slidesToShow: slidesToShowOnLarge,
                }
            }
        ]
    });
}