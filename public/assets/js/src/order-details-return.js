(function ($, window, document) {

    const {__, _x, _n, _nx} = wp.i18n;

    Propeller.Order = {
        postprocess: function (data) {
            if (data.status) {
                if (data.reload)
                    window.location.reload();
                if (data.redirect)
                    window.location.href = data.redirect;

            } else if (data.return_success) {
                $('#return_modal_' + data.order_id).modal('hide');
                $('#return_modal_' + data.order_id).find('form').trigger('reset');
                $('#returnRequestSuccess').find('.return-email').html(data.order_email);
                $('#returnRequestSuccess').find('.return-order').html(data.order_id);
                $('#returnRequestSuccess').modal('show');
            } else if (data.success) {
                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'success', null);
            } else {
                Propeller.Toast.show('Propeller', '', data.message, 'error');
            }
        }
    };

}(window.jQuery, window, document));