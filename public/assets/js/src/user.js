(function ($, window, document) {

    Propeller.User = {
        init: function () {
            $('.price-toggle a').off('click').click(this.custom_prices);
        },
        custom_prices: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var show_specific_prices = 0;

            if ($(this).closest('.price-toggle').hasClass('price-on')) {
                $(this).closest('.price-toggle').removeClass('price-on').addClass('price-off');
                show_specific_prices = 0;
            }
            else {
                $(this).closest('.price-toggle').removeClass('price-off').addClass('price-on');
                show_specific_prices = 1;
            }

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: {
                    action: 'user_prices',
                    active: show_specific_prices
                },
                success: function(data, msg, xhr) {
                    if (data.success && data.reload)
                        window.location.reload();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    };

    //Propeller.User.init();

}(window.jQuery, window, document));