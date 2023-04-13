(function ($, window, document) {

    Propeller.QuickOrder = {
        quickRowRecords: 6,
        init: function() {
            $('#fileUpload').off('change').change(this.handle_file_upload);
            $('#add-row').off('click').on('click', this.add_row);
            $('#quick-order-table').off('click').on('click', '.remove-row', this.remove_row);

            this.init_autocomplete();
            this.init_quantity();
            this.init_form();

            $('#nonce').val($('meta[name=security]').attr('content'));
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
            $('input.product-code').on('click', this.focus_code);
        },
        focus_code: function() {
            if (window.quickOrderAutoComplete && window.quickOrderAutoComplete.unInit) {
                window.quickOrderAutoComplete.unInit()
                window.quickOrderAutoComplete = null;
            }
            var maxResults = 6;
            window.quickOrderAutoComplete = new window.autoComplete({
                selector: '#'+$(this).attr('id'),
                threshold: 3,
                debounce: 300,
                cache: false,
                searchEngine: function(query, record) {
                    return 1;
                },
                data: {
                    keys: ['value'],
                    src: async (query) => {
                        let data = [];
                        await Propeller.Ajax.call({
                            url: PropellerHelper.ajax_url,
                            method: 'POST',
                            data: {
                                action: 'quick_product_search',
                                sku: query,
                                offset: 0
                            },
                            dataType: 'json',
                        }).then(function (response) {
                            if (response.hasOwnProperty('items')) {
                                for (let i in response.items) {
                                    var price =  response.items[i].price.discount != null ?  response.items[i].price.discount.value :  response.items[i].price.gross;
                                    data.push({
                                        label: response.items[i].name[0].value,
                                        value: response.items[i].sku,
                                        name: response.items[i].name[0].value,
                                        sku: response.items[i].sku,
                                        id:  response.items[i].productId,
                                        net_price: price,
                                        quantity: 1,
                                        image:  response.items[i].image,
                                        total: price
                                    })
                                }
                            }
                        }).catch(function (args) {
                            console.log('error', args)
                        });

                        if(!data.length) {
                            data.push({
                                name: 'No results found',
                                label: 'No results found',
                                value: 'No results for '+query,
                                id:  'error-404',
                                image:  'all',
                            })
                        }
                        return data;
                    },
                },
                resultsList: {
                    tag: "ul",
                    class: "propeller-autosuggest-items",
                    maxResults: maxResults,
                    element: (list, data) => {
                        list.style.width="500px";
                    },
                },
                resultItem: {
                    tag: "li",
                    class: "propeller-autosuggest-item",
                    element: (item, data) => {
                        //Clear.
                        while (item.firstChild) {
                            item.removeChild(item.firstChild);
                        }
                        // Set img.
                        if (item.value.image !== 'all') {
                            let imgWrap = document.createElement('div');
                            let image = document.createElement('img');
                            imgWrap.className = 'autoComplete_item-img';
                            image.src = data.value.image;
                            image.width = image.height = 35;
                            imgWrap.appendChild(image);
                            item.appendChild(imgWrap);
                        }
                        // Set text.
                        let txtWrap = document.createElement('div');
                        txtWrap.className = 'autoComplete_item-name';
                        txtWrap.innerHTML = data.value.name;
                        item.appendChild(txtWrap);
                    },
                    highlight: "autoComplete_highlight",
                    selected: "autoComplete_selected"
                },
                events: {
                    input: {
                        selection: (event) => {
                            event.preventDefault();

                            if (!event.detail.selection.hasOwnProperty('value')) {
                                return;
                            }
                            let item = event.detail.selection.value;
                            if(item.id === 'error-404') {
                                return;
                            }
                            var index = $(event.target).data('row');
                            $('#product-code-row-' + index).val(item.sku);
                            $('#product-name-row-' + index).val(item.name);
                            $('#product-price-row-' + index).val(Propeller.Global.formatPrice(item.net_price));
                            $('#product-price-row-' + index).attr('data-price', item.net_price);
                            $('#product-quantity-row-' + index).val(item.quantity);
                            $('#product-quantity-row-' + index).attr('data-id', item.id);
                            $('#product-total-row-' + index).val(Propeller.Global.formatPrice(item.total));
                            $('#product-total-row-' + index).attr('data-total', item.total);
                            Propeller.QuickOrder.calculate_totals();

                            if ($('#product-code-row-' + (index + 1)).length) 
                                $('#product-code-row-' + (index + 1)).focus();

                            if (window.quickOrderAutoComplete && window.quickOrderAutoComplete.unInit) {
                                window.quickOrderAutoComplete.unInit()
                                window.quickOrderAutoComplete = null;
                            }

                            return false;
                        }
                    }
                }
            });
            $(this).focus();
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
            $('#product-total-row-' + index).attr('data-total', total).val(Propeller.Global.formatPrice(total));

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

            var exclbtw = Propeller.Global.getPercentage(Propeller.TaxCodes.H, total);
            var subtotal = total - exclbtw;

            $('.propel-total-quick-subtotal').attr('data-subtotal', subtotal).html(Propeller.Global.formatPrice(subtotal));
            $('.propel-total-quick-excl-btw').attr('data-exclbtw', exclbtw).html(Propeller.Global.formatPrice(exclbtw));
            $('.propel-total-quick-price').attr('data-total', total).html(Propeller.Global.formatPrice(total));
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

    //Propeller.QuickOrder.init();

}(window.jQuery, window, document));