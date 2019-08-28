// get all slider
$slickSlider = $('.slick-carousel');

$slickSlider.each(function(){
    item = $(this).find('.carousel-item-container');
    slickID = $(this).attr('data-slick-bID');
    
    ajax(item, slickID);

});

function ajax(item, slickID) {
    $.ajax({
        dataType: "json",
        type: "POST",
        url: '/slick/getData/' + slickID,
        success: function (response) {
            initSlick(item, response);
        }
    });
}
// Helper function
function convertIntToBool(int) {
    if (int == 1) {
        return true;
    } else {
        return false;
    }
}

function initSlick(item, params) {
    dots = !convertIntToBool(params['hideDots']);
    arrows = !convertIntToBool(params['hideArrows']);
    infinite = convertIntToBool(params['infinite']);
    itemsmobile = parseInt(params['itemsmobile']);
    itemstablet = parseInt(params['itemstablet']);
    itemsdesktop = parseInt(params['itemsdesktop']);

    item.slick({
        mobileFirst: true,
        slidesToShow: itemsmobile,
        dots: dots,
        arrows: arrows,
        infinite: infinite,
        responsive: [{
                breakpoint: 639,
                settings: {
                    slidesToShow: itemstablet,
                }
            },
            {
                breakpoint: 1023,
                settings: {
                    slidesToShow: itemsdesktop,
                }
            }
        ]
    });
    item.parent().find('.preloader').css('opacity', '0');
}