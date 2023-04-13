(function ($, window, document) {

    Propeller.AccountPaginator = {
        paging_container: $('.propeller-account-pagination'),
        prev: $('.propeller-account-pagination a.page-item.previous'),
        next: $('.propeller-account-pagination a.page-item.next'),
        loading_el: $('.propeller-account-list'),
        scroll_to_el: $('.propeller-account-table'),

        init: function () {
            $('.propeller-account-pagination a.page-item').off('click').click(this.do_paging);
        },
        do_paging: function (event) {
            event.preventDefault();
            event.stopPropagation();
            var current = Propeller.Global.parseQuery(window.location.search);

            current.action = $(Propeller.AccountPaginator.paging_container).data('action');
            current.page = $(this).data('page');

            if ($(this).attr('disabled') == 'disabled')
                return;

            Propeller.Global.scrollTo($(Propeller.AccountPaginator.scroll_to_el));

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: current,
                loading: Propeller.AccountPaginator.loading_el,
                success: function (data, msg, xhr) {
                    $(Propeller.AccountPaginator.loading_el).html(data.content);

                    Propeller.AccountPaginator.init();
                },
                error: function () {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    };

    //Propeller.AccountPaginator.init();

}(window.jQuery, window, document));