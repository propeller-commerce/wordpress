(function ($, window, document) {
    const {__, _x, _n, _nx} = wp.i18n;
    
    Propeller.ProductPlusMinusButtons = {
        initialized: false,
        init: function () {
            $('.btn-price-request').off('click').on('click', this.add_price_request_product);

            if (Propeller.ProductPlusMinusButtons.initialized) {
                return;
            }
            $(document).on('click touchstart', '.btn-quantity', function () {

                var $that = $(this).closest('form');
                var $btns = $that.find('.btn-quantity');
                var $quantity = $that.find('.quantity');
                var $unit = eval($quantity.data("unit"));
                var $min = eval($quantity.data("min"));

                var oClickDelay = 0;

                var $btn = $(this);
                var $val = eval($quantity.val());

                try {
                    clearTimeout(oClickDelay);
                } catch (e) {
                }

                if ($btn.data('type') === 'minus') {
                    if (($val - $unit) >= $min) {
                        $quantity.val($val - $unit);
                    }
                } else {
                    $quantity.val($val + $unit);
                }
                if (eval($quantity.val()) > $min) {
                    $btns.eq(0).attr('disabled', false);
                } else {
                    $btns.eq(0).attr('disabled', true);
                }

                $quantity.trigger('change');

            })
            Propeller.ProductPlusMinusButtons.initialized = true;
        },
        add_price_request_product: function(event) {
            event.preventDefault();

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: {
                    action: 'propel_add_pr_product',
                    id: $(this).data('id'),
                    code: $(this).data('code'),
                    name: $(this).data('name'),
                    quantity: $(this).data('quantity'),
                    minquantity: $(this).data('minquantity'),
                    unit: $(this).data('unit'),
                },
                loading: $(this),
                success: function(data, msg, xhr) {
                    Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'error');
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
    };

    //Propeller.ProductPlusMinusButtons.init();

}(window.jQuery, window, document));