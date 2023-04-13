'use strict';

window.Propeller || (window.Propeller = {});

(function ($, window, document) {

    var Modal = {
        modal: $('#add-to-basket-modal'),
        init: function () {
        },
        setProductId: function (productId) {
            this.modal.find('.propel-modal-header').html(productId);
        },
        show: function (item) {
            this.fillModalData(item);

            this.modal.modal('show', {backdrop: 'static'});
        },
        show_content: function (content) {
            $('.modal-product-list').html(content);

            this.modal.modal('show', {backdrop: 'static'});
        },
        fillModalData: function (item) {
            if (item.product.hasOwnProperty('images') && Array.isArray(item.product.images) && item.product.images.length > 0 && item.product.images[0].hasOwnProperty('images') && Array.isArray(item.product.images[0].images) && item.product.images[0].images.length > 0)
                $('.added-item-img').attr('src', item.product.images[0].images[0].url);
            else {
                $('.added-item-img').remove();
                $('.image').append('<span class="no-image"></span>')
            }

            $('.added-item-img').attr('alt', item.product.name[0].value);
            $('.added-item-name').html(item.product.name[0].value);
            $('.added-item-sku').html(item.product.sku);
            $('.added-item-quantity').html(item.quantity);
            $('.added-item-price').html(Propeller.Global.formatPrice(item.totalPrice));
        }
    };

    Propeller.Modal = Modal;

    var BundleModal = {
        modal: $('#add-to-basket-modal'),
        init: function () {
        },
        setProductId: function (bundleId) {
            this.modal.find('.propel-modal-header').html(bundleId);
        },
        show: function (item) {
            this.fillModalData(item);

            this.modal.modal('show', {backdrop: 'static'});
        },
        show_content: function (content) {
            $('.modal-product-list').html(content);

            this.modal.modal('show', {backdrop: 'static'});
        },
        fillModalData: function (item) {

            $('.added-item-img').remove();
            $('.image').append('<span class="no-image"></span>')

            $('.added-item-name').html(item.bundle.name);
            $('.product-sku').remove();
            $('.added-item-quantity').html(item.quantity);
            $('.added-item-price').html(Propeller.Global.formatPrice(item.bundle.price.gross));
        }
    };

    Propeller.BundleModal = BundleModal;

    var ModalForms = {
        init: function () {

        },
        modal_form_submit: function (event) {
            event.preventDefault();
            event.stopPropagation();

            var formData = $(this).serializeObject();

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: formData,
                loading: $(this).closest('.modal-content'),
                success: function (data, msg, xhr) {
                    console.log('response data', data);

                    if (data.status) {
                        if (data.reload)
                            window.location.reload();
                    }
                },
                error: function () {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    }

    Propeller.ModalForms = ModalForms;

}(window.jQuery, window, document));