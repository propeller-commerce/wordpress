(function ($, window, document) {

    Propeller.Frontend = {
        init: function () {
            window.onpopstate = this.popState;
            Propeller.Cart.init();
            Propeller.Paginator.init();
            Propeller.Menu.init();
            Propeller.OffCanvasLayout.init();
        },
        popState: function (event) {
            if (window.location.pathname.indexOf(PropellerHelper.slugs.category) > -1)
                Propeller.Frontend.handleCatalog(event.state);
            if (window.location.pathname.indexOf(PropellerHelper.slugs.search) > -1)
                Propeller.Frontend.handleSearch(event.state, PropellerHelper.slugs.search);
            if (window.location.pathname.indexOf(PropellerHelper.slugs.brand) > -1)
                Propeller.Frontend.handleSearch(event.state, PropellerHelper.slugs.brand);
            if (window.location.pathname.indexOf(PropellerHelper.slugs.machines) > -1)
                Propeller.Frontend.handleMachines(event.state, PropellerHelper.slugs.machines);
        },
        handleCatalog: function (state) {

            Propeller.Global.scrollTo(Propeller.product_container);
            var page_data = {};

            var url_chunks = new RegExp(`\/(${PropellerHelper.slugs.category})\/(.*?)\/`).exec(window.location.pathname);

            if (state)
                page_data = state;
            else {
                page_data.slug = url_chunks[2];
                page_data.action = 'do_filter';
            }

            var active_filter = typeof page_data.active_filter != 'undefined' ? page_data.active_filter : null;

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: page_data,
                loading: $(Propeller.product_container),
                success: function (data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    $(document).trigger('propeller-search-success',[data.filters, active_filter])

                    Propeller.Frontend.init();

                    if (typeof Propeller.Frontend.callback != 'undefined') {
                        for (var i = 0; i < Propeller.Frontend.callback.length; i++) {
                            if (typeof Propeller.Frontend.callback[i] == 'function') {
                                Propeller.Frontend.callback[i]();
                                delete Propeller.Frontend.callback[i];
                            }
                        }
                    }
                },
                error: function () {
                    console.log('error', arguments);
                }
            });
        },
        handleMachines: function (state) {

            Propeller.Global.scrollTo(Propeller.product_container);
            var page_data = {};

            // var url_chunks = new RegExp(`\/(${PropellerHelper.slugs.machines})\/(.*?)\/`).exec(window.location.pathname);
            var url_chunks = window.location.pathname.split('/');

            if (state)
                page_data = state;
            else {
                page_data.slug = url_chunks[url_chunks.length - 2];
                page_data.action = 'do_machine';
            }

            var active_filter = typeof page_data.active_filter != 'undefined' ? page_data.active_filter : null;

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: page_data,
                loading: $(Propeller.product_container),
                success: function (data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    $(document).trigger('propeller-search-success',[data.filters, active_filter])

                    Propeller.Frontend.init();

                    if (typeof Propeller.Frontend.callback != 'undefined') {
                        for (var i = 0; i < Propeller.Frontend.callback.length; i++) {
                            if (typeof Propeller.Frontend.callback[i] == 'function') {
                                Propeller.Frontend.callback[i]();
                                delete Propeller.Frontend.callback[i];
                            }
                        }
                    }
                },
                error: function () {
                    console.log('error', arguments);
                }
            });
        },
        handleSearch: function (state, page) {
            Propeller.Global.scrollTo(Propeller.product_container);
            var page_data = {};

            var url_chunks = new RegExp(`\/(${page})\/(.*?)\/`).exec(window.location.pathname);

            if (state)
                page_data = state;
            else {
                if (page == PropellerHelper.slugs.brand) {
                    page_data.manufacturer = url_chunks[2];
                    page_data.action = 'do_brand';
                } else if (page == PropellerHelper.slugs.search) {
                    page_data.term = url_chunks[2];
                    page_data.action = 'do_search';
                }
            }

            var active_filter = typeof page_data.active_filter != 'undefined' ? page_data.active_filter : null;

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: page_data,
                loading: $(Propeller.product_container),
                success: function (data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    $(document).trigger('propeller-search-success',[data.filters, active_filter])

                    Propeller.Frontend.init();

                    if (typeof Propeller.Frontend.callback != 'undefined') {
                        for (var i = 0; i < Propeller.Frontend.callback.length; i++) {
                            if (typeof Propeller.Frontend.callback[i] == 'function') {
                                Propeller.Frontend.callback[i]();
                                delete Propeller.Frontend.callback[i];
                            }
                        }
                    }
                },
                error: function () {
                    console.log('error', arguments);
                }
            });
        }
    };

    //Propeller.Frontend.init();

}(window.jQuery, window, document));