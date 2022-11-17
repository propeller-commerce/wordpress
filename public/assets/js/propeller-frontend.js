/**
 * Propeller Plugin JS Library
*/
'use strict';

const { __, _x, _n, _nx } = wp.i18n;

window.Propeller || (window.Propeller = {});

(function($, window, document) {
    Propeller.days = [
        __('Sunday', 'propeller-ecommerce'), 
        __('Monday', 'propeller-ecommerce'), 
        __('Tuesday', 'propeller-ecommerce'), 
        __('Wednesday', 'propeller-ecommerce'), 
        __('Thursday', 'propeller-ecommerce'), 
        __('Friday', 'propeller-ecommerce'), 
        __('Saturday', 'propeller-ecommerce')
    ];

    Propeller.months = [
        __("January", 'propeller-ecommerce'), 
        __("February", 'propeller-ecommerce'), 
        __("March", 'propeller-ecommerce'), 
        __("April", 'propeller-ecommerce'), 
        __("May", 'propeller-ecommerce'), 
        __("June", 'propeller-ecommerce'),
        __("July", 'propeller-ecommerce'), 
        __("August", 'propeller-ecommerce'), 
        __("September", 'propeller-ecommerce'), 
        __("October", 'propeller-ecommerce'), 
        __("November", 'propeller-ecommerce'), 
        __("December", 'propeller-ecommerce')
    ];

	// Detect Internet Explorer
	Propeller.isIE = navigator.userAgent.indexOf("Trident") >= 0;
	// Detect Edge
	Propeller.isEdge = navigator.userAgent.indexOf("Edge") >= 0;
	// Detect Mobile
	Propeller.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    Propeller.TaxCodes = {
        H: 21,
        L: 9,
        N: 0
    };

    // '#propeller-product-list' doesn't reload filters
    // '.propeller-product-listing' reloads filters
    Propeller.product_container = '#propeller-product-list';
        // PropellerHelper.behavior.reload_filters == 0 
        //     ? '#propeller-product-list' 
        //     : '.propeller-product-listing';


    // Helper functions and extensions
    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    
    // Global AJAX handler
    var Ajax = {
        init: function () {

        },
        call: function(args) {
            var opts = {};
            var overlay = null;

            opts.url = args.url;
            opts.type = args.method || 'GET';
            opts.data = args.data || {};
            opts.dataType = args.dataType || 'json';
            opts.success = args.success || null;
            opts.error = args.error || null;
            opts.complete = function() {
                // hide the loader and remove it's instance
                if (typeof overlay != 'undefined' && overlay) {
                    overlay.hide();
                    overlay = null;
                }
            }
            
            var loading = args.loading || null;
            if (loading)
                overlay = PlainOverlay.show($(loading)[0], {
                    blur: 2,
                    style: {
                        background: 'transparent', 
                        // face: place loader here
                    }
                });
            
            var ajax = $.ajax(opts);

            return ajax;
        }
    };

    Propeller.Ajax = Ajax;

    
    // Global validator

    // https://github.com/jquery-validation/jquery-validation
    // https://jqueryvalidation.org/validate/
    var Validator = {
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
        assign_validator: function(forms) {
            if (!forms.length)
                return;

            $(forms[0]).validate({
                debug: false,
                ignore: "",
                highlight: function(element) {
                    if ($(element).is(':radio')) 
                        $(element).closest('label').removeClass('has-success').addClass('has-error');
                    else
                        $(element).parent().removeClass('has-success').addClass('has-error');
                },
                unhighlight: function(element) {
                    if ($(element).is(':radio'))
                        $(element).closest('label').addClass('has-success').removeClass('has-error');
                    else
                        $(element).parent().addClass('has-success').removeClass('has-error');
                },
                invalidHandler: this.default_error_handler,
                submitHandler: this.default_submit_handler
            });
        },
        default_submit_handler: function(form) {   

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(form).serializeObject(),
                loading: $(form).find('[type="submit"]'),
                success: function(data, msg, xhr) {
                    if (typeof data.postprocess != undefined && typeof data.object != 'undefined') {
                        Propeller[data.object].postprocess(data.postprocess);
                        $(form).trigger('reset');
                    }
                        
                },
                error: function() {
                    Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });
        },
        default_error_handler: function(event, validator) {
            event.preventDefault();
            event.stopPropagation();
            
            if (validator.errorList.length > 0) {
                for (var i = 0; i < validator.errorList.length; i++)
                    Propeller.Validator.display_error(validator.errorList[i]);
            }

            return false;
        },
        display_error: function(err) {
            if ($(err.element).is(':radio')) {
                if (!$(err.element).closest('.radios-container').find('span.input-error-message').length)
                    $('<span class="input-error-message">' + err.message + '</span>').insertAfter($(err.element).closest('.radios-container'));
                else 
                    $(err.element).closest('.radios-container').find('span.input-error-message').html(err.message);

                $(err.element).closest('.radios-container').find('.form-check-label').off('click').click(function(event) {
                    $(this).removeClass('input-error');
                    $(this).closest('.radios-container').parent().find('span.input-error-message').hide();
                });
            }
            else {
                $(err.element).addClass('input-error');

                if (!$(err.element).next('span.input-error-message').length)
                    $('<span class="input-error-message">' + err.message + '</span>').insertAfter(err.element);
                else 
                    $(err.element).next('span.input-error-message').html(err.message);

                $(err.element).off('focus').focus(function(event) {
                    $(this).removeClass('input-error');
                    $(this).next('span.input-error-message').hide();
                });
            }
        }
    };

    Propeller.Validator = Validator;

    

    var Address = {
        init: function() {
            $('.address-set-default').off('submit').submit(function(e){
                e.preventDefault();

                Propeller.Ajax.call({
                    url: propeller_ajax.ajaxurl,
                    method: 'POST',
                    data: $(this).serializeObject(),
                    loading: $(this).find('[type="submit"]'),
                    success: function(data, msg, xhr) {
                        if (typeof data.postprocess != undefined && typeof data.object != 'undefined')
                            Propeller[data.object].postprocess(data.postprocess);
                    },
                    error: function() {
                        Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                        console.log('error', arguments);
                    }
                });

                return false;
            })
        },
        postprocess: function(data) {
            if (data.status) {
                if (data.reload)
                    window.location.reload();
            }
            else {
                Propeller.Toast.show('Propeller', '', data.message, 'error');
            }
        }
    };
    
    Propeller.Address = Address;


    var Order = {
        postprocess: function(data) {
            if (data.status) {
                if (data.reload)
                    window.location.reload();
                if (data.redirect)
                    window.location.href = data.redirect;
                
            }
            else if (data.return_success) {
                $('#return_modal_' + data.order_id).modal('hide');
                $('#return_modal_' + data.order_id).find('form').trigger('reset');
                $('#returnRequestSuccess').find('.return-email').html(data.order_email);
                $('#returnRequestSuccess').find('.return-order').html(data.order_id);
                $('#returnRequestSuccess').modal('show');
            }
            else if (data.success) {
                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'success', null);
            }
            else {
                Propeller.Toast.show('Propeller', '', data.message,'error');
            }
        }
    };
    
    Propeller.Order = Order;

    var Login = {
        postprocess: function(data) {
            if (data.is_logged_in) {
                Propeller.Toast.toast.on('shown.bs.toast', function () {
                    window.location.href = data.redirect;
                });

                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'success', null);
            }
            else {
                // if(data.message_user) {
                //     var inputMail = $('input[name="user_mail"]');
                //     inputMail.parent('div.has-success').removeClass('has-success').addClass('has-error');
                //     $('span.input-user-message').html(data.message);
                //     $('span.input-user-message').addClass('input-error-message');
                // }
                // else if(data.message_pass) {
                //     var inputPassword = $('input[name="user_password"]');
                //     inputPassword.parent('div.has-success').removeClass('has-success').addClass('has-error');
                //     $('span.input-pass-message').html(data.message);
                //     $('span.input-pass-message').addClass('input-error-message');
                // }
                //else 
                    var inputMail = $('input[name="user_mail"]');
                    inputMail.parent('div.has-success').removeClass('has-success').addClass('has-error');
                    var inputPassword = $('input[name="user_password"]');
                    inputPassword.parent('div.has-success').removeClass('has-success').addClass('has-error');
                    Propeller.Toast.show('Propeller', '', data.message, 'error');
            }
        }
    };
    
    Propeller.Login = Login;


    var Register = {
        init: function() {
            $('input[type=radio][name=parentId]').off('change').change(function(e) {
                $('[name="user_type"]').val($(this).data('type'));

                if ($(this).data('type') == 'Customer') {
                    $('[name="taxNumber"]').removeClass('required');
                    $('[name="taxNumber"]').closest('.row.form-group').hide();
                }
                else {
                    $('[name="taxNumber"]').addClass('required');
                    $('[name="taxNumber"]').closest('.row.form-group').show();
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
    
    Propeller.Register = Register;



    var Checkout = {
        postprocess: function(data) {
            if (typeof data.message != 'undefined') 
                Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.message, 'success', null);
            if (typeof data.reload != 'undefined')
                window.location.reload();
            if (typeof data.redirect != 'undefined')
                window.location.href = data.redirect;
        }
    };
    
    Propeller.Checkout = Checkout;



    var Cart = {
        badge: $('.propeller-mini-shoping-cart').find('span.badge'),
        item_updating: false,
        init: function () {
            $('form.add-to-basket-form').off('submit').submit(this.cart_add_item);
            $('form.update-basket-item-form').off('submit').submit(this.cart_update_item);
            $('form.add-to-basket-bundle-form').off('submit').submit(this.cart_add_bundle);
            $('form.update-basket-item-form input[name="notes"]').off('blur').blur(this.cart_update_item_blur);
            $('form.update-basket-item-form input[name="quantity"]').off('blur').blur(this.cart_update_item_blur);
            $('form.update-basket-item-form input[name="notes"]').off('keypress').keypress(this.cart_update_item_keypress);
            $('form.update-basket-item-form input[name="quantity"]').off('keypress').keypress(this.cart_update_item_keypress);
            $('form.update-basket-item-form input[name="quantity"]').off('keyup').keyup(this.quantity_key_up);
            $('form.basket-voucher-form').off('submit').submit(this.cart_add_action_code);
            $('form.basket-remove-voucher-form').off('submit').submit(this.cart_remove_action_code);
            $('form.delete-basket-item-form').off('submit').submit(this.cart_delete_item);
            $('form.dropshipment-form').find('select[name="country"]').bind('change' , function(){
                if($(this).find('option:selected').val() != 'NL') {
                    $('input[name="icp"]').val("Y");
                } else {
                    $('input[name="icp"]').val("N");
                }
            });
            $('input[name="order_type"]').off('change').change(this.cart_change_type);

            $('form.replenish-form').off('submit').submit(this.replenish);

            $('.btn-checkout-ajax').off('click').click(this.change_order_status);
        },
        replenish: function(event) {
            event.preventDefault();

            $('.quick-order-errors').html('');

            var loading_el = $(this).find('button[type="submit"]').length 
                ? $(this).find('button[type="submit"]') 
                : $(this);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serializeObject(),
                loading: loading_el, 
                success: function(data, msg, xhr) {
                    if (data.object == 'QuickOrder') {
                        if (data.postprocess.error) {
                            $('#replenish_form').find('button.btn-quick-order').removeClass('disabled').prop('disabled', false);

                            if (typeof data.postprocess.remove != 'undefined' && data.postprocess.remove.length) {
                                for (var i = 0; i < data.postprocess.remove.length; i++) 
                                    $('#row-' + data.postprocess.remove[i]).find('button.remove-row').click();
                            }
                        }
                    }

                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        change_order_status: function(event) {
            event.stopPropagation();
            event.preventDefault(); 

            var redirect_url = $(this).attr('href');

            var loading_el = $(this);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: {
                    action: 'cart_change_order_status', 
                    order_status: $(this).attr('data-status')
                },
                loading: loading_el, 
                success: function(data, msg, xhr) {
                    if (data.success)
                        window.location.href = redirect_url;
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_add_item: function(event) {
            event.stopPropagation();
            event.preventDefault();

            var loading_el = $(this).find('button[type="submit"]').length 
                ? $(this).find('button[type="submit"]') 
                : $(this);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serialize(),
                loading: loading_el, 
                success: function(data, msg, xhr) {
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_add_bundle: function(event) {
            event.stopPropagation();
            event.preventDefault();

            var loading_el = $(this).find('button[type="submit"]').length 
                ? $(this).find('button[type="submit"]') 
                : $(this);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serialize(),
                loading: loading_el, 
                success: function(data, msg, xhr) {
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_update_item_blur: function(event) {
            if($(this).val() == 0)
                $(this).val($(this).data('min'));
            
            $(this).closest('form.update-basket-item-form').submit();
        },
        cart_update_item_keypress: function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);

            if(keycode == '13') {
                if($(this).val() == 0)
                    $(this).val($(this).data('min'));

                $(this).closest('form.update-basket-item-form').submit();
            }
        },
        quantity_key_up: function(event) {
            var value = $(this).val();

            if (value.indexOf('0') == 0)
                value = value.replace(/^0+/, '');

            $(this).val(value);
        },
        cart_update_item: function(event) {
            event.stopPropagation();
            event.preventDefault();

            if (Propeller.Cart.item_updating)
                return;

            var loading_el = $(this).find('button[type="submit"]').length 
                ? $(this).find('button[type="submit"]') 
                : $(this);
            
            Propeller.Cart.item_updating = true;

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serialize(),
                loading: loading_el,
                success: function(data, msg, xhr) {
                    Propeller.Cart.item_updating = false;
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    Propeller.Cart.item_updating = false;
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_delete_item: function(event) {
            event.stopPropagation();
            event.preventDefault();

            var loading_el = $(this).find('button[type="submit"]').length 
                ? $(this).find('button[type="submit"]') 
                : $(this);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serialize(),
                loading: loading_el,
                success: function(data, msg, xhr) {
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_add_action_code: function(event) {
            event.stopPropagation();
            event.preventDefault();

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serialize(),
                loading: $(event.target).find('button[type="submit"]'),
                success: function(data, msg, xhr) {
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_remove_action_code: function(event) {
            event.stopPropagation();
            event.preventDefault();

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serialize(),
                loading: $(event.target).find('button[type="submit"]'),
                success: function(data, msg, xhr) {
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_change_type: function(event) {
            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: $(event.target).closest('form').serializeObject(),
                loading: $(event.target).closest('form'),
                success: function(data, msg, xhr) {
                    Propeller.Cart.cart_postprocess(data);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        cart_postprocess: function(data) {
            Propeller.Cart.init();

            if (typeof data.postprocess != 'undefined') {
                if (typeof data.postprocess.content != 'undefined')
                    $('#shoppingcart').replaceWith(data.postprocess.content);
                if (typeof data.postprocess.badge != 'undefined')
                    this.cart_update_badge(data.postprocess.badge);
                if (typeof data.postprocess.totals != 'undefined')
                    this.cart_update_totals(data.postprocess.totals, data.postprocess.badge, data.postprocess.postageData, data.postprocess.taxLevels);
                if (typeof data.postprocess.items != 'undefined')
                    this.cart_update_items(data.postprocess.items);
                if (typeof data.postprocess.postageData != 'undefined')
                    this.cart_update_postage(data.postprocess.postageData);    
                if (typeof data.postprocess.taxLevels != 'undefined')
                    this.cart_update_taxLevels(data.postprocess.taxLevels);
                if (typeof data.postprocess.redirect != 'undefined')
                    window.location.href = data.postprocess.redirect;
                if (typeof data.postprocess.reload != 'undefined')
                    window.location.reload();

                if (typeof data.postprocess.remove != 'undefined') {
                    $('div[data-item-id="' + data.postprocess.remove + '"]').fadeOut(400, function(){
                        $(this).remove();

                        if(data.postprocess.badge == 0)
                            window.location.reload();
                    });
                }   
                
                if (typeof data.postprocess.show_modal != 'undefined' && data.postprocess.show_modal) {
                    if (typeof data.postprocess.content != 'undefined')
                        Propeller.Modal.show_content(data.postprocess.content);
                    else if (typeof data.postprocess.item != 'undefined') 
                        Propeller.Modal.show(data.postprocess.item);
                }
                else if (typeof data.postprocess.show_bundle != 'undefined' && data.postprocess.show_bundle) {
                    if (typeof data.postprocess.content != 'undefined')
                        Propeller.BundleModal.show_content(data.postprocess.content);
                    else if (typeof data.postprocess.item != 'undefined') 
                        Propeller.BundleModal.show(data.postprocess.item);
                }
                else {
                    Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), data.postprocess.message, 'error');

                    if (data.object == 'QuickOrder') {
                        $('.quick-order-errors').html(data.postprocess.message);
                    }
                }
                   
                Propeller.Cart.init();
            }
        },
        postprocess: function(data) {
            Propeller.Cart.init();

            if (typeof data.redirect != 'undefined')
                window.location.href = data.redirect;                
        },
        cart_update_badge(count) {
            this.badge.html(count);
        },
        cart_update_postage(postageData) {
            $('.propel-total-shipping').html(Propeller.Frontend.formatPrice(postageData.postage));
        },
        cart_update_totals: function(totals, count, postageData, taxLevels) {
            $('.propel-total-items').html(count);
            $('.propel-total-subtotal').html(Propeller.Frontend.formatPrice(totals.subTotal));
            $('.propel-total-voucher').html(Propeller.Frontend.formatPrice(totals.discountGross));
            $('.propel-total-excl-btw').html(Propeller.Frontend.formatPrice(totals.totalGross));
            $('.propel-total-btw').html(Propeller.Frontend.formatPrice(totals.totalNet - totals.totalGross));
            $('.propel-total-price').html(Propeller.Frontend.formatPrice(totals.totalNet));
            $('.propel-mini-cart-total-price').html(Propeller.Frontend.formatPrice(totals.totalNet));
            $('.propel-total-shipping').html(Propeller.Frontend.formatPrice(postageData.postage));
            if (taxLevels.length) {
                $(taxLevels).each(function(index, taxLevel){
                    if (taxLevel.taxCode == 'H')
                        $('.propel-postage-tax').html('21');
                    else 
                        $('.propel-postage-tax').html('9');
                });
            }
            if (count > 0)
                $('.propel-mini-cart-total-price').html(Propeller.Frontend.formatPrice(totals.totalNet));
            else 
                $('.propel-mini-cart-total-price').html(Propeller.Frontend.formatPrice(0));
        },
        cart_update_taxLevels: function(taxLevels) {
            if (taxLevels.length) {
                $(taxLevels).each(function(index, taxLevel){
                    if (taxLevel.taxCode == 'H')
                        $('.propel-postage-tax').html('21');
                    else 
                        $('.propel-postage-tax').html('9');
                });
            }
        },
        cart_update_items: function(items) {
            if (items.length) {
                $(items).each(function(index, item){
                    $('.basket-item-container[data-item-id="' + item.id + '"]')
                        .find('.basket-item-price').html(Propeller.Frontend.formatPrice(item.totalPrice));
                    
                });
            }
        }
    };

    Propeller.Cart = Cart;

    var QuickOrder = {
        quickRowRecords: 6,
        init: function() {
            $('#fileUpload').off('change').change(this.handle_file_upload);
            $('#add-row').off('click').on('click', this.add_row);
            $('#quick-order-table').off('click').on('click', '.remove-row', this.remove_row);

            this.init_autocomplete();
            this.init_quantity();
            this.init_form();
        },
        handle_file_upload: function() {
            var fileName= $(this).val().split('\\').pop();
            $(this).next().find('span').html(fileName);
        },
        add_row: function() {
            Propeller.QuickOrder.quickRowRecords++;

            $('#quick-order-table').append(`<div class="quick-order-row row" id="row-${Propeller.QuickOrder.quickRowRecords}">
                <div class="col-2 product-code">
                    <input type="text" name="product-code-row-${Propeller.QuickOrder.quickRowRecords}" value="" class="form-control product-code" id="product-code-row-${Propeller.QuickOrder.quickRowRecords}" data-row="${Propeller.QuickOrder.quickRowRecords}">
                    <input type="hidden" name="product-id-row-${Propeller.QuickOrder.quickRowRecords}" value="" class="product-id" id="product-id-row-${Propeller.QuickOrder.quickRowRecords}">
                </div>
                <div class="col-4 product-name pl-0">
                    <input type="text" name="product-name-row-${Propeller.QuickOrder.quickRowRecords}" value="" disabled class="form-control product-name" id="product-name-row-${Propeller.QuickOrder.quickRowRecords}" data-row="${Propeller.QuickOrder.quickRowRecords}">
                </div>
                <div class="col-2 product-price pl-0">
                    <input type="text" name="product-price-row-${Propeller.QuickOrder.quickRowRecords}" value="" disabled class="form-control product-price" id="product-price-row-${Propeller.QuickOrder.quickRowRecords}" data-row="${Propeller.QuickOrder.quickRowRecords}" data-price="">
                </div>
                <div class="col-1 product-quantity pl-0">
                    <input type="number" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" name="product-quantity-row-${Propeller.QuickOrder.quickRowRecords}" value="" class="form-control product-quantity" id="product-quantity-row-${Propeller.QuickOrder.quickRowRecords}" data-row="${Propeller.QuickOrder.quickRowRecords}" data-id="">
                </div>
                <div class="col-2 product-total-price pl-0">
                    <input type="text" name="product-total-row-${Propeller.QuickOrder.quickRowRecords}" value="" disabled class="form-control product-total" id="product-total-row-${Propeller.QuickOrder.quickRowRecords}" data-row="${Propeller.QuickOrder.quickRowRecords}" data-total="">
                </div>
                <div class="remove-row col-1 d-flex align-items-center" data-row="${Propeller.QuickOrder.quickRowRecords}">
                    <button type="button" class="remove-row">
                        <svg class="icon icon-remove">
                            <use class="shape-remove" xlink:href="#shape-remove"></use>
                        </svg>
                    </button>
                </div>
            </div>`);

            Propeller.QuickOrder.init_autocomplete();
            Propeller.QuickOrder.init_quantity();
        }, 
        remove_row: function() {
            var child = $(this).closest('.quick-order-row').nextAll();

            child.each(function () {
                var id = $(this).attr('id');

                var dig = parseInt(id.substring(4));

                var productCode = $(this).children('.product-code').children('input');
                productCode.attr('id',`product-code-row-${dig - 1}`);
                productCode.attr('data-row',`${dig - 1}`);
                productCode.attr('name',`product-code-row-${dig - 1}`);

                var productId = $(this).children('.product-code').children('input.product-id');
                productId.attr('id',`product-id-row-${dig - 1}`);
                productId.attr('data-row',`${dig - 1}`);
                productId.attr('name',`product-id-row-${dig - 1}`);

                var productName = $(this).children('.product-name').children('input');
                productName.attr('id',`product-name-row-${dig - 1}`);
                productName.attr('data-row',`${dig - 1}`);
                productName.attr('name',`product-name-row-${dig - 1}`);

                var productPrice = $(this).children('.product-price').children('input');
                productPrice.attr('id',`product-price-row-${dig - 1}`);
                productPrice.attr('data-row',`${dig - 1}`);
                productPrice.attr('name',`product-price-row-${dig - 1}`);
                
                var productQuantity = $(this).children('.product-quantity').children('input');
                productQuantity.attr('id',`product-quantity-row-${dig - 1}`);
                productQuantity.attr('data-row',`${dig - 1}`);
                productQuantity.attr('name',`product-quantity-row-${dig - 1}`);
                
                var productTotal = $(this).children('.product-total-price').children('input');
                productTotal.attr('id',`product-total-row-${dig - 1}`);
                productTotal.attr('data-row',`${dig - 1}`);
                productTotal.attr('name',`product-total-row-${dig - 1}`);

                $(this).attr('id', `row-${dig - 1}`);
            });

            $(this).closest('.quick-order-row').remove();

            Propeller.QuickOrder.init_autocomplete();
            Propeller.QuickOrder.init_quantity();
            Propeller.QuickOrder.calculate_totals();

            Propeller.QuickOrder.quickRowRecords--;
        }, 
        init_autocomplete: function() {
            $('input.product-code').off('blur').on('blur', this.blur_code);
            $('input.product-code').off('focus').on('focus', this.focus_code);
        },
        blur_code: function() {
            try {
                $(this).autocomplete('destroy');
            }
            catch (ex) {}
        },
        focus_code: function() {
            try {
                $(this).autocomplete('destroy');
            }
            catch (ex) {}

            $(this).autocomplete({
                minLength: 3,
                source: function(request, response) {
                    Propeller.Ajax.call({
                        url: propeller_ajax.ajaxurl,
                        method: 'POST',
                        data: { 
                            action: 'quick_product_search', 
                            sku: request.term,
                            offset: Propeller.Frontend.maxSuggestions
                        },
                        dataType: 'json',
                        success: function(data, msg, xhr) {
                            var items = $.map(data.items, function (item) {
                                var price = item.price.discount != null ? item.price.discount.value : item.price.gross;
    
                                var img = null;
                                
                                if (item.mediaImages &&
                                    item.mediaImages.itemsFound > 0 && 
                                    item.mediaImages.items.length &&
                                    typeof item.mediaImages.items[0].imageVariants != 'undefined' &&
                                    item.mediaImages.items[0].imageVariants.length) {

                                    img = item.mediaImages.items[0].imageVariants[0].url;
                                }

                                return {
                                    label: item.name[0].value,
                                    value: item.sku,
                                    name: item.name[0].value,
                                    sku: item.sku,
                                    id: item.classId,
                                    net_price: price,
                                    quantity: 1,
                                    image: img ? img : PropellerHelper.no_image,
                                    total: price
                                };
                            });

                            response(items);
                        },
                        error: function() {
                            console.log('error', arguments);
                        }, 
                    });
    
                },
                select: function(event, ui) {
                    if (ui.item.value == '')
                        return;

                    var index = $(event.target).data('row');
    
                    $('#product-name-row-' + index).val(ui.item.name);
                    $('#product-price-row-' + index).val(Propeller.Frontend.formatPrice(ui.item.net_price));
                    $('#product-price-row-' + index).attr('data-price', ui.item.net_price);
                    $('#product-quantity-row-' + index).val(ui.item.quantity);
                    $('#product-quantity-row-' + index).attr('data-id', ui.item.id);
                    $('#product-total-row-' + index).val(Propeller.Frontend.formatPrice(ui.item.total));
                    $('#product-total-row-' + index).attr('data-total', ui.item.total);

                    Propeller.QuickOrder.calculate_totals();
                },
                response: function(event, ui) {
                    if (!ui.content.length) {
                        var noResult = { value:"", label: "No results found", no_result: true };
                        ui.content.push(noResult);
                    } 
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                var li = $("<li class='propeller-autosuggest-item'>");
                
                if (typeof item.no_result != 'undefined') {
                    $(li).append('<div class="product-name">No results found</div>').appendTo(ul);
                }
                else {
                    var imgDiv = $('<div class="product-img"></div>');

                    if (item.image != '') {
                        $(imgDiv).append('<img width="35" height="35" src="' + item.image + '" />');
                        $(li).append(imgDiv);
                    }
                        
                    $(li).append('<div class="product-name">' + item.name + '</div>').appendTo(ul);
                }
                
                return li;
            };
        },
        init_quantity: function() {
            $('input.product-quantity').off('blur').blur(this.blur_quantity);
        },
        blur_quantity: function() {
            var quantity = $(this).val();
            
            if (isNaN(parseInt(quantity)))
                return;
    
            var index = $(this).data('row');
            var price = $('#product-price-row-' + index).data('price');
            var total = parseFloat(price) * quantity;
            $('#product-total-row-' + index).attr('data-total', total).val(Propeller.Frontend.formatPrice(total));

            Propeller.QuickOrder.calculate_totals();
        },
        init_form: function() {
            $('.btn-quick-order').off('click').on('click', this.collect_items);
        },
        collect_items: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var items = [];
            var quantities = $('input.product-quantity');
            for (var i = 0; i < quantities.length; i++) {
                if ($(quantities[i]).attr('data-id') == '')
                    continue;

                items.push($(quantities[i]).attr('data-id') + '-' + $(quantities[i]).val());
            }
            
            $(this).closest('form.replenish-form').find('input[name="items"]').val(items.join(','));

            $(this).closest('form.replenish-form').submit();

            $(this).addClass('disabled').attr('disabled', 'disabled');

            Propeller.QuickOrder.reset_form();
            
            return false;
        }, 
        calculate_totals: function() {
            var totals = $('input.product-total');
            var total = 0;

            for (var i = 0; i < totals.length; i++) {
                if ($(totals[i]).attr('data-total') == '')
                    continue;

                total += parseFloat($(totals[i]).attr('data-total'));
            }

            var exclbtw = Propeller.Frontend.getPercentage(Propeller.TaxCodes.H, total);
            var subtotal = total - exclbtw;

            $('.propel-total-quick-subtotal').attr('data-subtotal', subtotal).html(Propeller.Frontend.formatPrice(subtotal));
            $('.propel-total-quick-excl-btw').attr('data-exclbtw', exclbtw).html(Propeller.Frontend.formatPrice(exclbtw));
            $('.propel-total-quick-price').attr('data-total', total).html(Propeller.Frontend.formatPrice(total));
        },
        reset_form: function() {
            var rows = $('div.quick-order-row').length;

            $('div.quick-order-row').remove();
            this.quickRowRecords = 0;

            for (var i = 0; i < rows; i++) 
                this.add_row();

            $('#fileUpload').val('');
            $(this).closest('form.replenish-form').find('input[name="items"]').val('');
            $(this).addClass('disabled').removeAttr('disabled');

        }
    };

    Propeller.QuickOrder = QuickOrder;



    var Filters = {
        sliders: [],
        slider_running: false,
		init: function (initNumericFilters = true) {
            $('form.filterForm').off('submit').submit(this.filter_form_submit);
            $('form.filterForm input').not('.numeric-min').not('.numeric-max').off('change').change(this.filters_change);
            $('a.btn-active-filter').off('click').click(this.active_filters_click);

            $('.btn-remove-active-filters').off('click').click(this.clear_filters);

            if (initNumericFilters)
                this.init_numeric_filters();
		},
        init_numeric_filters: function() {
            $('.numeric-filter').each( function(i , container) {
                var $min = $(container).find('.numeric-min');
                var $max = $(container).find('.numeric-max');
                var $el = $(container).find('.slider')[0];
                
                try {
                    // https://refreshless.com/nouislider/

                    var slider = noUiSlider.create($el, {
                        start: [$($el).data('min'), $($el).data('max')],
                        connect: true,
                        range: {
                            'min': $($el).data('min'),
                            'max': $($el).data('max')
                        },
                        format: {
                            from: function(value) {
                                return Math.round(value);
                            },
                            to: function(value) {
                                return Math.round(value);
                            }
                        }
                    });   
                    
                    slider.on('slide', function (values) {
                        $min.val(values[0]);
                        $max.val(values[1]);
                    });

                    slider.on('set', function(values) {
                        if (!Propeller.Filters.slider_running) {
                            Propeller.Filters.slider_running = true;
                            var slug = $($el).closest('form').find('input[name="prop_value"]').val();
                            
                            Propeller.Filters.apply_filter(
                                [
                                    {
                                        name: $min.attr('name'),
                                        value: values[0]
                                    },
                                    {
                                        name: $max.attr('name'),
                                        value: values[1]
                                    },
                                ], slug, true, function() {
                                    Propeller.Filters.slider_running = false;
                                });
                        }
                    });
                    
                    $min.off("keypress").on("keypress" , Propeller.Filters.min_max_keypress);
                    $max.off("keypress").on("keypress" , Propeller.Filters.min_max_keypress);

                    $min.off("change").on("change" , Propeller.Filters.handle_min);
                    $max.off("change").on("change" , Propeller.Filters.handle_max);

                    Propeller.Filters.sliders.push(slider);
                }
                catch (ex) {
                    // probably slider is already initialized
                }
            });
        },
        min_max_keypress: function(e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                e.preventDefault();
                e.stopPropagation();
                
                $(this).trigger('change');

                return false;
            }
        },
        handle_min: function(e) {
            var $max = $(this).closest('form').find('.numeric-max');

            if($(this).val() < $(this).data('min') || $(this).val() > $max.data('max'))
                $(this).val($(this).data('min'));

            var slider = $(this).closest('form').find('.slider')[0];
            slider.noUiSlider.set([parseInt($(this).val()), null], true);
        },
        handle_max: function(e) {
            var $min = $(this).closest('form').find('.numeric-min');

            if($(this).val() > $(this).data('max') || $(this).val() < $min.data('min'))
                $(this).val($(this).data('max'));

            var slider = $(this).closest('form').find('.slider')[0];
            slider.noUiSlider.set([null, parseInt($(this).val())], true);
        },
        filter_form_submit: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var pageUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

            var formData = {};
            var current = '';

            if (window.location.href.indexOf('?') > -1) 
                current = Propeller.Filters.parseQuery(window.location.search);
            
            $('form.filterForm').each(function(index, frm){
                formData = $.extend(formData, $(frm).serializeObject());
            });

            pageUrl += '?' + Propeller.Filters.buildQuery(formData);
            
            formData.action = $(this).find('input[name="action"]').val();

            Propeller.Frontend.changeAjaxPage(formData, $(document).attr('title'), pageUrl);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: formData,
                loading: Propeller.product_container,
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Frontend.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        filters_change: function(event) {
            var slug = $(this).closest('form').find('input[name="prop_value"]').val();

            Propeller.Filters.apply_filter([{
                    name: $(this).attr('name'),
                    value: $(this).attr('value')
                }], 
                slug,
                $(this).is(':checked')
            );
        },
        apply_filter: function(filters, slug, do_add, callback = null) {
            Propeller.Filters.disable_filters();

            var pageUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

            var current = {};

            if (window.location.href.indexOf('?') > -1) 
                current = Propeller.Filters.parseQuery(window.location.search);
            
            for (var i = 0; i < filters.length; i++) {
                if (do_add) {
                    if (filters[i].name.indexOf('_from') > -1 || filters[i].name.indexOf('_to') > -1) {
                        current[filters[i].name] = filters[i].value;
                    }
                    else {
                        if (typeof current[filters[i].name] == 'undefined')
                            current[filters[i].name] = filters[i].value;
                        else {
                            current[filters[i].name] = current[filters[i].name] + ',' + filters[i].value;
                        }
                    }
                }
                else {
                    if (current[filters[i].name].indexOf(',') > -1) {
                        var filterVals = current[filters[i].name].split(',');

                        var filterIndex = filterVals.indexOf(filters[i].value);
                        filterVals.splice(filterIndex, 1);

                        current[filters[i].name] = filterVals.join(',');
                    }
                    else {
                        delete current[filters[i].name];
                    }
                }
            }

            // remove the page prop when changing filters
            delete current.page;
            delete current.active_filter;

            var path = '';

            if (slug == '') {
                if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.category) > -1) 
                    path = PropellerHelper.slugs.category;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.search) > -1) 
                    path = PropellerHelper.slugs.search;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.brand) > -1) 
                    path = PropellerHelper.slugs.brand;

                var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
                if (url_chunks !== null)
                    slug = url_chunks[2];
            }

            current.action = $('form.filterForm:first').find('input[name="action"]').val();
            current[$('form.filterForm:first').find('input[name="prop_name"]').val()] = $('form.filterForm:first').find('input[name="prop_value"]').val()
            
            if (do_add)
                current.active_filter = filters[0].name;

            pageUrl += '?' + Propeller.Filters.buildQuery(current);

            Propeller.Frontend.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: current,
                loading: Propeller.product_container,
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Filters.redraw_filters(data.filters, current.active_filter);

                    Propeller.Filters.enable_filters();
                    
                    Propeller.Frontend.init();
                    Propeller.CatalogListstyle.init();

                    if (callback && typeof callback == 'function')
                        callback();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });
        },
        disable_filters: function() {
            $("form.filterForm .slider").each(function(i, el){
                el.setAttribute("disabled", true);
            });
            
            $("form.filterForm :input").prop("disabled", true);
        },
        enable_filters: function() {
            $("form.filterForm .slider").each(function(i, el){
                el.removeAttribute("disabled");
            });

            $("form.filterForm :input").prop("disabled", false);
        },
        clear_filters: function(event) {
            event.preventDefault();

            $("form.filterForm :input").prop('checked', false);

            var pageUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

            var slug = $('form.filterForm:first').find('input[name="prop_value"]').val();

            var current = {};
            if (window.location.href.indexOf('?') > -1) 
                current = Propeller.Filters.parseQuery(window.location.search);

            
            for (var prop in current) {
                if (prop.indexOf('attr_') >= 0 || prop.indexOf('price_') >= 0)
                    delete current[prop];
            }

            delete current.page;
            delete current.active_filter;

            var path = '';

            if (slug == '') {
                if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.category) > -1) 
                    path = PropellerHelper.slugs.category;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.search) > -1) 
                    path = PropellerHelper.slugs.search;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.brand) > -1) 
                    path = PropellerHelper.slugs.brand;

                var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
                if (url_chunks !== null)
                    slug = url_chunks[2];
            }

            current.action = $('form.filterForm:first').find('input[name="action"]').val();

            current[$('form.filterForm:first').find('input[name="prop_name"]').val()] = $('form.filterForm:first').find('input[name="prop_value"]').val()
            
            pageUrl += '?' + Propeller.Filters.buildQuery(current);

            Propeller.Frontend.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: current,
                loading: Propeller.product_container,
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Filters.redraw_filters(data.filters, null);

                    Propeller.Filters.enable_filters();
                    
                    Propeller.Frontend.init();
                    Propeller.CatalogListstyle.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        active_filters_click: function(event) {
            var attr_name = $(this).data('filter');
            var attr_value = $(this).data('value') + '~' + $(this).data('type');

            $('input[name="' + attr_name + '"][value="' + attr_value + '"]').prop('checked', false).change();
        },
        parseQuery: function(queryString) { 
			var query = {};
			
            var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');

            if (pairs != '') {
                for (var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split('=');
                    query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1].replace(/\+/g, ' ') || '');
                }
            }

			return query;
		},
        buildQuery: function(qryObj) {
            var aString = [];

            for (const key in qryObj) {
                if (qryObj.hasOwnProperty(key)) {

                    if (typeof qryObj[key] == 'object') {
                        var type = '';
                        var values = [];

                        for (var i = 0; i < qryObj[key].length; i++) {
                            var tmp = qryObj[key][i].split('~');

                            values.push(tmp[0]);
                            type = tmp[1];
                        }

                        var vals = values.join(',');
                        aString.push(`${key}=${vals}~${type}`);
                    }
                    else {
                        aString.push(`${key}=${qryObj[key]}`);
                    }
                }
            }

            return aString.join('&');
        },
        redraw_filters: function(filters, active_filter) {
            $('#filtered_results').html($('#catalog_total').html());

            var newFilterContainer = $(filters);
            var oldFiltersContainer = $('.filter-container');

            var newFilters = $(filters).find('.filter');
            var oldFilters = $(oldFiltersContainer).find('.filter');

            for (var i = 0; i < newFilters.length; i++) {
                if (typeof $(newFilters[i]).attr('id') == 'undefined')
                    continue;

                if ($(newFilters[i]).attr('id') == active_filter && $(oldFiltersContainer).find('#' + active_filter).length > 0)
                    $(oldFiltersContainer).find('#' + active_filter).replaceWith(newFilters[i]);
                else if ($(oldFiltersContainer).find('#' + $(newFilters[i]).attr('id')).length > 0)
                    $(oldFiltersContainer).find('#' + $(newFilters[i]).attr('id')).replaceWith(newFilters[i]);
                else if ($(oldFiltersContainer).find('#' + $(newFilters[i]).attr('id')).length == 0) {
                    if (i > 0)
                        $(oldFiltersContainer).find('#' + $(newFilters[i - 1]).attr('id')).after(newFilters[i]);
                    else 
                        $(oldFiltersContainer).prepend(newFilters[i]);
                }
            }

            for (var i = 0; i < oldFilters.length; i++) {
                if (typeof $(oldFilters[i]).attr('id') == 'undefined')
                    continue;

                if ($(oldFilters[i]).attr('id') == active_filter)
                    continue;

                if ($(newFilterContainer).find('#' + $(oldFilters[i]).attr('id')).length == 0)
                    $(oldFilters[i]).remove();
            }
        }
    };

    Propeller.Filters = Filters;

    var User = {
		init: function () {
            $('.price-toggle a').off('click').click(this.custom_prices);
		},        
        custom_prices: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var show_specific_prices = false;

            if ($(this).closest('.price-toggle').hasClass('price-on')) {
                $(this).closest('.price-toggle').removeClass('price-on').addClass('price-off');
                show_specific_prices = false;
            }
            else {
                $(this).closest('.price-toggle').removeClass('price-off').addClass('price-on');
                show_specific_prices = true;
            }

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
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

    Propeller.User = User;



    var Cluster = {
        order_form: null,
        selected_options: [],
        init: function() {
            this.order_form = $('.add-to-basket-form');

            this.handle_order_button();
            
            $('.cluster-radio').off('change').on('change', this.radio_changed);
            $('.cluster-dropdown').off('change').on('change', this.dropdown_changed);

            this.load_crossupsells();
        },
        handle_order_button: function() {
            var disable = this.order_form.find('input[name="product_id"').val() == '';
            
            this.order_form.find('button[data-type="minus"]').prop('disabled', disable);
            this.order_form.find('button[data-type="plus"]').prop('disabled', disable);
            this.order_form.find('input.quantity').prop('disabled', disable);
            this.order_form.find('button.btn-addtobasket').prop('disabled', disable);
        },
        radio_changed: function(e) {
            Propeller.Cluster.handle_cluster_change(this);
        },
        dropdown_changed: function(e) {
            Propeller.Cluster.handle_cluster_change(this);
        },
        handle_cluster_change: function(obj) {
            if ($(obj).val() == '') {
                console.log('value is empty');
                return;
            }
            
            var slug = '', path = '';

            if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.product) > -1) 
                path = PropellerHelper.slugs.product;
                
            var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
            if (url_chunks !== null)
                slug = url_chunks[2];

            var request_data = $('.cluster-dropdown').serializeObject();
            
            request_data.action = 'update_cluster_content';
            request_data.slug = slug;

            request_data.clicked_attr = $(obj).attr('name');
            request_data.clicked_val = $(obj).val();

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: request_data,
                loading: $('.propeller-product-details'), 
                success: function(data, msg, xhr) {
                    $('.propeller-product-details').html(data.content);
                    
                    Propeller.Frontend.init();
                    Propeller.ProductPlusMinusButtons.init();
                    Propeller.Cluster.gallery_change();
                    Propeller.Cluster.gallery_swipe();
                    Propeller.Cluster.cross_upsell_slider();
                    Propeller.Cluster.init();
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
            Propeller.Gallery.init();
        },
        cross_upsell_slider: function() {
            $('.cluster-crossupsells-slider').not('.slick-initialized').slick({
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
        bundles_slider: function() {
            Propeller.ProductBundlesSlider.init();
        },
        load_crossupsells: function() {
            if ($('.cluster-crossupsells-slider').length) {
                Propeller.Ajax.call({
                    url: propeller_ajax.ajaxurl,
                    method: 'POST',
                    data: {
                        slug: $('.cluster-crossupsells-slider').data('slug'),
                        action: 'load_crossupsells',
                        class: 'cluster'
                    },
                    loading: $('.cluster-crossupsells-slider'), 
                    success: function(data, msg, xhr) {
                        $('.cluster-crossupsells-slider').html(data.content);
                        
                        Propeller.Frontend.init();
                        Propeller.ProductPlusMinusButtons.init();
                        Propeller.Cluster.cross_upsell_slider();
                    },
                    error: function() {
                        console.log('error', arguments);
                    }
                });
            }
        }
    };

    Propeller.Cluster = Cluster;


    var Gallery = {
        init: function() {
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
                slidesToShow: 3,
                slidesToScroll: 3,
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
                            slidesToScroll: 3,
                            arrows: true,
                            focusOnSelect: true,
                            centerMode: false
                        }
                    }
                ]
            });
        }
    };

    Propeller.Gallery = Gallery;

    var GalleryItem = {
        init: function () {
            var items = [];
            $('.gallery-item-slick' , '#slick-gallery').each( function() {
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
    };

    Propeller.GalleryItem = GalleryItem;


    var Slider = {
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
    };

    Propeller.Slider = Slider;
    
    var Frontend = {
        maxSuggestions: 6,
        searchSuggestionTemplate: '<div class="beer-card">' +
        '<div class="beer-card__image">' +
        '<img src="/assets/jquerytypeahead/img/beer_v2/{{group}}/{{display|raw|slugify}}.jpg">' +
        '</div>' +
        '<div class="beer-card__name">{{display}}</div>' +
        '</div>',
		init: function () {
            window.onpopstate = this.popState;
            Propeller.Cart.init();
            Propeller.Filters.init(false);
            Propeller.Paginator.init();
            Propeller.Menu.init();
            Propeller.OffCanvasLayout.init();
		},
        formatPrice: function(price) {
            return Number(parseFloat(price).toFixed(2)).toLocaleString('nl', {
                minimumFractionDigits: 2
            });
        },
        getPercentage: function(percent, total) {
            return (percent / 100) * total;
        }, 
        scrollTo: function(target) {
            $('html, body').stop().animate({
                'scrollTop': $(target).offset().top
            }, 500, 'swing');
        },
        changeAjaxPage: function(data, title, url) {
            if (window.history.pushState)
                window.history.pushState(data, title, url);
            else 
                window.location.href = url;
        },
        popState: function(event) {
            if (window.location.pathname.indexOf(PropellerHelper.slugs.category) > -1)
                Propeller.Frontend.handleCatalog(event.state);
            if (window.location.pathname.indexOf(PropellerHelper.slugs.search) > -1)
                Propeller.Frontend.handleSearch(event.state, PropellerHelper.slugs.search);
            if (window.location.pathname.indexOf(PropellerHelper.slugs.brand) > -1)
                Propeller.Frontend.handleSearch(event.state, PropellerHelper.slugs.brand);
        },
        handleCatalog: function(state) {
            Propeller.Frontend.scrollTo(Propeller.product_container);
            var page_data = {};

            var url_chunks = new RegExp(`\/(${PropellerHelper.slugs.category})\/(.*?)\/`).exec(window.location.pathname);
            
            if(state) 
                page_data = state;
            else {
                page_data.slug = url_chunks[2];
                page_data.action = 'do_filter';
            }

            var active_filter = typeof page_data.active_filter != 'undefined' ? page_data.active_filter : null;

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: page_data,
                loading: $(Propeller.product_container), 
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Filters.redraw_filters(data.filters, active_filter);

                    Propeller.Filters.enable_filters();
                    
                    Propeller.Frontend.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });
        },
        handleSearch: function(state, page) {
            Propeller.Frontend.scrollTo(Propeller.product_container);
            var page_data = {};

            var url_chunks = new RegExp(`\/(${page})\/(.*?)\/`).exec(window.location.pathname);
            
            if(state) 
                page_data = state;
            else {
                if (page == PropellerHelper.slugs.brand) {
                    page_data.manufacturer = url_chunks[2];
                    page_data.action = 'do_brand';
                }                    
                else if (page == PropellerHelper.slugs.search) {
                    page_data.term = url_chunks[2];
                    page_data.action = 'do_search';
                }
            }

            var active_filter = typeof page_data.active_filter != 'undefined' ? page_data.active_filter : null;

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: page_data,
                loading: $(Propeller.product_container), 
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Filters.redraw_filters(data.filters, active_filter);

                    Propeller.Filters.enable_filters();
                    
                    Propeller.Frontend.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });
        }
    };

    Propeller.Frontend = Frontend;

    
    var Paginator = {
        prev: $('.propeller-listing-pagination a.page-item.previous'), 
        next: $('.propeller-listing-pagination a.page-item.next'), 
		init: function () {
            $('.propeller-listing-pagination a.page-item').off('click').click(this.do_paging);
            $('select[name="catalog-offset"]').off('change').change(this.sort_offset);
            $('select[name="catalog-sort"]').off('change').change(this.sort_offset);

            var prevItem = $('.propeller-listing-pagination a.page-item.previous');
            var nextItem = $('.propeller-listing-pagination a.page-item.next');
            var pagesAll = $('.propeller-listing-pagination a.page-item').not(prevItem).not(nextItem);
            var current = $('.propeller-listing-pagination').data('current');
            var total = $('.propeller-listing-pagination').data('max');

            var nStartActive = 1;
            var nEndActive = 3;
            
            if(total > 3) {
                if(current > 1) {
                    nStartActive = current - 1;
                    nEndActive = nStartActive + 2;
                }
                if(nEndActive > total) {
                    nEndActive = total;
                    nStartActive = total - 2;
                }
            } else {
                nEndActive = total;
            }
           
            pagesAll.each(function(i, el) { 
                if(i >= nStartActive-1  && i < nEndActive) {
                    $(el).addClass('visible');
                } else {
                    $(el).removeClass('visible');
                }
            });

            $('.dots' , '.propeller-listing-pagination').hide();

            if(nStartActive > 1) {
                prevItem.addClass('visible');
                pagesAll.eq(0).addClass('visible');
            }
            if(nStartActive > 2) {
                $('#dots-prev').css('display','flex');
            }
            if(nEndActive < pagesAll.length) {
                nextItem.addClass('visible');
                pagesAll.eq( pagesAll.length - 1).addClass('visible');
            }
            if(nEndActive < pagesAll.length -1 ) {
                $('#dots-next').css('display','flex');
            }

		},
        sort_offset: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var pageUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            var current = Propeller.Filters.parseQuery(window.location.search);

            current.action = $(this).data('action');
            current.offset = $('select[name="catalog-offset"]').val();
            current.sort = $('select[name="catalog-sort"]').val();
            current[$(this).data('prop_name')] = $(this).data('prop_value');

            pageUrl += '?' + Propeller.Filters.buildQuery(current);

            Propeller.Frontend.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Frontend.scrollTo($(Propeller.product_container));

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: current,
                loading: $(Propeller.product_container), 
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);
                    
                    Propeller.Frontend.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        },
        do_paging: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var pageUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            var current = Propeller.Filters.parseQuery(window.location.search);

            current.action = $(this).data('action');
            current.offset = $('select[name="catalog-offset"]').val();
            current.sort = $('select[name="catalog-sort"]').val();

            current[$(this).data('prop_name')] = $(this).data('prop_value');

            current.page = $(this).data('page');

            if ($(this).attr('disabled') == 'disabled')
                return;

            pageUrl += '?' + Propeller.Filters.buildQuery(current);

            Propeller.Frontend.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Frontend.scrollTo($(Propeller.product_container));

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: current,
                loading: $(Propeller.product_container), 
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);
                    
                    Propeller.Frontend.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    };

    Propeller.Paginator = Paginator;

    var AccountPaginator = {
        paging_container: $('.propeller-account-pagination'), 
        prev: $('.propeller-account-pagination a.page-item.previous'), 
        next: $('.propeller-account-pagination a.page-item.next'), 
        loading_el: $('.propeller-account-list'),
        scroll_to_el: $('.propeller-account-table'),

		init: function () {
            $('.propeller-account-pagination a.page-item').off('click').click(this.do_paging);
		},
        do_paging: function(event) {
            event.preventDefault();
            event.stopPropagation();
            var current = Propeller.Filters.parseQuery(window.location.search);

            current.action = $(Propeller.AccountPaginator.paging_container).data('action');
            current.page = $(this).data('page');

            if ($(this).attr('disabled') == 'disabled')
                return;

            Propeller.Frontend.scrollTo($(Propeller.AccountPaginator.scroll_to_el));

            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: current,
                loading: Propeller.AccountPaginator.loading_el, 
                success: function(data, msg, xhr) {
                    $(Propeller.AccountPaginator.loading_el).html(data.content);
                    
                    Propeller.AccountPaginator.init();
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    }

    Propeller.AccountPaginator = AccountPaginator;

    var Menu = {
        init: function() {
            var active = $('ul.main-propeller-category').find('a.active');

            if (active.length > 0) {
                var immediate = $(active).closest('.main-propeller-category-subsubmenu');
                var immediate_parent = $(immediate).closest('.main-propeller-category-subsubmenu');
                var root_parent_button = $(active).closest('.main-item').find('a')[0];


                var immediate2 = $(active).closest('.main-item');
                var immediate2sub = $(active).closest('.main-subitem');
                var immediate2subsub = $(active).closest('.main-subsubitem');

                if (immediate2.length > 0) {
                    $(immediate2).addClass('main-parent');
                    $(immediate2).siblings().addClass('d-none');
                }

                if (immediate2sub.length > 0) {
                    $(immediate2).addClass('main-sub-parent');
                    $(immediate2sub).siblings().addClass('d-none');
                }

                if (immediate2subsub.length > 0) {
                    $(immediate2subsub).addClass('main-sub-sub-parent');  
                    $(immediate2subsub).closest('.main-subitem').addClass('main-subitem-parent');                
                }

                var mainParent = $('ul.main-propeller-category').find('li.main-item.main-parent');
                var mainSubParent = $('ul.main-propeller-category').find('li.main-sub-parent');
                var mainSubSubParent = $('ul.main-propeller-category').find('li.main-sub-sub-parent');


                if (mainParent.length > 0) {
                    $('.main-propeller-category-submenu .main-subitem a.has-submenu.dropdown-toggle').addClass('submenu-fix').attr("data-toggle","link");
                    $('.main-propeller-category-submenu .main-subitem a.has-submenu.dropdown-toggle.active').attr("data-toggle","collapse").attr("aria-expanded","true").removeClass('submenu-fix');
                    $('.main-propeller-category-subsubmenu').addClass('d-none');
                    $('.main-propeller-category-submenu .main-subitem a.has-submenu.dropdown-toggle.active').closest('.main-subitem').find('.main-propeller-category-subsubmenu').removeClass('d-none');
                    $('li.main-item.main-parent.main-sub-parent > a').attr("data-toggle","link");
                    $('.main-item.main-parent').find(".main-propeller-category-submenu").addClass('collapse show');

               
                }

                $('li.main-item.main-parent.main-sub-parent .main-subitem').each(function() {
                  
                    if (!$(this).children('.main-propeller-category-subsubmenu').length) {
                        $(this).addClass('no-l-fix')
                    }
                  
                  });

                if (mainSubParent.length > 0) {
                    $('.main-propeller-category-subsubmenu').removeClass('d-none').addClass('collapse show');
                    $('.main-propeller-category-submenu .main-subitem a.has-submenu.dropdown-toggle.active').removeClass('submenu-fix').addClass("active-fix").attr("data-toggle","collapse").attr("aria-expanded","true");
                }

                if (mainSubSubParent.length > 0) {
                    $('.main-propeller-category-submenu .main-subitem.main-subitem-parent a.has-submenu.dropdown-toggle').addClass("act-back-btn");
                    $('.main-item.main-parent.main-sub-parent > a.has-submenu.dropdown-toggle').addClass("d-none");                   
                 
                }

                

            }
        }
    };

    if (window.innerWidth > 768) {}

        Propeller.Menu = Menu;
    
   

    var Search = {
        init: function () {
            $('form[name="search"]').off('submit').on('submit', this.doSearch);
            this.initAutocomplete();
        },
        initAutocomplete: function() {
            if (!$('form[name="search"] input#term:first').length)
                return;
            
            $('form[name="search"] input#term:first').autocomplete({
                minLength: 3,
                appendTo: '.propeller-search-wrapper',
                source: function(request, response) {
                    Propeller.Ajax.call({
                        url: propeller_ajax.ajaxurl,
                        method: 'POST',
                        data: { 
                            action: 'global_search', 
                            term: request.term,
                            offset: Propeller.Frontend.maxSuggestions
                        },
                        dataType: 'json',
                        success: function(data, msg, xhr) {
                            response($.map(data.items, function (item) {
                                var img = null;
                                
                                if (item.mediaImages &&
                                    item.mediaImages.itemsFound > 0 && 
                                    item.mediaImages.items.length &&
                                    typeof item.mediaImages.items[0].imageVariants != 'undefined' &&
                                    item.mediaImages.items[0].imageVariants.length) {

                                    img = item.mediaImages.items[0].imageVariants[0].url;
                                }

                                return {
                                    name: item.name[0].value,
                                    slug: item.slug[0].value,
                                    image: (img ? img : PropellerHelper.no_image),
                                    url: item.url
                                };
                            }));
                        },
                        error: function() {
                            console.log('error', arguments);
                        }, 
                    });

                },
                select: function(event, ui) {
                    window.location.href = ui.item.url;
                },
                open: function(event, ui) {
                    var searchUrl = PropellerHelper.urls.search + $(event.target).val()

                    $('.ui-autocomplete').append('<li class="propeller-autosuggest-item text-justify"><a href="' + searchUrl + '">' + __('See All Result', 'propeller-ecommerce') + '</a></li>');
                },
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                var li = $("<li class='propeller-autosuggest-item'>");
                var imgDiv = $('<div class="product-img"></div>');
                if (item.image != '') {
                    $(imgDiv).append('<img width="35" height="35" src="' + item.image + '" />');
                    $(li).append(imgDiv);
                }
                    

                $(li).append('<div class="product-name">' + item.name + "</div>")
                     .appendTo(ul);
                
                return li;
            };

            $('form[name="search"] input#term:first').keydown(function(event){
                if(event.keyCode == 13) {
                    if($(this).val().length == 0) {
                        event.preventDefault();
                        return false;
                    }

                    window.location.href = PropellerHelper.urls.search + $(this).val() + '/';
                }
            });
        },
        doSearch: function(event) {
            event.preventDefault();
            event.stopPropagation();

            return false;
        }
        
    };

    Propeller.Search = Search;



    var Toast = {
        toast: $('#propel_toast'),
		init: function () {},
        setTitle: function(title) {
            this.toast.find('.propel-toast-title').html(title);
        },
        setSubtitle: function(title) {
            this.toast.find('.propel-toast-subtitle').html(title);
        },
        setBody: function(title) {
            this.toast.find('.propel-toast-body').html(title);
        },
        setToastClass: function(title) {
            this.toast.addClass(title);
        },
        show: function(title, subtitle, message, toastTypeClass, callback, delay = 500) {
            this.setTitle(title);
            this.setSubtitle(subtitle);
            this.setBody(message);
            this.setToastClass(toastTypeClass);

            if (delay != 500)
                this.toast.toast({ delay: delay });

            this.toast.on('hide.bs.toast', function () {
                if (typeof callback == 'function')
                    callback();
            });

            this.toast.toast('show');
        }
    };

    Propeller.Toast = Toast;

    


    var Modal = {
        modal: $('#add-to-basket-modal'),
		init: function () {},
        setProductId: function(productId) {
            this.modal.find('.propel-modal-header').html(productId);
        },
        show: function(item) {
            this.fillModalData(item);

            this.modal.modal('show', {backdrop: 'static'});
        },
        show_content: function(content) {
            $('.modal-product-list').html(content);

            this.modal.modal('show', {backdrop: 'static'});
        },
        fillModalData: function(item) {
            if (item.product.mediaImages && typeof item.product.mediaImages.items != 'undefined' && item.product.mediaImages.items.length)
                $('.added-item-img').attr('src', item.product.mediaImages.items[0].imageVariants[0].url);
            else {
                $('.added-item-img').remove();
                $('.image').append('<span class="no-image"></span>')
            }
               
            $('.added-item-img').attr('alt', item.product.name[0].value);
            $('.added-item-name').html(item.product.name[0].value);
            $('.added-item-sku').html(item.product.sku);
            $('.added-item-quantity').html(item.quantity);
            $('.added-item-price').html(Propeller.Frontend.formatPrice(item.totalPrice));
        }
    };

    Propeller.Modal = Modal;

    var BundleModal = {
        modal: $('#add-to-basket-modal'),
		init: function () {},
        setProductId: function(bundleId) {
            this.modal.find('.propel-modal-header').html(bundleId);
        },
        show: function(item) {
            this.fillModalData(item);

            this.modal.modal('show', {backdrop: 'static'});
        },
        show_content: function(content) {
            $('.modal-product-list').html(content);

            this.modal.modal('show', {backdrop: 'static'});
        },
        fillModalData: function(item) {
            
            $('.added-item-img').remove();
            $('.image').append('<span class="no-image"></span>')
          
            $('.added-item-name').html(item.bundle.name);
            $('.product-sku').remove();
            $('.added-item-quantity').html(item.quantity);
            $('.added-item-price').html(Propeller.Frontend.formatPrice(item.bundle.price.gross));
        }
    };

    Propeller.BundleModal = BundleModal;

    var ModalForms = {
        init: function() {

        },
        modal_form_submit: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var formData = $(this).serializeObject();
            
            Propeller.Ajax.call({
                url: propeller_ajax.ajaxurl,
                method: 'POST',
                data: formData,
                loading: $(this).closest('.modal-content'), 
                success: function(data, msg, xhr) {
                    console.log('response data', data);

                    if (data.status) {
                        if (data.reload)
                            window.location.reload();
                    }
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    }
    
    Propeller.ModalForms = ModalForms;

    

    var OffCanvasLayout = {
        init: function () {
            $('[data-toggle="off-canvas-filters"]').off('click').click(function (e) {
                var $targetLayer = $('.propeller-catalog-filters');
    
                if ($targetLayer.length) {
                    $('.mobile-filter-wrapper').toggleClass('show');
                    $($targetLayer).toggleClass('open');
                }

                $(this).attr('aria-expanded', function(_, attr) { return !(attr == 'true') });
            });
    
            $('.propeller-catalog-filters').find('.close-filters').off('click').click(function (e) {
                var $offCanvasLayer = $('.filter-container');
    
                $('.mobile-filter-wrapper').toggleClass('show');
                $('.propeller-catalog-filters').toggleClass('open');
               
                $('[data-target="#' + $offCanvasLayer.attr('id') + '"]').attr('aria-expanded', function(_, attr) { return !(attr == 'true') });
            });
    
            $('#filter-menu-clear-selection').off('click').click(function(e) {
                
                var $offCanvasLayer = $('.filter-container');
    
                $('.mobile-filter-wrapper').toggleClass('show');
                $('.propeller-catalog-filters').toggleClass('open');
    
                $('[data-target="#' + $offCanvasLayer.attr('id') + '"]').attr('aria-expanded', function(_, attr) { return !(attr == 'true') });
                $('.btn-remove-active-filters').trigger('click');
            });
        }
    };

    Propeller.OffCanvasLayout = OffCanvasLayout;

    var ReturnProductsForm = {
        init: function () {
            $('.return-quantity', '.return-form').off('change').on('change' , function(e) {
                if ($(this).val() > $(this).data('max'))
                    $(this).val($(this).data('max'));
                else if (parseInt($(this).val()) <= 0)
                    $(this).val(1);
            });

            $('.return-reason', '.return-form').off('change').on('change' , function(e) {
                var other = $(this).closest('form').find('#return_reason_other_' + $(this).data('id'));

                $('#return_reason_text_' + $(this).data('id'), '.return-form').val($(this).find('option:selected').text());
                           
                if ($(this).find('option:selected').attr("value") == '5') 
                    $(other).css('display','block');
                else 
                    $(other).css('display','none');
            });
          
            $('.return-product', '.return-form').off('change').on('change' , function() {
                if ($(this).is(':checked'))
                    $('input[data-id=' + $(this).data('id') + '], select[data-id=' + $(this).data('id') + ']').not(this).removeAttr('disabled');
                else 
                    $('input[data-id=' + $(this).data('id') + '], select[data-id=' + $(this).data('id') + ']').not(this).attr('disabled', 'disabled');
            });
        
        },
      
    };

    Propeller.ReturnProductsForm = ReturnProductsForm; 

    var CheckoutForms = {
        init: function () {
            if (window.location.href == PropellerHelper.urls.checkout_summary) {
                var items_count = $('.propel-total-items').html();
                if (typeof items_count != 'undefined' && items_count.trim() == '0')
                    window.location.href = PropellerHelper.urls.cart;
            }

            var delivery_address_fields = $('.new-delivery-address input.required, .new-delivery-address select.required');
            
            var $forms = $('form.form-handler');
            $forms.each(function(){
                var $thisForm = $(this);
                $(this).find('.form-check input[type="radio"]').not('.user-type-radio').unbind('change').bind('change' , function(e) {
                    var $this = $(this);

                    /* Toggle delivery address */
                    if( $this.attr('name') == 'add_delivery_address' ) {
                        if($this.is(':checked') && $this.attr('value') == 'Y') {
                            $('.new-delivery-address').slideDown(500);  
                            
                            $(delivery_address_fields).each(function(index, field){
                                $("input[name^='" + $(field).attr('name') + "']").addClass('required');
                            });
                        } else if( $this.is(':checked') && $this.attr('value') == 'N') {
                            $('.new-delivery-address').slideUp(500);

                            $(delivery_address_fields).each(function(index, field){
                                $("input[name^='" + $(field).attr('name') + "']").removeClass('required');
                            });
                        } 
                    }
                    if( $this.attr('name') == 'carrier' ) {
                        $thisForm.find('.carrier-label').removeClass('selected');
                        $('.carriers').find('label.error').remove();
                        $('.carriers').find('input').removeClass('error');
                        $this.closest('.carrier-label').addClass('selected').show().find('select,input').addClass('required').attr('required' , true);

                    }
                    if( $this.attr('name') == 'payMethod' ) {
                        $thisForm.find('.paymethod').removeClass('selected');
                        $('.paymethods').find('label.error').remove();
                        $('.paymethods').find('input').removeClass('error');
                        $this.closest('.paymethod').addClass('selected').show().find('select,input').addClass('required').attr('required' , true);

                    }
                });
                $(this).find('.form-check input[type="checkbox"]').unbind('change').bind('change' , function(e) {
                    var $this = $(this);

                    /* Toggle delivery address */
                    if( $this.attr('name') == 'save_delivery_address' ) {
                        if(!$this.is(':checked')) {
                            $('.new-delivery-address').slideDown(500);
                        } else {
                            $('.new-delivery-address').slideUp(500);
                        } 
                    }
                   
                });
            });

            $("input[name='add_delivery_address']").change();            
        }
    }
    Propeller.CheckoutForms = CheckoutForms;

    var ProductPlusMinusButtons = {
        init: function () {
            var $form = $('form');
            $form.each(function() {
                var $that = $(this);
                var $btns = $that.find('.btn-quantity');;
                var $quantity = $that.find('.quantity');
                var $unit = eval($quantity.data("unit"));
                var $min = eval($quantity.data("min"));

                var oClickDelay = 0;

                $that.find('.btn-quantity').unbind('click').bind('click' , function() {

                    var $btn = $(this);
                    var $val = eval($quantity.val());

                    try {
                        clearTimeout(oClickDelay);
                    } catch(e) {}

                    if( $btn.data('type') == 'minus') {
                        if(($val - $unit) >= $min) {
                            $quantity.val($val - $unit);
                        }
                    } else {
                        $quantity.val($val + $unit);
                    }
                    if(eval($quantity.val()) > $min) {
                        $btns.eq(0).attr('disabled' , false);
                    } else {
                        $btns.eq(0).attr('disabled' , true);
                    }
                    
                    $quantity.trigger('change');
                    
                });
            })
			
        },
    };

    Propeller.ProductPlusMinusButtons = ProductPlusMinusButtons;

    var ProductFixedWrapper = {
        init: function () {
            var productGalleryExists = false;
            
            if ($('.gallery-container').length) {
                var productGalleryOffsetTop = $('.gallery-container').offset().top;
                productGalleryExists = true;
            }
          
            $('a[href*="#"]:not([href="#"])').unbind('click').bind('click' , function() {
                if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') || location.hostname == this.hostname) {
                    var target = $(this.hash),
                    headerHeight = $('.sticky-header').height() + 20; // Get fixed header height
                    target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                    if ( target.offset().top ) {
                        $('html,body').animate({
                            scrollTop: target.offset().top - headerHeight
                        }, 500);
                        return false;
                    }
                }
            });

            $('#product-sticky-links a').click(function(e) {
                e.preventDefault();

                $('#product-sticky-links a').removeClass('active');

                $(this).addClass('active');

                return false;
            });

            $(window).scroll(function() {        
                if (productGalleryExists) {
                    if ($(this).scrollTop() >= productGalleryOffsetTop) {
                        $('#fixed-wrapper').addClass("show");
                        $('#product-sticky-links').addClass("sticky");
                    } else {
                        $('#fixed-wrapper').removeClass("show");
                        $('#product-sticky-links').removeClass("sticky");
                    }
                }
            });
        },
    };

    Propeller.ProductFixedWrapper = ProductFixedWrapper;


    var CatalogListstyle = {
        init: function () {
            var $catalogProductList = $('.propeller-product-list');
            $('.liststyle-options').find('.btn-liststyle').off('click').on('click' , function(event) {
                event.preventDefault();
                event.stopPropagation();

                var $this = $(this);
                var $liststyle = $this.data('liststyle');
    
                $('.liststyle-options').find('.btn-liststyle').each(function() {
                    var $that = $(this);
                    $that.removeClass('active');
                    $catalogProductList.removeClass($that.data('liststyle'));
                });
                $this.addClass('active');
    
                // Set class on productlist
                $catalogProductList.addClass($liststyle);
                
                return false;
            });
        },
    };

    Propeller.CatalogListstyle = CatalogListstyle;


    var ActionTooltip = {
        init: function () {
            $('.actioncode-tooltip').tooltip({container: ".actioncode-tooltip"});            
        },
    };

    Propeller.ActionTooltip = ActionTooltip;


    var TruncateContent = {
        init: function () {
           
            $('.product-truncate').each(function(e) {
                var $this = $(this);
                console.log('test');
                var $truncateContent = $this.find('.product-truncate-content');
                var $initialContentHeight = $truncateContent.outerHeight();
                var $truncateButton = $this.find('.product-truncate-button a');

                if ($truncateContent.hasClass('show-more')) {
                    $truncateContent.addClass('truncate-description');
                }
                var $truncatedContentHeight = $truncateContent.outerHeight();

                $truncateButton.unbind('click').bind('click' , function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    if ($truncateContent.hasClass('show-more')) {
                        $truncateContent.css( 'height' , $initialContentHeight );
                    } else {
                        $truncateContent.css( 'height' , $truncatedContentHeight );
                    }
                    $truncateButton.children().toggle();
                    $truncateContent.toggleClass('show-more show-less');
                });
            });
        },
    };
    Propeller.TruncateContent = TruncateContent;   

    var RecentlyViewedSlider = {
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
                    url: propeller_ajax.ajaxurl,
                    method: 'POST',
                    data: {
                        ids: window.slider_recent_products,
                        action: 'get_recently_viewed_products'
                    },
                    loading: $('#product-recently-viewed-slider'),
                    success: function(data, msg, xhr) {
                        $('#product-recently-viewed-slider').html(data.content);
                        
                        Propeller.Frontend.init();

                        Propeller.RecentlyViewedSlider.init_slider();
                    },
                    error: function() {
                        console.log('error', arguments);
                    }
                });
            }
        }
    };

    Propeller.RecentlyViewedSlider = RecentlyViewedSlider;


    var Calendar = {
        init: function () {
            $('.form-check input.custom-date').off('change').change(this.show_calendar);
            $('.form-check input.custom-date').off('click').click(this.show_calendar);
        },
        show_calendar: function(e) {
            if ($(this).is(':checked') && $(this).hasClass('custom-date')) {
                $(this).closest('form').find('.delivery.selected').removeClass('selected');

                $('.deliveries').find('label.error').remove();
                $('.deliveries').find('input').removeClass('error');
                $(this).closest('.delivery').addClass('selected').show().find('select,input').addClass('required').attr('required' , true);
                
                var elem = this;
                var calendar = null;
                var today = new Date();
                var calendar_start = new Date();

                calendar_start.setDate(today.getDate() + 3);

                calendar = $('#calendar-wrapper').calendar({
                    weekDayLength: 1,
                    date: calendar_start,
                    disable:function (date) {
                        return date <= today;
                    },                        
                    onClickDate: function(date) {
                        $('#calendar-wrapper').updateCalendarOptions({
                            date: date
                        });

                        var selectedDate = new Date(date);
                        var month = ('0' + (selectedDate.getMonth() + 1)).slice(-2);
                        var day = ('0' + (selectedDate.getDate())).slice(-2)

                        var date_val = `${selectedDate.getFullYear()}-${month}-${day}T00:00:00Z`;

                        $(elem).closest('label.form-check-label').find('input[name="delivery_select"]').val(date_val);

                        $(elem).closest('label.form-check-label').find('svg').remove();
                        $(elem).closest('label.form-check-label').find('div.delivery-day').removeClass('d-none').html(Propeller.days[selectedDate.getDay()]);
                        $(elem).closest('label.form-check-label').find('div.delivery-date').removeClass('d-none').html(selectedDate.getDate() + ' ' + Propeller.months[selectedDate.getMonth()]);
                        
                        $('#datePickerModal').modal('hide');
                    },
                    showYearDropdown: false,
                    startOnMonday: true,
                    enableMonthChange: false,
                    // whether to disable year view
                    enableYearView: false,
                    // shows a Today button on the bottom of the calendar
                    showTodayButton: false,
                    // highlights all other dates with the same week-day
                    highlightSelectedWeekday: false,
                    // highlights the selected week that contains the selected date
                    highlightSelectedWeek: false,
                });

                $('#datePickerModal').modal('show');
            }
        }
    }

    Propeller.Calendar = Calendar;


    var BulkPrices = {
        init: function () {
            $(".bulk-prices .row:first-child").css("font-weight",'bold');
            $(".product-quantity-input").keyup(function(){
                var value = this.value;
                
                $(".bulk-prices .row").css("font-weight",'').filter(function(){
                    var parts = $(this).text().split("-"); 
                    if(parts[1] === undefined) {
                        parts[1] = '';
                    }
                    parts[1] == "" ? parts[1] = Number.MAX_SAFE_INTEGER : "";
                    return (parseInt(parts[0]) <= value && value <= parseInt(parts[1])) 
                }).css("font-weight","bold");
            });
           
        }
    }

    Propeller.BulkPrices = BulkPrices;


    // init all possible instances in Propeller
    $(function() {
        for (const key in Propeller) {
            if (typeof Propeller[key].init != 'undefined')
                Propeller[key].init();

            if (typeof Propeller[key].on_load != 'undefined')
                Propeller[key].on_load();
        }   
    });		
     

}(window.jQuery, window, document));