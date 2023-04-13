(function ($, window, document) {

    Propeller.RecentlyViewedSlider = {
        init_slider: function () {
            $('#product-recently-viewed-slider').not('.slick-initialized').slick({
                dots: true,
                infinite: false,
                arrows: true,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 5,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false
                        }
                    }
                ]
            });
        },
        on_load: function() {
            if (typeof window.slider_recent_products != 'undefined' && window.slider_recent_products.length) {
                Propeller.Ajax.call({
                    url: PropellerHelper.ajax_url,
                    method: 'POST',
                    data: {
                        ids: window.slider_recent_products,
                        action: 'get_recently_viewed_products'
                    },
                    loading: $('#product-recently-viewed-slider'),
                    success: function(data, msg, xhr) {
                        $('#product-recently-viewed-slider').html(data.content);

                        if(Propeller.hasOwnProperty('Frontend')) {
                            Propeller.Frontend.init();
                        }

                        Propeller.RecentlyViewedSlider.init_slider();
                    },
                    error: function() {
                        console.log('error', arguments);
                    }
                });
            }
        }
    };

    //Propeller.RecentlyViewedSlider.on_load();

}(window.jQuery, window, document));