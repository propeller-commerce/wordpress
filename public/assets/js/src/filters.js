 (function ($, window, document) {

    Propeller.Filters =  {
        sliders: [],
        slider_running: false,
        init: function (initNumericFilters = true) {
            //$('form.filterForm').off('submit').submit(this.filter_form_submit);
            //$('form.filterForm input').not('.numeric-min').not('.numeric-max').off('change').change(this.filters_change);

            $(document).on('submit', 'form.filterForm', this.filter_form_submit);
            $(document).on('change', 'form.filterForm input:not(.numeric-min):not(.numeric-max)', this.filters_change);
            //$('a.btn-active-filter').off('click').on('click', this.active_filters_click);
            //$('.btn-remove-active-filters').off('click').on('click', this.clear_filters);
            $(document).on('click', 'a.btn-active-filter',  this.active_filters_click);
            $(document).on('click', '.btn-remove-active-filters', this.clear_filters);

            if (initNumericFilters)
                this.init_numeric_filters();

            $(document).on('propeller-search-success', this.init_filters)
        },
        init_filters: function(event, filters, active_filter) {
            Propeller.Filters.redraw_filters(filters, active_filter);
            Propeller.Filters.enable_filters();
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
                current = Propeller.Global.parseQuery(window.location.search);

            $('form.filterForm').each(function(index, frm){
                formData = $.extend(formData, $(frm).serializeObject());
            });

            pageUrl += '?' + Propeller.Global.buildQuery(formData);

            formData.action = $(this).find('input[name="action"]').val();

            Propeller.Global.changeAjaxPage(formData, $(document).attr('title'), pageUrl);

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
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
        apply_filter: function(filters, slug, do_add, callback = null, current = {}) {
            Propeller.Filters.disable_filters();

            var pageUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

            if (window.location.href.indexOf('?') > -1 && typeof current.action == 'undefined')
                current = Propeller.Global.parseQuery(window.location.search);

            for (var i = 0; i < filters.length; i++) {
                if (do_add) {
                    if (filters[i].name.indexOf('_from') > -1 || filters[i].name.indexOf('_to') > -1) {
                        current[filters[i].name] = filters[i].value;
                    }
                    else {
                        filters[i].value = encodeURIComponent(filters[i].value);

                        if (typeof current[filters[i].name] == 'undefined')
                            current[filters[i].name] = filters[i].value;
                        else {
                            current[filters[i].name] = current[filters[i].name] + '^' + filters[i].value;
                        }
                    }
                }
                else {
                    if (current[filters[i].name].indexOf('^') > -1) {
                        filters[i].value = encodeURIComponent(filters[i].value);
                        
                        var filterVals = current[filters[i].name].split('^');

                        var filterIndex = filterVals.indexOf(filters[i].value);
                        filterVals.splice(filterIndex, 1);

                        current[filters[i].name] = filterVals.join('^');
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
            var obj_id = null;

            if (slug == '') {
                if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.category) > -1)
                    path = PropellerHelper.slugs.category;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.search) > -1)
                    path = PropellerHelper.slugs.search;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.brand) > -1)
                    path = PropellerHelper.slugs.brand;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.machines) > -1)
                    path = PropellerHelper.slugs.machines;

                var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
                
                if (url_chunks !== null) {
                    if (PropellerHelper.behavior.ids_in_url) {
                        id_chunks = url_chunks[2].split('/');

                        slug = id_chunks[1];
                        obj_id = id_chunks[0];
                    }
                    else 
                        slug = url_chunks[2];
                } 
            }

            current.action = $(Propeller.product_container).parent().data('action');
            current[$(Propeller.product_container).parent().data('prop_name')] = $(Propeller.product_container).parent().data('prop_value');
            current.view = $(Propeller.product_container).parent().data('liststyle');

            if (do_add)
                current.active_filter = filters[0].name;

            if (!obj_id && PropellerHelper.behavior.ids_in_url && window.location.pathname.indexOf('/' + PropellerHelper.slugs.category) > -1) {
                path = PropellerHelper.slugs.category;

                var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
                
                if (url_chunks !== null) {
                    if (PropellerHelper.behavior.ids_in_url) {
                        id_chunks = url_chunks[2].split('/');

                        obj_id = id_chunks[0];
                    }
                } 

                if (obj_id)
                    current.obid = obj_id;
            }

            pageUrl += '?' + Propeller.Global.buildQuery(current);

            Propeller.Global.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: current,
                loading: Propeller.product_container,
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Filters.redraw_filters(data.filters, current.active_filter);

                    Propeller.Filters.enable_filters();

                    Propeller.Frontend.init();
                    Propeller.CatalogListstyle.init(current.view);

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
                current = Propeller.Global.parseQuery(window.location.search);


            for (var prop in current) {
                if (prop.indexOf('attr_') >= 0 || prop.indexOf('price_') >= 0)
                    delete current[prop];
            }

            delete current.page;
            delete current.active_filter;

            var path = '';
            var obj_id = null;

            if (slug == '') {
                if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.category) > -1)
                    path = PropellerHelper.slugs.category;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.search) > -1)
                    path = PropellerHelper.slugs.search;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.brand) > -1)
                    path = PropellerHelper.slugs.brand;
                else if (window.location.pathname.indexOf('/' + PropellerHelper.slugs.machines) > -1)
                    path = PropellerHelper.slugs.machines;

                var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
                if (url_chunks !== null) {
                    if (PropellerHelper.behavior.ids_in_url) {
                        id_chunks = url_chunks[2].split('/');

                        slug = id_chunks[1];
                        obj_id = id_chunks[0];
                    }
                    else 
                        slug = url_chunks[2];
                }
            }

            if (!obj_id && PropellerHelper.behavior.ids_in_url && window.location.pathname.indexOf('/' + PropellerHelper.slugs.category) > -1) {
                path = PropellerHelper.slugs.category;

                var url_chunks = new RegExp(`\/(${path})\/(.*?)\/`).exec(window.location.pathname);
                
                if (url_chunks !== null) {
                    if (PropellerHelper.behavior.ids_in_url) {
                        id_chunks = url_chunks[2].split('/');

                        obj_id = id_chunks[0];
                    }
                } 

                if (obj_id)
                    current.obid = obj_id;
            }
            
            current.action = $('form.filterForm:first').find('input[name="action"]').val();

            current[$('form.filterForm:first').find('input[name="prop_name"]').val()] = $('form.filterForm:first').find('input[name="prop_value"]').val()
            current.view = $(Propeller.product_container).parent().data('liststyle');

            pageUrl += '?' + Propeller.Global.buildQuery(current);

            Propeller.Global.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: current,
                loading: Propeller.product_container,
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Filters.redraw_filters(data.filters, null);

                    Propeller.Filters.enable_filters();

                    Propeller.Frontend.init();
                    Propeller.CatalogListstyle.init(current.view);
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
                    if (i > 0) {
                        if (typeof $(newFilters[i - 1]).attr('id') == 'undefined')
                            $(oldFiltersContainer).append(newFilters[i]);
                        else
                            $(oldFiltersContainer).find('#' + $(newFilters[i - 1]).attr('id')).after(newFilters[i]);
                    }
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
    //Propeller.Filters.init();

}(window.jQuery, window, document));