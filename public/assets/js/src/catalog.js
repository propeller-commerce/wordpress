(function ($, window, document) {

    Propeller.CatalogListstyle = {
        init: function (list_style = 'blocks') {
            var $catalogProductList = $('.propeller-product-list');

            $('.liststyle-options').find('.btn-liststyle').off('click').on('click' , function(event) {
                event.preventDefault();
                event.stopPropagation();

                var $this = $(this);
                var $liststyle = $this.data('liststyle');

                $('.liststyle-options').find('.btn-liststyle').each(function(i, el) {
                    var $that = $(el);
                    $that.removeClass('active');
                    $catalogProductList.removeClass($that.data('liststyle'));
                });
                $this.addClass('active');

                // Set class on productlist
                $catalogProductList.addClass($liststyle);

                return false;
            });

            $('.liststyle-options').find('.btn-liststyle').removeClass('active');
            $('.liststyle-options').find('[data-liststyle="' + list_style + '"]').addClass('active');
        },
    };

    //Propeller.CatalogListstyle.init();

}(window.jQuery, window, document));