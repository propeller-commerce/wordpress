(function ($, window, document) {

    const {__, _x, _n, _nx} = wp.i18n;

    Propeller.Checkout = {
        postprocess: function(data) {
            if (typeof data.message != 'undefined')
                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'success', null);
            if (typeof data.reload != 'undefined')
                window.location.reload();
            if (typeof data.redirect != 'undefined')
                window.location.href = data.redirect;
        }
    };

}(window.jQuery, window, document));