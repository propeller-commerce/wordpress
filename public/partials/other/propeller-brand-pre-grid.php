<?php if( $data->itemsFound > 0 ) { ?>                
    <div class="row align-items-center catalog-result-options">
        <div class="col-12 mb-4 d-md-none">
            <button class="d-flex d-md-none btn-filter-menu" type="button" data-toggle="off-canvas-filters" 
            data-target="#filter_container" aria-controls="filter_container" 
            aria-expanded="false" aria-label="Toon menu">
                <span><?php echo __('Filters', 'propeller-ecommerce'); ?></span>
                <svg class="icon icon-svg" aria-hidden="true">
                    <use xlink:href="#shape-filters"></use>
                </svg>
            </button>
        </div>  
        <div class="col-auto mr-auto catalog-result">
            <div class="catalog-result-count"><span id="catalog_total"><?php echo esc_html($data->itemsFound); ?></span> <?php echo __('results', 'propeller-ecommerce'); ?></div>
        </div>
        
        <?php require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-offset-sort.php'); ?>

        <div class="col-auto d-none d-md-flex align-items-center liststyle-options">
            <div class="input-group">
                <a class="input-group-prepend btn-liststyle" href="" data-liststyle="list" aria-label="Toon als lijst" rel="nofollow">
                    <span class="icon"> 
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-list"></use>
                        </svg>
                    </span>
                </a>
                <a class="input-group-append btn-liststyle active" href="" data-liststyle="blocks" aria-label="Toon als blokken" rel="nofollow">
                    <span class="icon">
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-blocks"></use>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
        <!-- Active filters -->
        <div class="col-12 mb-4 mt-4 active-filters">
            <?php
                $selected_filters = $obj->get_selected_filters($obj->filters->getFilters());
                if(sizeof($selected_filters)) { ?>
                   <span class="label"> <?php echo __('Selected filters', 'propeller-ecommerce'); ?> </span> 
                <?php } 
                
                foreach ($selected_filters as $selected_filter) { 
            ?>
                <a class="btn-active-filter"
                    data-filter='<?php echo esc_html($selected_filter->filter->searchId); ?>'
                    data-value='<?php echo wp_slash($selected_filter->value); ?>'
                    data-type='<?php echo esc_html($selected_filter->filter->type); ?>'>
                        <span class="active-filter-name"><?php echo esc_html($selected_filter->value); ?></span>
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-close"></use>
                        </svg>
                </a>
            <?php } if(sizeof($selected_filters)) { ?>
                <a class="btn-remove-active-filters"><?php echo __('Clear all filters', 'propeller-ecommerce'); ?></a>
            <?php } ?>
        </div>
    </div>
<?php } ?>