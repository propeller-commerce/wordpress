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
        init: function () {

        },
        call: function(args) {
            var overlay = null;
            var opts = {};

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

            opts.data.nonce = propeller_admin_ajax.nonce;
            
            var loading = args.loading || null;
            if (loading)
                overlay = PlainOverlay.show($(loading)[0], {
                    blur: 2,
                    style: {
                        fillColor: '#888'
                        // background: 'transparent', 
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
        accordion: null,
		init: function () {
            $('#exclusions').off('change').on('change', this.handle_exclusions);
            $('#closed_portal').off('change').on('change', this.display_exclusions);
            $('#use_recaptcha').off('change').on('change', this.display_recaptcha);
            $('#add_page_btn').off('click').on('click', this.add_new_row);
            $('.delete-btn').off('click').on('click', this.delete_row);

            $('#propel_settings_form').off('submit').on('submit', this.submit_form);
            $('#propel_pages_form').off('submit').on('submit', this.submit_form);
            $('#propel_behavior_form').off('submit').on('submit', this.submit_form);
            $('#propeller_cache_form').off('submit').on('submit', this.submit_form);

            // Built with https://github.com/michu2k/Accordion
            this.accordion = new Accordion('.accordion-container');

            $('.propel-add-lng-btn').off('click').on('click', this.add_slug_row);

            $('#generate_sitemap').off('click').on('click', this.generate_sitemap);

            this.check_slug_buttons();
		},
        add_slug_row: function(event) {
            event.preventDefault();

            var template = $('#slug_row_template').html();
            var page_id = $(this).data('page_id');
            var index = $(this).closest('.propel-page-row').data('index');

            var slug_id = Propeller.Admin.get_next_slug_index(index);
            
            template = template.replaceAll('{slug-id}', slug_id);
            template = template.replaceAll('{page-id}', page_id);
            template = template.replaceAll('{index}', index);

            $('.page-slugs-container-' + index).append(template);

            if ($(this).closest('.page-slug-row').find('.page-slugs-languages option').length == $('.page-slugs-container-' + index).find('.page-slug-row').length)
                $('.page-slugs-container-' + index).find('.propel-add-lng-btn').remove();
            else
                $(this).remove();

            $('.propel-add-lng-btn').off('click').on('click', Propeller.Admin.add_slug_row);

            return false;
        },
        get_next_slug_index: function(index) {
            var last_slug_id = parseInt($('.page-slugs-container-' + index).find('.page-slug-row:last-child').data('id'));
            
            return last_slug_id + 1;
        },
        check_slug_buttons: function() {
            $('.page-slug-containers').each(function(i, obj) {
                console.log($(obj).data('index'));
                
                var langs_length = $(obj).find('.page-slugs-languages:first option').length;
                var slugs = $(obj).find('.page-slug-row').length;

                if (langs_length == slugs)
                    $(obj).find('.propel-add-lng-btn').remove();
                else 
                    $(obj).find('.propel-add-lng-btn:not(:last-child)').remove();
            });
        },
        submit_form: function(event) {
            event.preventDefault();

            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: $(this).serializeObject(),
                loading: $(this),
                success: function(data, msg, xhr) {
                    Propeller.Alert.show(data.message, data.success);
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
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
        display_recaptcha: function(event) {
            if ($(this).is(':checked'))
                $('#recaptcha_settings').show();
            else 
                $('#recaptcha_settings').hide();
        },
        add_new_row: function(event) {
            event.preventDefault();

            var lastIndex = $('.propel-pages-container').find('.propel-page-row').length;
            
            var template = $('#page_row_template').html();
            template = template.replaceAll('{index}', lastIndex);

            $('.propel-pages-container > .accordion-container').append(template);

            if (Propeller.Admin.accordion) {
                Propeller.Admin.accordion.update();
                Propeller.Admin.accordion.open(lastIndex);
            }
                
            $('.propel-add-lng-btn').off('click').on('click', Propeller.Admin.add_slug_row);

            return false;
        },
        delete_row: function(event) {
            var id = parseInt($(this).attr('data-id'));

            $(this).closest('.propel-page-acc-row').remove();

            if (id > 0) {
                var delPagesArr = $('#delete_pages').val().split(',');
                delPagesArr.push(id);

                $('#delete_pages').val(delPagesArr.join(','));
            }
        },
        generate_sitemap: function(event) {
            event.preventDefault();

            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                timeout: 0,
                data: {
                    action: 'propel_generate_sitemap'
                },
                loading: $(this),
                success: function(data, msg, xhr) {
                    Propeller.Alert.show(data.message, data.success);

                    if (data.reload)
                        window.location.href = `?page=${data.page}&tab=${data.tab}`;
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
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
            $('#restore_translations_form').off('submit').on('submit', this.restore_translations);
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

                    Propeller.Translations.load_backups();
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
                    Propeller.Alert.show(data.message, data.success);
                    Propeller.Translations.load_backups();
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
                    Propeller.Alert.show(data.message, data.success);
                    Propeller.Translations.load_backups();
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
                    Propeller.Alert.show(data.message, data.success);
                    Propeller.Translations.load_backups();
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
        },
        restore_translations: function(event) {
            event.preventDefault();

            var form_data = $(this).serializeObject();
            form_data.action = 'restore_translations';

            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: form_data,
                loading: $(this),
                success: function(data, msg, xhr) {
                    if (data.success)
                        window.location.reload();
                    else 
                        Propeller.Alert.show(data.message, data.success);
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });

            return false;
        },
        load_backups: function() {
            Propeller.Ajax.call({
                url: propeller_admin_ajax.ajaxurl,
                method: 'POST',
                data: {
                    action: 'load_translations_backups'
                },
                loading: $('#backup_date'),
                success: function(data, msg, xhr) {
                    if (data.success)
                        $('#backup_date').html(data.options);
                    else 
                        Propeller.Alert.show(data.message, data.success);
                },
                error: function() {
                    // Propeller.Toast.show('Propeller', __('just now', 'propeller-ecommerce'), arguments[0].responseText, 'error', null, 3000);
                    console.log('error', arguments);
                }
            });
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
        show: function(message, success) {
            $(this.alert).removeClass('alert-success');
            $(this.alert).removeClass('alert-danger');

            $(this.alert).addClass(success ? 'alert-success' : 'alert-danger');

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
