(function ($, window, document) {

    const {__, _x, _n, _nx} = wp.i18n;

    Propeller.Cart = {
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
                url: PropellerHelper.ajax_url,
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
                url: PropellerHelper.ajax_url,
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

            if (PropellerHelper.behavior.stock_check) {
                var quantity = parseInt($(this).find('input[name="quantity"]').val());
                var stock = parseInt($(this).find('input[name="quantity"]').data('stock'));
                var diff = quantity - stock;

                if (quantity > stock) {
                    var basket_form = $(this);
                    var product_card = $(this).closest('.propeller-product-card').length 
                        ? $(this).closest('.propeller-product-card')
                        : $(this).closest('.propeller-product-details');

                    var product_image = $(product_card).find('.product-card-image img.img-fluid').attr('src');
                    var product_name = $(product_card).find('.product-name a').html();
                    var product_sku = $(product_card).find('.product-code').html().split(':')[1];
                    
                    $('#add-pre-basket-modal').find('.added-item-quantity').html(quantity);
                    $('#add-pre-basket-modal').find('.added-item-stock').html(stock);

                    $('#add-pre-basket-modal').find('.added-item-name').html(product_name);
                    $('#add-pre-basket-modal').find('.added-item-img').attr('src', product_image);
                    $('#add-pre-basket-modal').find('.added-item-sku').html(product_sku);
                    $('#add-pre-basket-modal').find('.added-item-diff').html(diff);
                    
                    $('#add-pre-basket-modal').find('.added-item-full-quantity').val(quantity);
                    $('#add-pre-basket-modal').find('.added-item-full-stock').val(stock);

                    $('#add-pre-basket-modal').off('shown.bs.modal').on('shown.bs.modal', function (event) {
                        $('form[name="add-product-pre-basket"]').off('submit').on('submit', function(e) {
                            e.preventDefault();

                            var stock_check_data = $(this).serializeObject();

                            var loading_el = $(this).find('.btn-proceed');

                            var data = $(basket_form).serializeObject();
                            data.quantity = stock_check_data.pre_basket_option;

                            Propeller.Ajax.call({
                                url: PropellerHelper.ajax_url,
                                method: 'POST',
                                data: data,
                                loading: loading_el,
                                success: function(data, msg, xhr) {
                                    $('#add-pre-basket-modal').modal('hide');
                                    Propeller.Cart.cart_postprocess(data);
                                },
                                error: function() {
                                    console.log('error', arguments);
                                }
                            });

                            return false;
                        });
                    });

                    $('#add-pre-basket-modal').modal('show');

                    return false;
                }
            }

            var loading_el = $(this).find('button[type="submit"]').length
                ? $(this).find('button[type="submit"]')
                : $(this);

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: $(this).serializeObject(),
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
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: $(this).serializeObject(),
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
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: $(this).serializeObject(),
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
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: $(this).serializeObject(),
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
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: $(this).serializeObject(),
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
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: $(this).serializeObject(),
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
                url: PropellerHelper.ajax_url,
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
            $('.propel-total-shipping').html(Propeller.Global.formatPrice(postageData.postage));
        },
        cart_update_totals: function(totals, count, postageData, taxLevels) {
            $('.propel-total-items').html(count);
            $('.propel-total-subtotal').html(Propeller.Global.formatPrice(totals.subTotal));
            $('.propel-total-voucher').html(Propeller.Global.formatPrice(totals.discountGross));
            $('.propel-total-excl-btw').html(Propeller.Global.formatPrice(totals.totalGross));
            $('.propel-total-btw').html(Propeller.Global.formatPrice(totals.totalNet - totals.totalGross));
            $('.propel-total-price').html(Propeller.Global.formatPrice(totals.totalNet));
            $('.propel-mini-cart-total-price').html(Propeller.Global.formatPrice(totals.totalNet));
            $('.propel-total-shipping').html(Propeller.Global.formatPrice(postageData.postage));
            if (taxLevels.length) {
                $(taxLevels).each(function(index, taxLevel){
                    if (taxLevel.taxCode == 'H')
                        $('.propel-postage-tax').html('21');
                    else
                        $('.propel-postage-tax').html('9');
                });
            }
            else
                $('.propel-postage-tax').html('0');
            if (count > 0)
                $('.propel-mini-cart-total-price').html(Propeller.Global.formatPrice(totals.totalNet));
            else
                $('.propel-mini-cart-total-price').html(Propeller.Global.formatPrice(0));
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
            else
                $('.propel-postage-tax').html('0');
        },
        cart_update_items: function(items) {
            if (items.length) {
                $(items).each(function(index, item){
                    $('.basket-item-container[data-item-id="' + item.id + '"]')
                        .find('.basket-item-price').html(Propeller.Global.formatPrice(item.totalPrice));

                });
            }
        }
    };

    //Propeller.Cart.init();

}(window.jQuery, window, document));