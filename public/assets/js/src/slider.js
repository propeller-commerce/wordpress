(function ($, window, document) {

    Propeller.Slider = {
        init: function() {
            $('.propeller-slider').not('.slick-initialized').slick({
                dots: false,
                infinite: false,
                arrows: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: false,
                            arrows: true,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            dots: true,
                            arrows: false
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: "unslick"
                    }
                ]
            });
        }
    }

    //Propeller.Slider.init();

}(window.jQuery, window, document));