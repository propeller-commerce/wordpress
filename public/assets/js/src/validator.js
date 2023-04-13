(function ($, window, document) {

    const {__, _x, _n, _nx} = wp.i18n;

    // https://github.com/jquery-validation/jquery-validation
    // https://jqueryvalidation.org/validate/
    Propeller.Validator = {
        init: function () {
            this.assign_validator($('form.validate').not('.modal-edit-form'));

            this.assign_validator($('form.page-login-form'));
            this.assign_validator($('form.header-login-form'));

            // trigger validation for modal forms
            $('.modal').on('shown.bs.modal', function (event) {
                if ($(this).has('form.validate'))
                    Propeller.Validator.assign_validator($(this).find('form.validate'));
            });
        },
        assign_validator: function (forms) {
            if (!forms.length)
                return;

            $(forms[0]).validate({
                debug: false,
                ignore: "",
                rules: {
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_verfification: {
                        required: true,
                        equalTo: '[name="password"]',
                        minlength: 6
                    }
                },
                highlight: function (element) {
                    if ($(element).is(':radio'))
                        $(element).closest('label').removeClass('has-success').addClass('has-error');
                    else
                        $(element).parent().removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    if ($(element).is(':radio'))
                        $(element).closest('label').addClass('has-success').removeClass('has-error');
                    else
                        $(element).parent().addClass('has-success').removeClass('has-error');
                },
                invalidHandler: this.default_error_handler,
                submitHandler: this.default_submit_handler
            });
        },
        default_submit_handler: function (form) {
            var form_data = $(form).serializeObject();

            if (Propeller.Validator.use_recaptcha(form)) {
                grecaptcha.ready(function() {
                    grecaptcha.execute(PropellerHelper.behavior.recaptcha_site_key, {action: 'submit'}).then(function(token) {
                        form_data.rc_token = token;

                        Propeller.Ajax.call({
                            url: PropellerHelper.ajax_url,
                            method: 'POST',
                            data: form_data,
                            loading: $(form).find('[type="submit"]'),
                            success: function (data, msg, xhr) {
                                if (typeof data.postprocess != undefined && typeof data.object != 'undefined') {
                                    Propeller[data.object].postprocess(data.postprocess);
                                    $(document).trigger('propeller_submit_success', data)
                                    $(form).trigger('reset');
                                }
                            },
                            error: function () {
                                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                                console.log('error', arguments);
                            }
                        });
                    });
                });
            }
            else {
                Propeller.Ajax.call({
                    url: PropellerHelper.ajax_url,
                    method: 'POST',
                    data: form_data,
                    loading: $(form).find('[type="submit"]'),
                    success: function (data, msg, xhr) {
                        if (typeof data.postprocess != undefined && typeof data.object != 'undefined') {
                            Propeller[data.object].postprocess(data.postprocess);
                            $(document).trigger('propeller_submit_success', data)
                            $(form).trigger('reset');
                        }
                    },
                    error: function () {
                        Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                        console.log('error', arguments);
                    }
                });
            }
        },
        default_error_handler: function (event, validator) {
            event.preventDefault();
            event.stopPropagation();

            if (validator.errorList.length > 0) {
                for (var i = 0; i < validator.errorList.length; i++)
                    Propeller.Validator.display_error(validator.errorList[i]);
            }

            return false;
        },
        display_error: function (err) {
            if ($(err.element).is(':radio')) {
                if (!$(err.element).closest('.radios-container').find('span.input-error-message').length)
                    $('<span class="input-error-message">' + err.message + '</span>').insertAfter($(err.element).closest('.radios-container'));
                else
                    $(err.element).closest('.radios-container').find('span.input-error-message').html(err.message);

                $(err.element).closest('.radios-container').find('.form-check-label').off('click').click(function (event) {
                    $(this).removeClass('input-error');
                    $(this).closest('.radios-container').parent().find('span.input-error-message').hide();
                });
            } else {
                $(err.element).addClass('input-error');

                if (!$(err.element).next('span.input-error-message').length)
                    $('<span class="input-error-message">' + err.message + '</span>').insertAfter(err.element);
                else
                    $(err.element).next('span.input-error-message').html(err.message);

                $(err.element).off('focus').focus(function (event) {
                    $(this).removeClass('input-error');
                    $(this).next('span.input-error-message').hide();
                });
            }
        },
        use_recaptcha: function(form) {
            return typeof PropellerHelper.behavior.use_recaptcha != 'undefined' && 
                   PropellerHelper.behavior.use_recaptcha == true && 
                   typeof PropellerHelper.behavior.recaptcha_site_key != 'undefined' && 
                   PropellerHelper.behavior.recaptcha_site_key != '' && 
                   ($(form).hasClass('page-login-form') || $(form).hasClass('header-login-form') || $(form).hasClass('register-form'));
        }
    };

    //Propeller.Validator.init();

}(window.jQuery, window, document));