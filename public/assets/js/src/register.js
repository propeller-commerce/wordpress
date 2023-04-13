(function ($, window, document) {

    const {__, _x, _n, _nx} = wp.i18n;

    Propeller.Register = {
        init: function() {
            $('input[type=radio][name=parentId]').off('change').change(function(e) {
                $('[name="user_type"]').val($(this).data('type'));

                if ($(this).data('type') == 'Customer') {
                    $('[name="taxNumber"]').removeClass('required');
                    $('[name="taxNumber"]').closest('.row.form-group').hide();
                    $('[name="company_name"]').removeClass('required');
                    $('[name="invoice_address[company]"]').removeClass('required');
                    $('[name="delivery_address[company]"]').removeClass('required');
                    $('[name="company_name"]').closest('.row.form-group').hide();
                }
                else {
                    $('[name="taxNumber"]').addClass('required');
                    $('[name="taxNumber"]').closest('.row.form-group').show();
                    $('[name="company_name"]').addClass('required');
                    $('[name="company_name"]').closest('.row.form-group').show();
                    $('[name="invoice_address[company]"]').addClass('required');
                    $('[name="delivery_address[company]"]').addClass('required');
                }
            });

            $('body').off('change', "input[name='save_delivery_address']").on('change', "input[name='save_delivery_address']", function () {
                if ($(this).is(':checked'))
                    $("input[name^='delivery_address']").removeClass('required');
                else
                    $("input[name^='delivery_address']").addClass('required');
            });

            $("input[name='save_delivery_address']").change();
        },
        postprocess: function(data) {
            if (data.is_registered) {
                Propeller.Toast.toast.on('shown.bs.toast', function () {
                    window.location.href = data.redirect;
                });

                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'success', null);
            }
            else if (data.reset_error) {
                var inputMail = $('input[name="user_mail"]');
                inputMail.parent('div.has-success').removeClass('has-success').addClass('has-error');
                $('span.input-user-message').html(data.message);
                $('span.input-user-message').addClass('input-error-message');
            }
            else {
                Propeller.Toast.show('Propeller', '', data.message, 'error');
            }
        }
    };

    //Propeller.Register.init();

}(window.jQuery, window, document));