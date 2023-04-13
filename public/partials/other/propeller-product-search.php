<svg style="display: none;">
    <symbol viewBox="0 0 22 22" id="shape-search"><title>Search</title><path d="M16.884 16.116 12.6 11.832a.394.394 0 0 0-.283-.116h-.342A6.905 6.905 0 0 0 6.906.125 6.905 6.905 0 0 0 0 7.031a6.905 6.905 0 0 0 11.591 5.074v.338a.41.41 0 0 0 .116.283l4.284 4.283a.399.399 0 0 0 .564 0l.329-.329a.399.399 0 0 0 0-.564zm-9.978-3.241a5.84 5.84 0 0 1-5.844-5.844 5.84 5.84 0 0 1 5.844-5.843A5.84 5.84 0 0 1 12.75 7.03a5.84 5.84 0 0 1-5.844 5.844z"/> </symbol>  
</svg>
<div class="propeller-search-wrapper d-flex">
    <var id="result-container" class="result-container"></var>
    <form name="search" method="get">        
        <input type="hidden" name="action" value="search">
        <div class="input-group">
            <label for="term-<?php echo esc_attr($search_id); ?>" class="sr-only"><?php echo __('Search by product', 'propeller-ecommerce'); ?></label>
            <input
                type="search"
                name="term"
                id="term-<?php echo esc_attr($search_id); ?>"
                class="form-control"
                placeholder="<?php echo __('Search by product', 'propeller-ecommerce'); ?>"
                value=""
                autocomplete="off">
            <span class="input-group-append">
                <button class="btn-search" type="submit" aria-label="<?php echo __('Search', 'propeller-ecommerce'); ?>">
                    <svg class="icon icon-search" aria-hidden="true">
                        <use class="header-shape-search" xlink:href="#shape-search"></use>
                    </svg>
                </button>
            </span>
        </div>
    </form>
</div>