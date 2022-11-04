/**
 * Propeller Plugin JS Library
*/
'use strict';

window.Propeller || (window.Propeller = {});

(function($, window, document) {

    Propeller.$window = $(window);
	Propeller.$body = $(document.body);
	
	// Detect Internet Explorer
	Propeller.isIE = navigator.userAgent.indexOf("Trident") >= 0;
	// Detect Edge
	Propeller.isEdge = navigator.userAgent.indexOf("Edge") >= 0;
	// Detect Mobile
	Propeller.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    Propeller.get_active_tab = function() {
        var urlParams = new URLSearchParams(window.location.search);
        var params = Object.fromEntries(urlParams);

        return typeof params.tab != 'undefined' ? params.tab : 'general';
    };
    
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

    var Ajax = {
        overlay: null,
        init: function () {

        },
        call: function(args) {
            var opts = {};

            opts.url = args.url;
            opts.type = args.method || 'GET';
            opts.data = args.data || {};
            opts.dataType = args.dataType || 'json';
            opts.success = args.success || null;
            opts.error = args.error || null;
            opts.complete = function() {
                // hide the loader and remove it's instance
                if (typeof Propeller.Ajax.overlay != 'undefined' && Propeller.Ajax.overlay) {
                    Propeller.Ajax.overlay.hide();
                    delete Propeller.Ajax.overlay;
                }
            }
            
            var loading = args.loading || null;
            if (loading)
                this.overlay = PlainOverlay.show($(loading)[0], {
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



    var Admin = {
        account_pages_checked: false,
		init: function () {
            $('#exclusions').off('change').on('change', this.handle_exclusions);
            $('#closed_portal').off('change').on('change', this.display_exclusions);
            $('#add_page_btn').off('click').on('click', this.add_new_row);
            $('.delete-btn').off('click').on('click', this.delete_row);
		},
        handle_exclusions: function(event) {
            event.preventDefault();

            $('#excluded_pages').val($(this).val().join(','));
        },
        display_exclusions: function(event) {
            if ($(this).is(':checked'))
                $('#exclusions_container').show();
            else 
                $('#exclusions_container').hide();
        },
        add_new_row: function(event) {
            event.preventDefault();

            var lastIndex = parseInt($('.propel-pages-container').find('.propel-page-row:last-child').attr('data-index'));
            lastIndex++;
            
            var template = $('#page_row_template').html();
            template = template.replaceAll('{index}', lastIndex);

            $('.propel-pages-container').append(template);

            return false;
        },
        delete_row: function(event) {
            var id = parseInt($(this).attr('data-id'));

            $(this).closest('.propel-page-row').remove();

            if (id > 0) {
                var delPagesArr = $('#delete_pages').val().split(',');
                delPagesArr.push(id);

                $('#delete_pages').val(delPagesArr.join(','));
            }
        }
    };

    Propeller.Admin = Admin;


    var Translations = {
        init: function() {
            $('#scroll_top').off('click').on('click', this.scroll_to_top);

            $('#scan_translations').off('click').on('click', this.scan_translations);

            $('#propel_translations_form').off('submit').on('submit', this.submit_form);
            $('#create_translations_form').off('submit').on('submit', this.create_translations_file);
            $('#generate_translations_form').off('submit').on('submit', this.generate_translations);
        },
        create_translations_file: function(e) {
            e.preventDefault();
            
            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serializeObject(),
                loading: $(this),
                success: function(data, msg, xhr) {
                    if (data.success)
                        window.location.href = `?page=${data.page}&${data.action}=true&tab=${data.tab}&file=${data.file}`;
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
        },
        submit_form: function(e) {
            e.preventDefault();
            
            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serializeObject(),
                loading: $(this),
                success: function(data, msg, xhr) {
                    console.log(data);

                    if (data.success && typeof data.message != 'undefined')
                        Propeller.Alert.show(data.message);
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
        },
        scan_translations: function(e) {
            e.preventDefault();
            
            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: {
                    action: 'scan_translations'
                },
                loading: $(this),
                success: function(data, msg, xhr) {
                    console.log(data);

                    if (data.success && typeof data.message != 'undefined')
                        Propeller.Alert.show(data.message);
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
        },
        generate_translations: function(e) {
            e.preventDefault();

            var translations_form_data = $('#propel_translations_form').serializeObject();
            delete translations_form_data.action;

            var data = {};
            Object.assign(data, $(this).serializeObject(), translations_form_data);
            
            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: data,
                loading: $(this),
                success: function(data, msg, xhr) {
                    console.log(data);

                    if (data.success && typeof data.message != 'undefined')
                        Propeller.Alert.show(data.message);
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
        },
        scroll_to_top: function(e) {
            $("html, body").animate({ scrollTop: 0 }, "fast");

            return false;
        }
    };

    Propeller.Translations = Translations;

    var Alert = {
        alert: '.propel-alert',
        init: function() {},
        show: function(message) {
            $(this.alert).find('.propel-alert-body').html(message);

            $(this.alert).fadeTo(2000, 500).slideUp(500, function() {
                $(this.alert).slideUp(500);
            });
            // $(this.alert).alert();
        }
    };

    Propeller.Alert = Alert;

    $(function() {
        for (const key in Propeller) {
            if (typeof Propeller[key].init != 'undefined')
                Propeller[key].init();
        }   

        $('#propel_tabs a[href="#' + Propeller.get_active_tab() + '"]').tab('show');
    });		

}(window.jQuery, window, document));
