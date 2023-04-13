(function ($, window, document) {

    Propeller.Paginator = {
        prev: $('.propeller-listing-pagination a.page-item.previous'),
        next: $('.propeller-listing-pagination a.page-item.next'),
        init: function () {
            $('.propeller-listing-pagination a.page-item').off('click').click(this.do_paging);
            $('.liststyle-options .btn-liststyle').off('click').click(this.sort_offset);
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
            var current = Propeller.Global.parseQuery(window.location.search);

            current.action = $(this).data('action');
            current.offset = $('select[name="catalog-offset"]').val();
            current.sort = $('select[name="catalog-sort"]').val();
            current.view = $(this).data('liststyle');
            current[$(this).data('prop_name')] = $(this).data('prop_value');

            if (PropellerHelper.behavior.ids_in_url) {
                current.obid = $(this).data('obid');

                if (typeof current.obid == 'undefined' || current.obid == '')
                    delete current.obid;
            }
            
            delete current.page;
            
            pageUrl += '?' + Propeller.Global.buildQuery(current);

            Propeller.Global.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Global.scrollTo($(Propeller.product_container));
            Propeller.CatalogListstyle.init(current.view);

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: current,
                loading: $(Propeller.product_container),
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Frontend.init();
                    Propeller.CatalogListstyle.init(current.view);
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
            var current = Propeller.Global.parseQuery(window.location.search);

            current.action = $(this).data('action');
            current.offset = $('select[name="catalog-offset"]').val();
            current.sort = $('select[name="catalog-sort"]').val();
            current.view = $('.liststyle-options').find('.btn-liststyle.active').data('liststyle');

            current[$(this).data('prop_name')] = $(this).data('prop_value');

            current.page = $(this).data('page');

            if (PropellerHelper.behavior.ids_in_url) {
                current.obid = $(this).data('obid');

                if (typeof current.obid == 'undefined' || current.obid == '')
                    delete current.obid;
            }

            if ($(this).attr('disabled') == 'disabled')
                return;

            pageUrl += '?' + Propeller.Global.buildQuery(current);

            Propeller.Global.changeAjaxPage(current, $(document).attr('title'), pageUrl);

            Propeller.Global.scrollTo($(Propeller.product_container));

            Propeller.Ajax.call({
                url: PropellerHelper.ajax_url,
                method: 'POST',
                data: current,
                loading: $(Propeller.product_container),
                success: function(data, msg, xhr) {
                    $(Propeller.product_container).html(data.content);

                    Propeller.Frontend.init();
                    Propeller.CatalogListstyle.init(current.view);
                },
                error: function() {
                    console.log('error', arguments);
                }
            });

            return false;
        }
    };

    //Propeller.Paginator.init();

}(window.jQuery, window, document));