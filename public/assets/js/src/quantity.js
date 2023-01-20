(function ($, window, document) {

    Propeller.ProductPlusMinusButtons = {
        initialized: false,
        init: function () {
            if (Propeller.ProductPlusMinusButtons.initialized) {
                return;
            }
            $(document).on('click', '.btn-quantity', function () {

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
    };

    //Propeller.ProductPlusMinusButtons.init();

}(window.jQuery, window, document));