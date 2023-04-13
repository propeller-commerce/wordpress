(function ($, window, document) {

    Propeller.Product = {
        order_form: null,
        selected_options: [],
        init: function() {
            this.order_form = $('.add-to-basket-form');

            this.handle_order_button();

            $('.cluster-radio').off('change').on('change', this.radio_changed);
            $('.cluster-dropdown').off('change').on('change', this.dropdown_changed);

            this.load_crossupsells();

            if(Propeller.hasOwnProperty('Cart')) {
                Propeller.Cart.init();
            }
        },
        handle_order_button: function() {
            var disable = this.order_form.find('input[name="product_id"').val() == '';

            this.order_form.find('button[data-type="minus"]').prop('disabled', disable);
            this.order_form.find('button[data-type="plus"]').prop('disabled', disable);
            this.order_form.find('input.quantity').prop('disabled', disable);
            this.order_form.find('button.btn-addtobasket').prop('disabled', disable);
        },
        radio_changed: function(e) {
            Propeller.Product.handle_cluster_change(this);
        },
        dropdown_changed: function(e) {
            Propeller.Product.handle_cluster_change(this);
        },
        handle_cluster_change: function(obj) {

            if ($(obj).val() == '')
                return;

            var slug = '', path = '';

            if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.product) > -1)
                path = PropellerHelper.slugs.product;

            var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
            if (url_chunks !== null)
                slug = url_chunks[2];

            var request_data = $('.cluster-dropdown').serializeObject();

            request_data.action = 'update_cluster_content';
            request_data.slug = slug;
            request_data.cluster_id = $(obj).data('cluster_id');

            request_data.clicked_attr = $(obj).attr('name');
            request_data.clicked_val = $(obj).val();


            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: request_data,
                loading: $('.propeller-product-details'),
                success: function(data, msg, xhr) {
                    $('.propeller-product-details').html(data.content);

                    //Propeller.Frontend.init();
                    Propeller.ProductPlusMinusButtons.init();
                    Propeller.Product.gallery_change();
                    Propeller.Product.gallery_swipe();
                    //Propeller.Product.cross_upsell_slider();
                    Propeller.Product.init();
                    Propeller.ProductFixedWrapper.init();

                    if(Propeller.hasOwnProperty('Cart')) {
                        Propeller.Cart.init();
                    }
                },
                error: function() {
                    console.log('error', arguments);
                }
            });
        },
        gallery_swipe: function() {
            var items = [];
            $('.gallery-item-slick' , '.slick-gallery').each( function() {
                var $figure = $(this),
                    $a = $figure.find('a'),
                    $src = $a.attr('href'),
                    $title = $figure.find('figcaption').html(),
                    $msrc = $figure.find('img').attr('src');
                if ($a.data('size')) {
                    var $size   = $a.data('size').split('x');
                    var item = {
                        src   : $src,
                        w   : $size[0],
                        h     : $size[1],
                        title   : $title,
                        msrc  : $msrc
                    };
                } else {
                    var item = {
                        src: $src,
                        w: 800,
                        h: 800,
                        title: $title,
                        msrc: $msrc
                    };
                    var img = new Image();
                    img.src = $src;

                    var wait = setInterval(function() {
                        var w = img.naturalWidth,
                            h = img.naturalHeight;
                        if (w && h) {
                            clearInterval(wait);
                            item.w = w;
                            item.h = h;
                        }
                    }, 30);
                }
                var index = items.length;
                items.push(item);
                $figure.unbind('click').click(function(event) {
                    event.preventDefault(); // prevent the normal behaviour i.e. load the <a> hyperlink
                    // Get the PSWP element and initialise it with the desired options
                    var $pswp = $('#pswp')[0];
                    console.log($pswp);
                    var options = {
                        index: index,
                        bgOpacity: 0.8,
                        showHideOpacity: true
                    }
                    new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options).init();
                });
            });
        },
        gallery_change: function() {
            $('.slick-gallery').not('.slick-initialized').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                adaptiveHeight: false,
                fade: true,
                asNavFor: '#product-thumb-slick',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            dots: true
                        }
                    }
                ]
            });

            $('#product-thumb-slick').not('.slick-initialized').slick({
                slidesToShow: 4,
                slidesToScroll: 4,
                vertical:true,
                dots: false,
                autoplay:false,
                arrows: true,
                asNavFor: '#slick-gallery',
                focusOnSelect: true,
                centerMode: true,
                centerPadding: '20px',
                verticalSwiping:true,
                infinite: false,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            vertical: false,
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            arrows: true,
                            focusOnSelect: true,
                            centerMode: false
                        }
                    }
                ]
            });
        },
        cross_upsell_slider: function(container = '') {

            container = container == '' ? '.crossupsells-slider' : '#' + container;

            $(container).not('.slick-initialized').slick({
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
        },
        load_crossupsells: function() {

            if ($('.crossupsells-slider').length) {
                $('.crossupsells-slider').each(function(index, slider) {
                    Propeller.Ajax.call({
                        url: PropellerHelper.ajax_url,
                        method: 'POST',
                        data: {
                            slug: $(slider).data('slug'),
                            crossupsell_type: $(slider).data('type'),
                            action: 'load_crossupsells',
                            class: $(slider).data('class')
                        },
                        loading: $(slider),
                        success: function(data, msg, xhr) {
                            $(slider).html(data.content);

                            //Propeller.Frontend.init();
                            Propeller.Cart.init();
                            Propeller.ProductPlusMinusButtons.init();
                            Propeller.Product.cross_upsell_slider($(slider).attr('id'));
                        },
                        error: function() {
                            console.log('error', arguments);
                        }
                    });
                });
            }
        }
    };

    //Propeller.Product.init();

}(window.jQuery, window, document));