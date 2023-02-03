(function ($, window, document) {

    Propeller.Ajax = {
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

            opts.data.nonce = $('meta[name=security]').attr('content');
            opts.data.lang = PropellerHelper.language;

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

}(window.jQuery, window, document));