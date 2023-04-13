(function ($, window, document) {

    var Menu = {
        init: function() {
            var active = $('ul.main-propeller-category').find('a.active');

            if (active.length > 0) {
                if (active.hasClass('has-submenu')) {
                    active.attr('aria-expanded') == 'true';
                    active.click();
                }
                var immediate = $(active).closest('.main-propeller-category-subsubmenu');
                var immediate_parent = $(immediate).closest('.main-propeller-category-subsubmenu');
                var root_parent_button = $(active).closest('.main-item').find('a')[0];

                if ($(root_parent_button).attr('aria-expanded') == 'false')
                    $(root_parent_button).click();

                if (immediate_parent.length > 0 && !$(immediate_parent).hasClass('show'))
                    $(immediate_parent).addClass('show');

                if (immediate.length > 0 && !$(immediate).hasClass('show'))
                    $(immediate).addClass('show');
            }
        }
    };

    Propeller.Menu = Menu

}(window.jQuery, window, document));